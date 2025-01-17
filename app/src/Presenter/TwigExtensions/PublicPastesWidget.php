<?php

namespace App\Presenter\TwigExtensions;

use App\Domain\Services\PasteService;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class PublicPastesWidget extends AbstractExtension
{
    public function __construct(
        private readonly Environment $twig,
        private readonly PasteService $pasteService,
    )
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('public_pastes_widget', [$this, 'renderPublicPastesWidget'], ['is_safe' => ['html']]),
        ];
    }

    public function renderPublicPastesWidget(): string
    {
        $pastes = $this->pasteService->getPublicPastes();
        return $this->twig->render('paste/public_pastes_widget.html.twig', [
            'pastes' => $pastes,
        ]);
    }
}