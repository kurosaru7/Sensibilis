<?php

namespace App\Entity;

use App\Repository\DataUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DataUserRepository::class)
 */
class DataUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Choice;

    /**
     * @ORM\Column(type="integer")
     */
    private $question;

    /**
     * @ORM\Column(type="integer")
     */
    private $Uoption;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChoice(): ?bool
    {
        return $this->Choice;
    }

    public function setChoice(bool $Choice): self
    {
        $this->Choice = $Choice;

        return $this;
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

    public function getUoption(): ?int
    {
        return $this->Uoption;
    }

    public function setUoption(int $Uoption): self
    {
        $this->Uoption = $Uoption;

        return $this;
    }
}
