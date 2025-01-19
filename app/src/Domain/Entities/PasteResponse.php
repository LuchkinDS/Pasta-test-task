<?php

namespace App\Domain\Entities;

use DateTimeImmutable;

final readonly class PasteResponse
{
    const int READS_TO_BURN = 1;

    public function __construct(
        public int $id,
        public ?string $title,
        public string $content,
        public DateTimeImmutable $releaseDate,
        public DateTimeImmutable $expirationDate,
        public Exposure $exposure,
        public string $hash,
        public bool $burn,
        public int $read,
    )
    {
    }
    public function readyBurn(): bool {
        return $this->burn && $this->read === self::READS_TO_BURN;
    }
}