<?php

namespace App\Data\Mappers;

use App\Domain\Entities\Paste;
use App\Data\Entities\Paste as PasteDb;
use App\Domain\Entities\PasteResponse;

class MapperPaste
{
    public static function pasteToPasteDb(Paste $paste): PasteDb
    {
        return PasteDb::new(
            title: $paste->title,
            content: $paste->content,
            releaseDate: $paste->getReleaseDate(),
            expirationDate: $paste->getExpirationDate(),
            exposure: $paste->exposure,
            hash: $paste->getHash(),
        );
    }

    public static function pasteDbToPasteResponse(PasteDb $pasteDb): PasteResponse
    {
        return new PasteResponse(
            title: $pasteDb->getTitle(),
            content: $pasteDb->getContent(),
            releaseDate: $pasteDb->getReleaseDate(),
            expirationDate: $pasteDb->getExpirationDate(),
            exposure: $pasteDb->getExposure(),
            hash: $pasteDb->getHash()
        );
    }
}