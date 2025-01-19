<?php

namespace App\Domain\Entities;

use DateTimeImmutable;

final class Paste
{
    private Exposure $exposure;
    public function __construct(
        public readonly ?int              $id,
        public readonly ?string           $title,
        public readonly string            $content,
        public readonly DateTimeImmutable $releaseDate,
        public readonly DateTimeImmutable $expirationDate,
        Exposure          $exposure,
        public readonly string            $hash,
        public readonly bool           $burn,
        public readonly int $read,
    )
    {
        $this->setExposure($exposure);
    }

    public function getExposure(): Exposure
    {
        return $this->exposure;
    }

    private function setExposure(Exposure $exposure): void
    {
        $this->exposure = $exposure;
        if ($this->burn) {
            $this->exposure = Exposure::Unlisted;
        }
    }
}