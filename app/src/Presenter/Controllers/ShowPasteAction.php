<?php

namespace App\Presenter\Controllers;
use App\Domain\Services\PasteServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ShowPasteAction extends AbstractController
{
    public function __construct(private readonly PasteServices $pasteServices)
    {
    }

    #[Route(path: '/{hash}', name: 'show_paste', methods: ['GET'])]
    public function __invoke(string $hash): Response
    {
        $paste = $this->pasteServices->getPaste($hash);
        return $this->render('paste/show.html.twig', [
            'paste' => $paste,
        ]);
    }
}