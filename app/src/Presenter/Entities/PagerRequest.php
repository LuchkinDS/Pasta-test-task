<?php

namespace App\Presenter\Entities;

use App\Domain\Entities\Pager;
use Symfony\Component\Validator\Constraints as Assert;

final class PagerRequest
{
    #[Assert\Type('integer')]
    private int $page;
    #[Assert\Type('integer')]
    private int $limit;

    public function __construct(
        int $page = 1,
        int $limit = 10,
    )
    {
        $this->limit = abs(min($limit, Pager::MAX_PER_PAGE));
        $this->page = abs($page);
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getPage(): int
    {
        return $this->page;
    }
}