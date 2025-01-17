<?php

namespace App\Domain\Entities;

Enum Exposure: string
{
    case Public = 'public';
    case Unlisted = 'unlisted';

    /** @return array<string> */
    public function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}