<?php

namespace App\Presenter\Entities;

use App\Domain\Entities\Expiration;
use App\Domain\Entities\Exposure;
use Symfony\Component\Validator\Constraints as Assert;

class Paste
{
    #[Assert\Length(max: 255)]
    public ?string $title = null;
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 65_535)]
    public string $content;
    #[Assert\NotBlank]
    #[Assert\Type(type: Expiration::class)]
    public Expiration $expiration;
    #[Assert\NotBlank]
    #[Assert\Type(type: Exposure::class)]
    public Exposure $exposure;
}