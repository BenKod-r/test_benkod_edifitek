<?php

namespace App\Controller;

use App\Form\ReverseType;
use App\Service\ReverseService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BonusController extends AbstractController
{
    #[Route('/bonus', name: 'bonus', methods: ['GET', 'POST'])]
    public function index(Request $request, ReverseService $reverseService, string $result = null): Response
    {
        $form = $this->createForm(ReverseType::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $result = $reverseService->reverse($form->get('text')->getData());
        }

        return $this->renderForm('bonus/index.html.twig', [
            'title' => 'Bonus reverse',
            'add_search' => false,
            'form' => $form,
            'result' => $result
        ]);
    }

}
