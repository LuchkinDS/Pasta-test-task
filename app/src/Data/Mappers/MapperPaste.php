<?php

namespace App\Data\Mappers;

use App\Domain\Entities\Pager;
use App\Domain\Entities\Paste;
use App\Data\Entities\Paste as PasteDb;
use App\Domain\Entities\PasteResponse;
use App\Domain\Entities\PastesResponse;
use Knp\Component\Pager\Pagination\PaginationInterface;

class MapperPaste
{
    public static function pasteToPasteDb(Paste $paste): PasteDb
    {
        return PasteDb::new(
            id: $paste->id,
            title: $paste->title,
            content: $paste->content,
            releaseDate: $paste->releaseDate,
            expirationDate: $paste->expirationDate,
            exposure: $paste->getExposure(),
            hash: $paste->hash,
            burn: $paste->burn,
            read: $paste->read,
        );
    }

    public static function pasteDbToPasteResponse(PasteDb $pasteDb): PasteResponse
    {
        return new PasteResponse(
            id: $pasteDb->getId(),
            title: $pasteDb->getTitle(),
            content: $pasteDb->getContent(),
            releaseDate: $pasteDb->getReleaseDate(),
            expirationDate: $pasteDb->getExpirationDate(),
            exposure: $pasteDb->getExposure(),
            hash: $pasteDb->getHash(),
            burn: $pasteDb->isBurn(),
            read: $pasteDb->getRead(),
        );
    }

    /** @param array<PasteDb> $pastesDb */
    public static function pastesDbToPastesResponse(array $pastesDb): PastesResponse
    {
        return new PastesResponse(
            items: array_map(static fn($pasteDb) => self::pasteDbToPasteResponse($pasteDb), $pastesDb)
        );
    }

    public static function paginatorToPastesResponse(PaginationInterface $pagination): PastesResponse
    {
        return new PastesResponse(
            items: array_map(static fn($pasteDb) => self::pasteDbToPasteResponse($pasteDb), $pagination->getItems()),
            pager: new Pager(
                page: $pagination->getCurrentPageNumber(),
                limit: $pagination->getItemNumberPerPage(),
                total: $pagination->getTotalItemCount(),
            )
        );
    }
}