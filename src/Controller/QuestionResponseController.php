<?php

namespace App\Controller;

use App\Entity\QuestionResponse;
use App\Form\QuestionResponseType;
use App\Repository\QuestionResponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/question-response')]
class QuestionResponseController extends AbstractController
{
    /**
     * @param Request $request
     * @param QuestionResponse $questionResponse
     * @param QuestionResponseRepository $questionResponseRepository
     * @return Response
     */
    #[Route('/{id}', name: 'app_question_response_delete', methods: ['POST'])]
    public function delete(Request $request, QuestionResponse $questionResponse, QuestionResponseRepository $questionResponseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$questionResponse->getId(), $request->request->get('_token'))) {
            try {
                $questionResponseRepository->remove($questionResponse, true);
                $this->addFlash('success', 'Votre message a bien été supprimé');
            } catch(\Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenue lors de la suppression du message');
            }
        }

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }
}
