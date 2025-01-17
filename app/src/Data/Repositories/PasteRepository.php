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

    public function findPasteByHash(string $hash): ?PasteDb
    {
        $currentDate = (new DateTimeImmutable())
            ->setTimezone(new DateTimeZone('UTC'))
            ->format('Y-m-d H:i:s');
        $builder = $this
            ->getQueryBuilderFindPasteByHash($hash)
            ->andWhere('p.expirationDate >= :currentDate')
            ->setParameter('currentDate', $currentDate);
        return $builder->getQuery()->getOneOrNullResult();
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