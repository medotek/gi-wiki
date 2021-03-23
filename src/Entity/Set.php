<?php

namespace App\Entity;

use App\Repository\SetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SetRepository::class)
 * @ORM\Table(name="`set`")
 */
class Set
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
    private $stat;

    /**
     * @ORM\ManyToMany(targetEntity=Artifact::class)
     */
    private $artifacts;

    /**
     * @ORM\ManyToOne(targetEntity=Build::class, inversedBy="sets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $build;

    public function __construct()
    {
        $this->artifacts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStat(): ?string
    {
        return $this->stat;
    }

    public function setStat(string $stat): self
    {
        $this->stat = $stat;

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

    public function getBuild(): ?Build
    {
        return $this->build;
    }

    public function setBuild(?Build $build): self
    {
        $this->build = $build;

        return $this;
    }

    public function __toString(): ?string
    {
        $str = "";

        try {
            foreach ($this->artifacts->getIterator() as $artifact) {
                $str += (string)$artifact . ", ";
            }
        } catch (\Exception $e) {
            return null;
            throw($e);
        }

        return $str;
    }
}
