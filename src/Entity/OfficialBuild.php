<?php

namespace App\Entity;

use App\Repository\OfficialBuildRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OfficialBuildRepository::class)
 */
class OfficialBuild
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Build::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $build;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBuild(): ?Build
    {
        return $this->build;
    }

    public function setBuild(Build $build): self
    {
        $this->build = $build;

        return $this;
    }
}
