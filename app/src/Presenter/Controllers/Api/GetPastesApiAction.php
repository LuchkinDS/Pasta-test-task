<?php

namespace App\Presenter\Controllers\Api;
use App\Domain\Services\PasteService;
use App\Presenter\Entities\PagerRequest;
use App\Presenter\Mappers\MapperPager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class GetPastesApiAction
{
    public function __construct(
        private SerializerInterface $serializer,
    )
    {
    }

    #[Route(path: '/api/v1/paste', name: '/api/v1/paste', methods: ['GET'])]
    public function form(#[MapQueryString] PagerRequest $pager, PasteService $services): Response
    {
        $pastes = $services->getPublicPastes(MapperPager::pagerRequestToPager($pager));
        $data = $this->serializer->serialize($pastes, JsonEncoder::FORMAT);
        return new JsonResponse(data: $data, status: Response::HTTP_OK, json: true);
    }
}