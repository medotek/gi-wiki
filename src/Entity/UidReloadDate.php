<?php

namespace App\Entity;

use App\Repository\UidReloadDateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UidReloadDateRepository::class)
 */
class UidReloadDate
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @ORM\OneToOne(targetEntity=UserUidCharacter::class, cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(nullable=false, name="uid", referencedColumnName="uid", onDelete="CASCADE"),
     *  @ORM\JoinColumn(nullable=false, name="user_id", referencedColumnName="user_id", onDelete="CASCADE")
     * })
     */
    private $uid;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastDate;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="uidReloadDates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

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

    public function getLastDate(): ?\DateTimeInterface
    {
        return $this->lastDate;
    }

    public function setLastDate(\DateTimeInterface $lastDate): self
    {
        $this->lastDate = $lastDate;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }
}
