<?php

namespace App\Presenter\Controllers;
use App\Domain\Services\PasteServices;
use App\Presenter\Entities\Paste;
use App\Presenter\Entities\PasteType;
use App\Presenter\Mapper\PasteMapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CreatePasteAction extends AbstractController
{
    #[Route(path: 'paste/create', name: 'create_paste', methods: ['GET', 'POST'])]
    public function form(Request $request, PasteServices $services): Response
    {
        $paste = new Paste();
        $form = $this->createForm(PasteType::class, $paste);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pasteResponse = $services->createPaste(PasteMapper::pasteToPasteRequest($paste));
            $this->addFlash('posted_pasta','Your paste has been cooked!');
            return $this->redirectToRoute('show_paste', ['hash' => $pasteResponse->hash]);
        }
        return $this->render('paste/form.html.twig', [
            'form' => $form,
        ]);
    }
}