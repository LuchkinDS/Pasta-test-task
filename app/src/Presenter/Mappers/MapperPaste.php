<?php

namespace App\Presenter\Mappers;

use App\Domain\Entities\PasteRequest;
use App\Presenter\Entities\Paste;

class MapperPaste
{
    public static function pasteToPasteRequest(Paste $paste): PasteRequest
    {
        return new PasteRequest(
            title: $paste->title,
            content: $paste->content,
            expiration: $paste->expiration,
            exposure: $paste->exposure,
        );
    }
}