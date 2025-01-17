<?php

namespace App\Domain\Entities;

final readonly class PastesResponse
{
    /** @param array<PasteResponse> $items */
    public function __construct(
        public array $items,
        public ?Pager $pager = null,
    )
    {
    }
}