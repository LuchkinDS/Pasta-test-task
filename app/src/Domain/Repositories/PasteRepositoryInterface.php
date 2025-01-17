<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Paste;
use App\Domain\Entities\PasteResponse;

interface PasteRepositoryInterface
{
    public function getPasteByHash(string $hash): PasteResponse;
    public function save(Paste $paste): PasteResponse;
    public function hasHash(string $hash): bool;
}