<?php

namespace App\Entity;

use App\Repository\UserUidCharacterRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserUidCharacterRepository::class)
 */
class UserUidCharacter
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $uid;


    /**
     * @ORM\Column(type="json")
     */
    private $uidCharacterInfo = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $reliquariesStatPos = [];

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="characters")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getUidCharacterInfo(): ?array
    {
        return $this->uidCharacterInfo;
    }

    public function setUidCharacterInfo(array $uidCharacterInfo): self
    {
        $this->uidCharacterInfo = $uidCharacterInfo;

        return $this;
    }

    public function getReliquariesStatPos(): ?array
    {
        return $this->reliquariesStatPos;
    }

    public function setReliquariesStatPos(?array $reliquariesStatPos): self
    {
        $this->reliquariesStatPos = $reliquariesStatPos;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
