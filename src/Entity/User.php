<?php

namespace App\Entity;

use App\Repository\UserRepository;
use ContainerOcq7WVR\getSecurity_EncoderFactory_GenericService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;
use function Couchbase\defaultEncoder;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    private $encoderFactory;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $creationDate;

    /**
     * @ORM\OneToMany(targetEntity=CommunityBuild::class, mappedBy="author", orphanRemoval=true)
     */
    private $builds;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=CommunityBuild::class, mappedBy="votes")
     */
    private $votes;

    /**
     * @ORM\OneToMany(targetEntity=CommunityBuild::class, mappedBy="votesBuild")
     */
    private $buildsVotes;

    public function __construct()
    {
        $this->builds = new ArrayCollection();
        $this->votes = new ArrayCollection();
        $this->buildsVotes = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * @return Collection|CommunityBuild[]
     */
    public function getBuilds(): Collection
    {
        return $this->builds;
    }

    public function addBuild(CommunityBuild $build): self
    {
        if (!$this->builds->contains($build)) {
            $this->builds[] = $build;
            $build->setAuthor($this);
        }

        return $this;
    }

    public function removeBuild(CommunityBuild $build): self
    {
        if ($this->builds->removeElement($build)) {
            // set the owning side to null (unless already changed)
            if ($build->getAuthor() === $this) {
                $build->setAuthor(null);
            }
        }

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    //Méthodes d'Authentification

    public function getUsername()
    {
        return $this->email;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return Collection|CommunityBuild[]
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(CommunityBuild $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes[] = $vote;
            $vote->setVotes($this);
        }

        return $this;
    }

    public function removeVote(CommunityBuild $vote): self
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getVotes() === $this) {
                $vote->setVotes(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CommunityBuild[]
     */
    public function getBuildsVotes(): Collection
    {
        return $this->buildsVotes;
    }

    public function addBuildsVote(CommunityBuild $buildsVote): self
    {
        if (!$this->buildsVotes->contains($buildsVote)) {
            $this->buildsVotes[] = $buildsVote;
            $buildsVote->setVotesBuild($this);
        }

        return $this;
    }

    public function removeBuildsVote(CommunityBuild $buildsVote): self
    {
        if ($this->buildsVotes->removeElement($buildsVote)) {
            // set the owning side to null (unless already changed)
            if ($buildsVote->getVotesBuild() === $this) {
                $buildsVote->setVotesBuild(null);
            }
        }

        return $this;
    }
}
