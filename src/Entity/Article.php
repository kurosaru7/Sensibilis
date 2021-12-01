<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Theme::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $theme;

    /**
     * @ORM\Column(type="text")
     */
    private $video_links;

    /**
     * @ORM\Column(type="text")
     */
    private $other_links;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    public function setTheme(Theme $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function getVideoLinks(): ?string
    {
        return $this->video_links;
    }

    public function setVideoLinks(?string $video_links): self
    {
        $this->video_links = $video_links;

        return $this;
    }

    public function getOtherLinks(): ?string
    {
        return $this->other_links;
    }

    public function setOtherLinks(string $other_links): self
    {
        $this->other_links = $other_links;

        return $this;
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
}
