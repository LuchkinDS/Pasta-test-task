<?php

namespace App\Domain\Entities;

use DateTimeImmutable;

final class Paste
{
    private Exposure $exposure;
    public function __construct(
        public readonly ?int              $id = null,
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

    public static function burn()
    {
        // TODO: 1. фабрика для создания paste (в фабрику перенести генератор хэша, и enums)
        // TODO: 2. в ответ от Data добавить id, для возможности апдейта pasta
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