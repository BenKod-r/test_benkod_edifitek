<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\QuestionResponse;
use App\Entity\User;
use App\Form\FilterType;
use App\Form\MentorType;
use App\Form\QuestionResponseType;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use App\Repository\QuestionResponseRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    /**
     * @param UserRepository $userRepository
     * @param Request $request
     * @return Response
     */
    #[Route('/developer/mentor/list', name: 'app_developer_index', methods: ['GET', 'POST'])]
    public function indexDevelopers(UserRepository $userRepository, Request $request): Response
    {
        $mentors = $userRepository->findByRoles('ROLE_MENTOR');

        $user = new User();
        $form = $this->createForm(FilterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mentors = $form->get('stack')->getData()[0]->getName() === 'Tous' ?
                $userRepository->findByRolesAndAvailability('ROLE_MENTOR', ['isAvailable' => $form->get('isAvailable')->getData() === true ? 1 : 0]) :
                $userRepository->findByRolesAndFilters('ROLE_MENTOR', $form->get('isAvailable')->getData(), $form->get('stack')->getData()[0]->getName())
            ;
        }

        return $this->renderForm('forum/developer/index.html.twig', [
            'add_search' => false,
            'title' => 'liste des mentors',
            'form' => $form,
            'mentors' => $mentors,
        ]);
    }

    /**
     * @param User $developer
     * @param User $mentor
     * @param QuestionRepository $questionRepository
     * @param QuestionResponseRepository $questionResponseRepository
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param Question|null $questionSelected
     * @return Response
     */
    #[Route('/developer/{developer}/mentor/{mentor}/question/{questionSelected}/show', name: 'app_mentor_show', methods: ['GET', 'POST'])]
    public function show(User $developer,
                         User $mentor,
                         QuestionRepository $questionRepository,
                         QuestionResponseRepository $questionResponseRepository,
                         EntityManagerInterface $entityManager,
                         Request $request,
                         Question $questionSelected = null): Response
    {
        $questions = $questionRepository->findBy(['mentor' => $mentor->getId(), 'developer' => $this->getUser()->getId()], ['updatedAt' => 'DESC']);
        if ($questionSelected) {
            foreach ($questionResponseRepository->findBy(['question' => $questionSelected->getId(), 'recipient' => $this->getUser()->getId()]) as $questionResponse) {
                $questionResponse->setIsRead(true);
            }

            $entityManager->flush();
        }

        $question = new Question();
        $formQuestion = $this->createForm(QuestionType::class, $question);
        $formQuestion->handleRequest($request);

        if ($formQuestion->isSubmitted() && $formQuestion->isValid()) {
            $question
                ->setUpdatedAt(new \DateTime())
                ->setDeveloper($developer)
                ->setMentor($mentor)
                ->setIsRead(false)
            ;

            try {
                $questionRepository->add($question, true);
                $this->addFlash('success', 'Votre question a bien été envoyée');
            } catch(\Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenue lors de l\'envoi de votre question');
            }

            if ($questionSelected) {
                return $this->redirectToRoute('app_mentor_show', [
                    'developer' => $developer->getId(),
                    'mentor' => $mentor->getId(),
                    'questionSelected' => $questionSelected->getId(),
                ], Response::HTTP_SEE_OTHER);
            } else {
                return $this->redirectToRoute('app_mentor_show', [
                    'developer' => $developer->getId(),
                    'mentor' => $mentor->getId(),
                    'questionSelected' => 0
                ], Response::HTTP_SEE_OTHER);
            }

        }

        $questionResponse = new QuestionResponse();
        $formResponse = $this->createForm(QuestionResponseType::class, $questionResponse);
        $formResponse->handleRequest($request);

        if ($formResponse->isSubmitted() && $formResponse->isValid()) {
            $questionResponse
                ->setAuthor($this->getUser())
                ->setRecipient($mentor)
                ->setIsRead(false)
                ->setQuestion($questionSelected)
                ->setUpdatedAt(new \DateTime());

            try {
                $questionResponseRepository->add($questionResponse, true);
                $this->addFlash('success', 'Votre réponse a bien été envoyée');
            } catch(\Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenue lors de l\'envoi de votre réponse');
            }

            return $this->redirectToRoute('app_mentor_show', [
                'developer' => $developer->getId(),
                'mentor' => $mentor->getId(),
                'questionSelected' => $questionSelected->getId(),
                '_fragment' => 'message-' . count($questionResponseRepository->findBy(['question' => $questionSelected->getId()]))
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('forum/mentor/show.html.twig', [
            'formQuestion' => $formQuestion,
            'formResponse' => $formResponse,
            'add_search' => false,
            'title' => 'Vos échanges avec',
            'mentor' => $mentor,
            'developer' => $developer,
            'questions' => $questions,
            'questionSelected' => $questionSelected
        ]);
    }

    /**
     * @param QuestionRepository $questionRepository
     * @param QuestionResponseRepository $questionResponseRepository
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param Question|null $questionSelected
     * @return Response
     */
    #[Route('/mentor/{questionSelected}', name: 'app_mentor_index', methods: ['GET', 'POST'])]
    public function showForumMentor(QuestionRepository $questionRepository,
                                    QuestionResponseRepository $questionResponseRepository,
                                    UserRepository $userRepository,
                                    Request $request,
                                    EntityManagerInterface $entityManager,
                                    Question $questionSelected = null): Response
    {
        $mentor = $userRepository->findOneBy(['id' => $this->getUser()->getId()]);
        $questions = $questionRepository->findBy(['mentor' => $mentor->getId()], ['updatedAt' => 'DESC']);

        if ($questionSelected) {
            $questionSelected->setIsRead(true);
            foreach ($questionResponseRepository->findBy(['question' => $questionSelected->getId(), 'recipient' => $mentor->getId()]) as $questionResponse) {
                $questionResponse->setIsRead(true);
            }

            $entityManager->flush();
        }

        $formMentor = $this->createForm(MentorType::class, $mentor);
        $formMentor->handleRequest($request);

        if ($formMentor->isSubmitted() && $formMentor->isValid()) {
            try {
                $userRepository->add($mentor, true);
                $this->addFlash('success', 'Votre compte à bien été mis à jour');
            } catch(\Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenue lors de la mise à jour de votre compte');
            }

            $referer = $request->headers->get('referer');
            return new RedirectResponse($referer);

        }

        $questionResponse = new QuestionResponse();
        $formResponse = $this->createForm(QuestionResponseType::class, $questionResponse);
        $formResponse->handleRequest($request);

        if ($formResponse->isSubmitted() && $formResponse->isValid()) {
            $questionResponse
                ->setAuthor($mentor)
                ->setQuestion($questionSelected)
                ->setIsRead(false)
                ->setRecipient($questionResponse->getQuestion()->getDeveloper())
                ->setUpdatedAt(new \DateTime());

            try {
                $questionResponseRepository->add($questionResponse, true);
                $this->addFlash('success', 'Votre réponse a bien été envoyée');
            } catch(\Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenue lors de l\'envoi de votre réponse');
            }

            return $this->redirectToRoute('app_mentor_index', [
                'questionSelected' => $questionSelected->getId(),
                '_fragment' => 'message-' . count($questionResponseRepository->findBy(['question' => $questionSelected->getId()]))
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('forum/mentor/index.html.twig', [
            'formMentor' => $formMentor,
            'formResponse' => $formResponse,
            'add_search' => false,
            'title' => 'Dashboard',
            'mentor' => $mentor,
            'questions' => $questions,
            'questionSelected' => $questionSelected
        ]);
    }
}
