<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    private $lastName;

    #[ORM\Column(type: 'string', length: 255)]
    private $firstName;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $createdAt;

    #[ORM\ManyToMany(targetEntity: Stack::class, inversedBy: 'users')]
    private $stack;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isAvailable;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $profileImage;

    #[ORM\OneToMany(mappedBy: 'developer', targetEntity: Question::class)]
    private $questions;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: QuestionResponse::class)]
    private $questionResponses;

    public function __construct()
    {
        $this->stack = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->questionResponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Stack>
     */
    public function getStack(): Collection
    {
        return $this->stack;
    }

    public function addStack(Stack $stack): self
    {
        if (!$this->stack->contains($stack)) {
            $this->stack[] = $stack;
        }

        return $this;
    }

    public function removeStack(Stack $stack): self
    {
        $this->stack->removeElement($stack);

        return $this;
    }

    public function isIsAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(?bool $isAvailable): self
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    public function getProfileImage(): ?string
    {
        return $this->profileImage;
    }

    public function setProfileImage(?string $profileImage): self
    {
        $this->profileImage = $profileImage;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setDeveloper($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getDeveloper() === $this) {
                $question->setDeveloper(null);
            }
        }

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
            $questionResponse->setAuthor($this);
        }

        return $this;
    }

    public function removeQuestionResponse(QuestionResponse $questionResponse): self
    {
        if ($this->questionResponses->removeElement($questionResponse)) {
            // set the owning side to null (unless already changed)
            if ($questionResponse->getAuthor() === $this) {
                $questionResponse->setAuthor(null);
            }
        }

        return $this;
    }
}
