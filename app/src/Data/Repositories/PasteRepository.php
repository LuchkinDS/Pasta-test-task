<?php

namespace App\Data\Repositories;

use App\Data\Entities\Paste as PasteDb;
use App\Data\Mappers\MapperPaste;
use App\Domain\Entities\Pager;
use App\Domain\Entities\Paste;
use App\Domain\Entities\PasteResponse;
use App\Domain\Entities\PastesResponse;
use App\Domain\Exceptions\PasteNotFoundException;
use App\Domain\Repositories\PasteRepositoryInterface;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Exception;
use Knp\Component\Pager\PaginatorInterface;

final readonly class PasteRepository implements PasteRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private PaginatorInterface $paginator,
    )
    {
    }

    public function create(Paste $paste): PasteResponse
    {
        $pasteDb = MapperPaste::pasteToPasteDb($paste);
        $this->em->persist($pasteDb);
        $this->em->flush();
        $this->em->clear();
        return MapperPaste::pasteDbToPasteResponse($pasteDb);
    }

    public function update(Paste $paste): PasteResponse
    {
        $pasteDb = $this->em->find(PasteDb::class, $paste->id);
        $pasteDb->setExpirationDate($paste->expirationDate);
        $this->em->flush();
        $this->em->clear();
        return MapperPaste::pasteDbToPasteResponse($pasteDb);
    }

    /**
     * @throws Exception
     */
    public function findPasteByHash(string $hash): ?PasteDb
    {
        $paste = null;
        try {
            $this->em->beginTransaction();
            $currentDate = (new DateTimeImmutable())
                ->setTimezone(new DateTimeZone('UTC'))
                ->format('Y-m-d H:i:s');
            $builder = $this
                ->getQueryBuilderFindPasteByHash($hash)
                ->andWhere('p.expirationDate >= :currentDate')
                ->setParameter('currentDate', $currentDate);
            $paste = $builder->getQuery()->getOneOrNullResult();
            if (!is_null($paste)) {
                $this->em
                    ->createQueryBuilder()
                    ->update(PasteDb::class, 'p')
                    ->set('p.wasRead', $paste->getRead() + 1)
                    ->where("p.id = :id")
                    ->setParameter(':id', $paste->getId())
                    ->getQuery()
                    ->execute();
            }
            $this->em->commit();
        } catch (Exception $e) {
            $this->em->rollback();
            throw new Exception(message: $e->getMessage(), previous: $e->getPrevious());
        }
        return $paste;
    }

    private function getQueryBuilderFindPasteByHash(string $hash): QueryBuilder
    {
        $builder = $this->em
            ->createQueryBuilder()
            ->select('p')
            ->from(PasteDb::class, 'p')
            ->where("p.hash = :hash");

        return $builder->setParameter(':hash', $hash);
    }

    public function hasHash(string $hash): bool
    {
        return !is_null($this->getQueryBuilderFindPasteByHash($hash)->getQuery()->getOneOrNullResult());
    }

    /**
     * @throws Exception
     */
    public function getPasteByHash(string $hash): PasteResponse
    {
        $paste = $this->findPasteByHash($hash);
        if (is_null($paste)) {
            throw new PasteNotFoundException("Paste with hash {$hash} not found!");
        }
        return MapperPaste::pasteDbToPasteResponse($paste);
    }

    public function getPublicPaste(Pager $pager): PastesResponse
    {
        $dql = <<<DQL
            SELECT p FROM App\Data\Entities\Paste p
            WHERE p.expirationDate >= :currentDate
            AND p.exposure = 'public'
            ORDER BY p.id DESC
        DQL;
        $currentDate = (new DateTimeImmutable())
            ->setTimezone(new DateTimeZone('UTC'))
            ->format('Y-m-d H:i:s');
        $query = $this->em->createQuery($dql)
            ->setParameter(':currentDate', $currentDate);
        $pagination = $this->paginator->paginate(
            $query,
            $pager->page,
            $pager->limit
        );
        return MapperPaste::paginatorToPastesResponse($pagination);
    }
}