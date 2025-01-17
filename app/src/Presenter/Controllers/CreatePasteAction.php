<?php

namespace App\Presenter\Controllers;
use App\Domain\Services\PasteService;
use App\Presenter\Entities\PagerRequest;
use App\Presenter\Entities\Paste;
use App\Presenter\Entities\PasteType;
use App\Presenter\Mappers\MapperPager;
use App\Presenter\Mappers\MapperPaste;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

class CreatePasteAction extends AbstractController
{
    #[Route(path: '/', name: 'create_paste', methods: ['GET', 'POST'])]
    public function form(Request $request, #[MapQueryString] PagerRequest $pager, PasteService $services): Response
    {
        $paste = new Paste();
        $form = $this->createForm(PasteType::class, $paste);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pasteResponse = $services->createPaste(MapperPaste::pasteToPasteRequest($paste));
            $this->addFlash('posted_pasta','Your paste has been cooked!');
            return $this->redirectToRoute('show_paste', ['hash' => $pasteResponse->hash]);
        }
        $pastes = $services->getPublicPastes(MapperPager::pagerRequestToPager($pager));
        return $this->render('paste/form.html.twig', [
            'form' => $form,
            'pastes' => $pastes,
        ]);
    }
}