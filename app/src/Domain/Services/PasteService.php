<?php

namespace App\Domain\Services;

use App\Domain\Entities\HashGeneratorInterface;
use App\Domain\Entities\Paste;
use App\Domain\Entities\PasteRequest;
use App\Domain\Entities\PasteResponse;
use App\Domain\Entities\PastesResponse;
use App\Domain\Exceptions\UniqueHashException;
use App\Domain\Repositories\PasteRepositoryInterface;

final readonly class PasteService
{
    public function __construct(
        private PasteRepositoryInterface $repository,
        private HashGeneratorInterface $hashGenerator,
    )
    {
    }

    public function createPaste(PasteRequest $pasteRequest): PasteResponse
    {
        $paste = Paste::new(
            title: $pasteRequest->title,
            content: $pasteRequest->content,
            expiration: $pasteRequest->expiration,
            exposure: $pasteRequest->exposure,
            generator: $this->hashGenerator,
        );
        if ($this->repository->hasHash($paste->getHash())) {
            throw new UniqueHashException('Hash has already exist.');
        }
        return $this->repository->create($paste);
    }

    public function getPaste(string $hash): ?PasteResponse
    {
        return $this->repository->getPasteByHash($hash);
    }

    public function getPublicPastes(): PastesResponse
    {
        return $this->repository->getPublicPaste();
    }
}