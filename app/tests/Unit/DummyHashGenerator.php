<?php

namespace App\Tests\Unit;

use App\Domain\Entities\HashGeneratorInterface;

final readonly class DummyHashGenerator implements HashGeneratorInterface
{
    public function __construct(private string $hash)
    {
    }

    public function getHash(): string
    {
        return $this->hash;
    }
}