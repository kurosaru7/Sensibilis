<?php

namespace App\Entity;

use App\Repository\ErrorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ErrorRepository::class)
 */
class Error
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $question;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $answers;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?int
    {
        return $this->question;
    }

    public function setQuestion(int $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswers(): ?string
    {
        return $this->answers;
    }

    public function setAnswers(string $answers): self
    {
        $this->answers = $answers;

        return $this;
    }
}
