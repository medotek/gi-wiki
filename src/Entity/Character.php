<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CharacterRepository::class)
 * @ORM\Table(name="`character`")
 */
class Character
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
     * @ORM\Column(type="string", length=255)
     */
    private $element;

    /**
     * @ORM\Column(type="integer")
     */
    private $rarity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $weaponType;

    /**
     * @ORM\OneToMany(targetEntity=Build::class, mappedBy="gameCharacter", orphanRemoval=true)
     */
    private $builds;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    public function __construct()
    {
        $this->builds = new ArrayCollection();
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

    public function getElement(): ?string
    {
        return $this->element;
    }

    public function setElement(string $element): self
    {
        $this->element = $element;

        return $this;
    }

    public function getRarity(): ?int
    {
        return $this->rarity;
    }

    public function setRarity(int $rarity): self
    {
        $this->rarity = $rarity;

        return $this;
    }

    public function getWeaponType(): ?string
    {
        return $this->weaponType;
    }

    public function setWeaponType(string $weaponType): self
    {
        $this->weaponType = $weaponType;

        return $this;
    }

    /**
     * @return Collection|Build[]
     */
    public function getBuilds(): Collection
    {
        return $this->builds;
    }

    public function addBuild(Build $build): self
    {
        if (!$this->builds->contains($build)) {
            $this->builds[] = $build;
            $build->setGameCharacter($this);
        }

        return $this;
    }

    public function removeBuild(Build $build): self
    {
        if ($this->builds->removeElement($build)) {
            // set the owning side to null (unless already changed)
            if ($build->getGameCharacter() === $this) {
                $build->setGameCharacter(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
