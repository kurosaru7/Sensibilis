<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $titre;

    /**
     * @ORM\ManyToOne(targetEntity=Theme::class, inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $theme;

    /**
     * @ORM\OneToMany(targetEntity=Option::class, mappedBy="question", orphanRemoval=true)
     */
    private $options;

    /**
     * @ORM\Column(type="text")
     */
    private $answer;

    /**
     * @ORM\Column(type="integer")
     */
    private $indexQ;


    public function __construct()
    {
        $this->options = new ArrayCollection();
    }

    public function __toString() {
        return $this->titre;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(String $answer): ?string
    {
        $this->answer = $answer;
        return $this;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getTheme(): ?Theme
    {
        return $this->theme;
    }





    public function setTheme(?Theme $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function setId(Int $id)
    {
        $this-> id =$id;
    }

    /**
     * @return Collection|Option[]
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
            $option->setQuestion($this);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->removeElement($option)) {
            // set the owning side to null (unless already changed)
            if ($option->getQuestion() === $this) {
                $option->setQuestion(null);
            }
        }

        return $this;
    }

    public function getIndexQ(): ?int
    {
        return $this->indexQ;
    }

    public function setIndexQ(int $indexQ): self
    {
        $this->indexQ = $indexQ;

        return $this;
    }

}
