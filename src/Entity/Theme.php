<?php

namespace App\Entity;

use App\Repository\ThemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ThemeRepository::class)
 */
class Theme
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    // /**
    //  * @ORM\Column(type="string", length=255)
    //  */
    // private $slug;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="theme", cascade={"remove"})
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity=Make::class, mappedBy="theme")
     */
    private $makes;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;


    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->makes = new ArrayCollection();
    }

    public function __toString() {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setId(Int $id)
    {
        $this-> id =$id;
    }

    // public function getSlug(): ?string
    // {
    //     return $this->slug;
    // }

    // public function setSlug(string $slug): self
    // {
    //     $this->slug = $slug;

    //     return $this;
    // }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setTheme($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getTheme() === $this) {
                $question->setTheme(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Make[]
     */
    public function getMakes(): Collection
    {
        return $this->makes;
    }

    public function addMake(Make $make): self
    {
        if (!$this->makes->contains($make)) {
            $this->makes[] = $make;
            $make->setTheme($this);
        }

        return $this;
    }

    public function removeMake(Make $make): self
    {
        if ($this->makes->removeElement($make)) {
            // set the owning side to null (unless already changed)
            if ($make->getTheme() === $this) {
                $make->setTheme(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
