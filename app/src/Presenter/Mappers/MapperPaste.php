<?php

namespace App\Presenter\Mappers;

use App\Domain\Entities\PasteRequest;
use App\Presenter\Entities\PasteForm;

class MapperPaste
{
    public static function pasteToPasteRequest(PasteForm $paste): PasteRequest
    {
        return new PasteRequest(
            title: $paste->title,
            content: $paste->content,
            expiration: $paste->expiration,
            exposure: $paste->exposure,
            burn: $paste->burn,
        );
    }
}