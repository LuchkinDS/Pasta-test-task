<?php

namespace App\Domain\Entities;

class SimpleHashGenerator implements HashGeneratorInterface
{
    public function getHash(): string
    {
        return uniqid();
    }
}