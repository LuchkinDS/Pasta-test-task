<?php

namespace App\Presenter\Controllers\Api;
use App\Domain\Entities\PasteRequest;
use App\Domain\Services\PasteService;
use App\Presenter\Entities\PasteForm;
use App\Presenter\Mappers\MapperPaste;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class CreatePasteApiAction
{
    public function __construct(
        private PasteService $pasteService,
        private UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    #[Route(path: '/api/v1/paste', name: '/api/v1/paste', methods: ['POST'])]
    public function form(#[MapRequestPayload] PasteForm $request): Response
    {
        $paste = $this->pasteService->createPaste(MapperPaste::pasteFormToPasteRequest($request));
        $url = $this->urlGenerator->generate('api/v1/book', ['id' => $paste->id], referenceType: UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse(
            status: Response::HTTP_CREATED,
            headers: ['Link' => $url]
        );
    }
}