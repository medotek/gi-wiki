<?php

namespace App\Entity;

use App\Repository\CommunityBuildRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommunityBuildRepository::class)
 */
class CommunityBuild
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $tags = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="builds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToOne(targetEntity=Build::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false, name="build_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $build;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="buildsVotes")
     */
    private $votesBuild;

    public function __construct(int $votes, DateTime $date) {
        $this->votes = $votes>0 ? $votes : 0;
        $this->creation_date = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * To unserialize ->  use this method: unserialize($tags)
     *
     * @return array|null
     */
    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(?array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }


    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
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

    public function getVotesBuild(): ?User
    {
        return $this->votesBuild;
    }

    public function setVotesBuild(?User $votesBuild): self
    {
        $this->votesBuild = $votesBuild;

        return $this;
    }

}
