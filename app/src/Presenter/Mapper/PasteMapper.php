<?php

namespace App\Presenter\Mapper;

use App\Domain\Entities\PasteRequest;
use App\Presenter\Entities\Paste;

class PasteMapper
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