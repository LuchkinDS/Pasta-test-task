<?php

namespace App\Presenter\Mappers;

use App\Domain\Entities\Pager;
use App\Presenter\Entities\PagerRequest;

class MapperPager
{
    public static function pagerRequestToPager(PagerRequest $pager): Pager
    {
        return new Pager(
            page: $pager->getPage(),
            limit: $pager->getLimit(),
        );
    }
}