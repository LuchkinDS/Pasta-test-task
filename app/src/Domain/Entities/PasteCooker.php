<?php

namespace App\Domain\Entities;

use DateInterval;
use DateTimeImmutable;
use DateTimeZone;

final readonly class PasteCooker
{
    public static function new(
        ?string                $title,
        string                 $content,
        Expiration             $expiration,
        Exposure               $exposure,
        HashGeneratorInterface $generator,
        bool $burn,
    ): Paste
    {
        $currentDate = (new DateTimeImmutable())
            ->setTimezone(new DateTimeZone('UTC'));
        $releaseDate = $currentDate;
        $expirationDate = $currentDate->add((new PasteCooker())->getDateInterval($expiration));
        $hash = $generator->getHash();
        return new Paste(
            id: null,
            title: $title,
            content: $content,
            releaseDate: $releaseDate,
            expirationDate: $expirationDate,
            exposure: $exposure,
            hash: $hash,
            burn: $burn,
            read: 0,
        );
    }

    public static function burn(
        int $id,
        ?string $title,
        string $content,
        DateTimeImmutable $releaseDate,
        DateTimeImmutable $expirationDate,
        Exposure $exposure,
        string $hash,
        int $read,
    ): Paste
    {
        $expirationDate = $expirationDate->sub(new DateInterval('P100Y'));
        return new Paste(
            id: $id,
            title: $title,
            content: $content,
            releaseDate: $releaseDate,
            expirationDate: $expirationDate,
            exposure: $exposure,
            hash: $hash,
            burn: true,
            read: $read,
        );
    }

    private function getDateInterval(Expiration $expiration): DateInterval
    {
        return match ($expiration) {
            Expiration::Newer => new DateInterval("P100Y"),
            Expiration::Hour => new DateInterval("PT1H"),
            Expiration::Day => new DateInterval("P1D"),
            Expiration::Week => new DateInterval("P1W"),
            Expiration::Month => new DateInterval("P1M"),
        };
    }
}