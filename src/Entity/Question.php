<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text', nullable: true)]
    private $message;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'questions')]
    private $developer;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'questions')]
    private $mentor;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updatedAt;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: QuestionResponse::class)]
    private $questionResponses;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isRead;

    public function __construct()
    {
        $this->questionResponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getDeveloper(): ?User
    {
        return $this->developer;
    }

    public function setDeveloper(?User $developer): self
    {
        $this->developer = $developer;

        return $this;
    }

    public function getMentor(): ?User
    {
        return $this->mentor;
    }

    public function setMentor(?User $mentor): self
    {
        $this->mentor = $mentor;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, QuestionResponse>
     */
    public function getQuestionResponses(): Collection
    {
        return $this->questionResponses;
    }

    public function addQuestionResponse(QuestionResponse $questionResponse): self
    {
        if (!$this->questionResponses->contains($questionResponse)) {
            $this->questionResponses[] = $questionResponse;
            $questionResponse->setQuestion($this);
        }

        return $this;
    }

    public function removeQuestionResponse(QuestionResponse $questionResponse): self
    {
        if ($this->questionResponses->removeElement($questionResponse)) {
            // set the owning side to null (unless already changed)
            if ($questionResponse->getQuestion() === $this) {
                $questionResponse->setQuestion(null);
            }
        }

        return $this;
    }

    public function isIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(?bool $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }
}
