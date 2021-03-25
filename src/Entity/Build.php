<?php

namespace App\Entity;

use App\Repository\BuildRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BuildRepository::class)
 */
class Build
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

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Character::class, inversedBy="builds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $gameCharacter;

    /**
     * @ORM\ManyToMany(targetEntity=Weapon::class)
     */
    private $weapons;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $buildCategory;

    private array $tags;

    /**
     * @ORM\ManyToMany(targetEntity=Artifact::class)
     */
    private $artifacts;

    public function __construct()
    {
        $this->weapons = new ArrayCollection();
        $this->sets = new ArrayCollection();
        $this->artifacts = new ArrayCollection();
    }

    public function getTags(): array
    {
        return $this->tags;
    }


    public function setTags(array $tags): self
    {
        $this->tags = $tags;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getGameCharacter(): ?Character
    {
        return $this->gameCharacter;
    }

    public function setGameCharacter(?Character $gameCharacter): self
    {
        $this->gameCharacter = $gameCharacter;

        return $this;
    }

    /**
     * @return Collection|Weapon[]
     */
    public function getWeapons(): Collection
    {
        return $this->weapons;
    }

    public function addWeapon(Weapon $weapon): self
    {
        if (!$this->weapons->contains($weapon)) {
            $this->weapons[] = $weapon;
        }

        return $this;
    }

    public function removeWeapon(Weapon $weapon): self
    {
        $this->weapons->removeElement($weapon);

        return $this;
    }

    /**
     * @return Collection|Set[]
     */
    public function getSets(): Collection
    {
        return $this->sets;
    }

    public function addSet(Set $set): self
    {
        if (!$this->sets->contains($set)) {
            $this->sets[] = $set;
            $set->setBuild($this);
        }

        return $this;
    }

    public function removeSet(Set $set): self
    {
        if ($this->sets->removeElement($set)) {
            // set the owning side to null (unless already changed)
            if ($set->getBuild() === $this) {
                $set->setBuild(null);
            }
        }

        return $this;
    }

    public function getBuildCategory(): ?string
    {
        return $this->buildCategory;
    }

    public function setBuildCategory(string $buildCategory): self
    {
        $this->buildCategory = $buildCategory;

        return $this;
    }

    /**
     * @return Collection|Artifact[]
     */
    public function getArtifacts(): Collection
    {
        return $this->artifacts;
    }

    public function addArtifact(Artifact $artifact): self
    {
        if (!$this->artifacts->contains($artifact)) {
            $this->artifacts[] = $artifact;
        }

        return $this;
    }

    public function removeArtifact(Artifact $artifact): self
    {
        $this->artifacts->removeElement($artifact);

        return $this;
    }
}
