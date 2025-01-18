<?php

namespace App\Domain\Entities;

final readonly class PasteRequest
{
    public function __construct(
        public ?string $title,
        public string $content,
        public Expiration $expiration,
        public Exposure $exposure,
        public bool $burn,
    )
    {
    }
}