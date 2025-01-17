<?php

namespace App\Data\Repositories;

use App\Data\Entities\Paste as PasteDb;
use App\Data\Mappers\MapperPaste;
use App\Domain\Entities\Paste;
use App\Domain\Entities\PasteResponse;
use App\Domain\Entities\PastesResponse;
use App\Domain\Exceptions\PasteNotFoundException;
use App\Domain\Repositories\PasteRepositoryInterface;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;

final readonly class PasteRepository implements PasteRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function create(Paste $paste): PasteResponse
    {
        $pasteDb = MapperPaste::pasteToPasteDb($paste);
        $this->em->persist($pasteDb);
        $this->em->flush();
        return MapperPaste::pasteDbToPasteResponse($pasteDb);
    }

    public function findPasteByHash(string $hash): ?PasteDb
    {
        $dql = <<<DQL
            SELECT p FROM App\Data\Entities\Paste p
            WHERE p.hash = :hash AND p.expirationDate >= :currentDatetime
        DQL;
        $currentDate = (new DateTimeImmutable())
            ->setTimezone(new DateTimeZone('UTC'))
            ->format('Y-m-d H:i:s');
        $query = $this->em->createQuery($dql);
        return $query
            ->setParameter(':hash', $hash)
            ->setParameter(':currentDatetime', $currentDate)
            ->getOneOrNullResult();
    }

    public function hasHash(string $hash): bool
    {
        return !is_null($this->findPasteByHash($hash, null));
    }

    public function getPasteByHash(string $hash): PasteResponse
    {
        $paste = $this->findPasteByHash($hash);
        if (is_null($paste)) {
            throw new PasteNotFoundException("Paste with hash {$hash} not found!");
        }
        return MapperPaste::pasteDbToPasteResponse($paste);
    }

    public function getPublicPaste(): PastesResponse
    {
        $dql = <<<DQL
            SELECT p FROM App\Data\Entities\Paste p
            WHERE p.expirationDate >= :currentDate
            AND p.exposure = 'public'
        DQL;
        $query = $this->em->createQuery($dql);
        $currentDate = (new DateTimeImmutable())
            ->setTimezone(new DateTimeZone('UTC'))
            ->format('Y-m-d H:i:s');
        $result =  $query
            ->setParameter(':currentDate', $currentDate)
            ->setMaxResults(10)
            ->getResult();
        return MapperPaste::pastesDbToPastesResponse($result);
    }
}