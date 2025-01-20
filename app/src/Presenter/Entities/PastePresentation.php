<?php

namespace App\Presenter\Entities;

use App\Domain\Entities\Exposure;
use DateTimeImmutable;

final readonly class PastePresentation
{
    public function __construct(
        public ?string $title,
        public string $content,
        public DateTimeImmutable $expirationDate,
        public Exposure $exposure,
        public bool $burn,
    )
    {
    }
}