<?php

namespace App\Domain\Entities;

use DateInterval;
use DateTimeImmutable;
use DateTimeZone;

class Paste
{
    private DateTimeImmutable $releaseDate;
    private DateTimeImmutable $expirationDate;
    private string $hash;
    public function __construct(
        public readonly ?string $title,
        public readonly string $content,
        public readonly Expiration $expiration,
        public readonly Exposure $exposure,
        private readonly HashGeneratorInterface $hashGenerator,
    )
    {
    }

    public static function new(?string $title, string $content, Expiration $expiration, Exposure $exposure, HashGeneratorInterface $generator): self
    {
        $paste = new Paste(
            title: $title,
            content: $content,
            expiration: $expiration,
            exposure: $exposure,
            hashGenerator: $generator
        );
        return $paste->create();
    }

    public function create(): self
    {
        $currentDate = (new DateTimeImmutable())
            ->setTimezone(new DateTimeZone('UTC'));
        $this->releaseDate = $currentDate;
        $this->expirationDate = $currentDate->add($this->getDateInterval());
        $this->hash = $this->hashGenerator->getHash();
        return $this;
    }

    private function getDateInterval(): DateInterval
    {
        return match ($this->expiration) {
            Expiration::Newer => new DateInterval("P100Y"),
            Expiration::Hour => new DateInterval("PT1H"),
            Expiration::Day => new DateInterval("P1D"),
            Expiration::Week => new DateInterval("P1W"),
            Expiration::Month => new DateInterval("P1M"),
        };
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getExpirationDate(): DateTimeImmutable
    {
        return $this->expirationDate;
    }

    public function getReleaseDate(): DateTimeImmutable
    {
        return $this->releaseDate;
    }
}