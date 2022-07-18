<?php

namespace App\Service;

use App\Repository\QuestionRepository;
use App\Repository\QuestionResponseRepository;

class QuestionResponseService
{
    /** @var QuestionResponseRepository  */
    private QuestionResponseRepository $questionResponseRepository;
    /** @var QuestionRepository  */
    private QuestionRepository $questionRepository;

    public function __construct(QuestionResponseRepository $questionResponseRepository, QuestionRepository $questionRepository)
    {
        $this->questionResponseRepository = $questionResponseRepository;
        $this->questionRepository = $questionRepository;
    }

    /**
     * @param $recipientId
     * @param $questionId
     * @return int
     */
    public function countQuestionResponseNotRead($recipientId, $questionId): int
    {
        $questionResponseNotRead = $this->questionResponseRepository->findBy(['recipient' => $recipientId, 'question' => $questionId, 'isRead' => false]);

        return count($questionResponseNotRead);
    }

    public function countQuestionNotRead($mentorId): int
    {
        $questionNotRead = $this->questionRepository->findBy(['mentor' => $mentorId, 'isRead' => false]);

        return count($questionNotRead);
    }
}
