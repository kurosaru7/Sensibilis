<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass=App\Repository\UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(
 * fields={"username"},
 * message="Le nom d'utilisateur que vous avez indiqué est déjà utilisé !"
 * )
 */
class User implements UserInterface
{

    const ROLE_USER = 'ROLE_USER';
    const ROLE_EDITOR = 'ROLE_EDITOR';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    const PREF = 'PREF';
    const DDPP = 'DDPP';
    const DDTM = 'DDTM';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     * 
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=8, minMessage="Votre mot de passe doit faire au moins 8 caractères.")
     */
    private $password;
    
    /**
     * @Assert\EqualTo(propertyPath="password", message="Vous n'avez pas indiqué le même mot de passe.")
     */
    private $confirmPassword;

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword)
    {
        $this->confirmPassword = $confirmPassword;
    }

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $origine;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity=Make::class, mappedBy="user", orphanRemoval=true)
     */
    private $makes;

    public function __construct()
    {
        $this->makes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function setId(Int $id)
    {
        $this-> id =$id;
    }

    public function getOrigine(): ?string
    {
        return $this->origine;
    }

    public function setOrigine(string $origine): self
    {
        $this->origine = $origine;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }


    public function eraseCredentials()
    {
        // $this->password = null;
        // $this->username = null;
    }

    public function getSalt()
    {
        
    }

    public function getRoles()
    {
        return [$this->role];
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
            $make->setUser($this);
        }

        return $this;
    }

    public function removeMake(Make $make): self
    {
        if ($this->makes->removeElement($make)) {
            // set the owning side to null (unless already changed)
            if ($make->getUser() === $this) {
                $make->setUser(null);
            }
        }

        return $this;
    }
}
