<?php

namespace App\Data\Repositories;

use App\Data\Entities\Paste as PasteDb;
use App\Data\Mappers\MapperPaste;
use App\Domain\Entities\Paste;
use App\Domain\Entities\PasteResponse;
use App\Domain\Exceptions\PasteNotFoundException;
use App\Domain\Repositories\PasteRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class PasteRepository implements PasteRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function save(Paste $paste): PasteResponse
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
            WHERE p.hash = :hash
        DQL;
        $query = $this->em->createQuery($dql);
        return $query
            ->setParameter(':hash', $hash)
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
}