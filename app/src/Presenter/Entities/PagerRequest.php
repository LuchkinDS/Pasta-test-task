<?php

namespace App\Presenter\Entities;

use Symfony\Component\Validator\Constraints\Range;

final readonly class PagerRequest
{
    public function __construct(
        #[Range(min: 1)]
        public int $page = 1,
        #[Range(min: 1, max: 100)]
        public int $limit = 10,
    )
    {
    }
}