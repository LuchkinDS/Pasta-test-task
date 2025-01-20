<?php

namespace App\Presenter\Mappers;

use App\Domain\Entities\PasteRequest;
use App\Domain\Entities\PasteResponse;
use App\Presenter\Entities\PasteForm;
use App\Presenter\Entities\PastePresentation;

class MapperPaste
{
    public static function pasteFormToPasteRequest(PasteForm $paste): PasteRequest
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