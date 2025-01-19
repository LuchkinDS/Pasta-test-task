<?php

namespace App\Domain\Entities;

final readonly class Pager
{
    public function __construct(
        public int $page = 1,
        public int $limit = 10,
        public int $total = 0,
    )
    {
    }
    public function getMaxPage(): int
    {
        return (int)ceil($this->total/$this->limit);
    }
}