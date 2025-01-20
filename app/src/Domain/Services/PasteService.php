<?php

namespace App\Domain\Services;

use App\Domain\Entities\HashGeneratorInterface;
use App\Domain\Entities\Pager;
use App\Domain\Entities\PasteCooker;
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
        $paste = PasteCooker::new(
            title: $pasteRequest->title,
            content: $pasteRequest->content,
            expiration: $pasteRequest->expiration,
            exposure: $pasteRequest->exposure,
            generator: $this->hashGenerator,
            burn: $pasteRequest->burn,
        );
        if ($this->repository->hasHash($paste->hash)) {
            throw new UniqueHashException('Hash has already exist.');
        }
        return $this->repository->create($paste);
    }

    public function getPaste(string $hash): ?PasteResponse
    {
        $pasteResponse = $this->repository->getPasteByHash($hash);
        if($pasteResponse->readyBurn()) {
            $paste = PasteCooker::burn(
                id: $pasteResponse->id,
                title: $pasteResponse->title,
                content: $pasteResponse->content,
                releaseDate: $pasteResponse->releaseDate,
                expirationDate: $pasteResponse->expirationDate,
                exposure: $pasteResponse->exposure,
                hash: $pasteResponse->hash,
                read: $pasteResponse->read,
            );
            $this->repository->update($paste);
        }
        return $pasteResponse;
    }

    public function getPublicPastes(Pager $pager): PastesResponse
    {
        return $this->repository->getPublicPaste($pager);
    }
}