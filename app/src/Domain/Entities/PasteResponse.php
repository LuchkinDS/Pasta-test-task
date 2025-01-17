<?php

namespace App\Domain\Entities;

use DateTimeImmutable;

final readonly class PasteResponse
{
    public function __construct(
        public ?string $title,
        public string $content,
        public DateTimeImmutable $releaseDate,
        public DateTimeImmutable $expirationDate,
        public Exposure $exposure,
        public string $hash,
    )
    {
    }
}