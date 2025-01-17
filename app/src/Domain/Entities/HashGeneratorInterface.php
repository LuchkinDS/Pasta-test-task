<?php

namespace App\Domain\Entities;

interface HashGeneratorInterface
{
    public function getHash(): string;
}