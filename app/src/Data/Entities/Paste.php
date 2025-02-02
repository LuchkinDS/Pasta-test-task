<?php

namespace App\Data\Entities;

use App\Data\Repositories\PasteRepository;
use App\Domain\Entities\Exposure;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PasteRepository::class)]
#[ORM\Table(name: 'pastes')]
#[ORM\Index(name: 'expiration_date_idx', columns: ['expiration_date'])]
#[ORM\Index(name: 'exposure_idx', columns: ['exposure'])]
#[ORM\Index(name: 'hash_idx', columns: ['hash'])]
final class Paste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id;
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $title;
    #[ORM\Column(type: Types::TEXT, columnDefinition: Types::TEXT)]
    private string $content;
    #[ORM\Column(name: 'release_date', type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $releaseDate;
    #[ORM\Column(name: 'expiration_date', type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $expirationDate;
    #[ORM\Column(type: Types::STRING, enumType: Exposure::class)]
    private Exposure $exposure;
    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $hash;
    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private bool $burn;
    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $wasRead;
    public function __construct(
        ?int $id,
        ?string $title,
        string $content,
        DateTimeImmutable $releaseDate,
        DateTimeImmutable $expirationDate,
        Exposure $exposure,
        string $hash,
        bool $burn,
        int $read,
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->releaseDate = $releaseDate;
        $this->expirationDate = $expirationDate;
        $this->exposure = $exposure;
        $this->hash = $hash;
        $this->burn = $burn;
        $this->wasRead = $read;
    }

    public static function new(
        ?int $id,
        ?string $title,
        string $content,
        DateTimeImmutable $releaseDate,
        DateTimeImmutable $expirationDate,
        Exposure $exposure,
        string $hash,
        bool $burn,
        int $read,
    ): self
    {
        return new self(
            id: $id,
            title: $title,
            content: $content,
            releaseDate: $releaseDate,
            expirationDate: $expirationDate,
            exposure: $exposure,
            hash: $hash,
            burn: $burn,
            read: $read,
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTitle(): ?string
    {
        return $this->title;
    }
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }
    public function getContent(): string
    {
        return $this->content;
    }
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }
    public function getReleaseDate(): DateTimeImmutable
    {
        return $this->releaseDate;
    }
    public function setReleaseDate(DateTimeImmutable $releaseDate): self
    {
        $this->releaseDate = $releaseDate;
        return $this;
    }
    public function getExpirationDate(): DateTimeImmutable
    {
        return $this->expirationDate;
    }
    public function setExpirationDate(DateTimeImmutable $expirationDate): self
    {
        $this->expirationDate = $expirationDate;
        return $this;
    }
    public function getExposure(): Exposure
    {
        return $this->exposure;
    }
    public function setExposure(Exposure $exposure): self
    {
        $this->exposure = $exposure;
        return $this;
    }
    public function getHash(): string
    {
        return $this->hash;
    }
    public function setHash(string $hash): self
    {
        $this->hash = $hash;
        return $this;
    }

    public function isBurn(): bool
    {
        return $this->burn;
    }

    public function setBurn(bool $burn): void
    {
        $this->burn = $burn;
    }

    public function getRead(): int
    {
        return $this->wasRead;
    }

    public function setRead(int $read): void
    {
        $this->wasRead = $read;
    }
}