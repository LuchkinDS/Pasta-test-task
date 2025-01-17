<?php

namespace App\Domain\Entities;

enum Expiration: string
{
    case Newer = 'newer';
    case Hour = 'hour';
    case Day = 'day';
    case Week = 'week';
    case Month = 'mouth';

    /** @return array<string> */
    public function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
