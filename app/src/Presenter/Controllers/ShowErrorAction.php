<?php

namespace App\Presenter\Controllers;
use App\Domain\Entities\Pager;
use App\Domain\Exceptions\PasteNotFoundException;
use App\Domain\Services\PasteService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Throwable;

final class ShowErrorAction extends AbstractController
{
    public function __construct(private readonly PasteService $pasteService)
    {
    }
    public function show(Throwable $exception, ?DebugLoggerInterface $logger): Response
    {
        return match (get_class($exception)) {
            PasteNotFoundException::class => $this->pasteNotFoundPage($exception),
            default => throw $exception,
        };
    }

    private function pasteNotFoundPage(Exception $exception): Response
    {
        $pastes = $this->pasteService->getPublicPastes(new Pager());
        return $this->render('paste/error.html.twig', [
            'pastes' => $pastes,
        ], new Response($exception->getMessage(), Response::HTTP_NOT_FOUND));
    }
}