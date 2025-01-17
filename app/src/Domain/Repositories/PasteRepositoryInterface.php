<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Paste;
use App\Domain\Entities\PasteResponse;
use App\Domain\Entities\PastesResponse;

interface PasteRepositoryInterface
{
    public function getPasteByHash(string $hash): PasteResponse;
    public function create(Paste $paste): PasteResponse;
    public function hasHash(string $hash): bool;
    public function getPublicPaste(): PastesResponse;
}