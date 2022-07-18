<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/question')]
class QuestionController extends AbstractController
{
    /**
     * @param Request $request
     * @param Question $question
     * @param QuestionRepository $questionRepository
     * @return Response
     */
    #[Route('/{id}', name: 'app_question_delete', methods: ['POST'])]
    public function delete(Request $request, Question $question, QuestionRepository $questionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            try {
                $questionRepository->remove($question, true);
                $this->addFlash('success', 'Votre question a bien été supprimée');
            } catch(\Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenue lors de la suppression de la question');
            }
        }

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }
}
