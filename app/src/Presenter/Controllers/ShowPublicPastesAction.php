<?php

namespace App\Presenter\Controllers;

use App\Data\Repositories\PasteRepository;
use App\Domain\Services\PasteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/', name: 'public_pastes', methods: ['GET'])]
final class ShowPublicPastesAction extends AbstractController
{
    public function __construct(private readonly PasteService $service)
    {
    }

    public function __invoke(): Response
    {
        $pastes = $this->service->getPublicPastes();
        return $this->render('paste/show_public.html.twig', [
            'pastes' => $pastes,
        ]);
    }
}