<?php

namespace App\Presenter\Controllers\Api;
use App\Domain\Services\PasteService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class GetPasteApiAction
{
    public function __construct(
        private SerializerInterface $serializer,
    )
    {
    }

    #[Route(path: '/api/v1/paste/{hash}', name: '/api/v1/paste', methods: ['GET'])]
    public function form(int $hash, PasteService $services): Response
    {
        $paste = $services->getPaste($hash);
        $data = $this->serializer->serialize($paste, JsonEncoder::FORMAT);
        return new JsonResponse(data: $data, status: Response::HTTP_OK, json: true);
    }
}