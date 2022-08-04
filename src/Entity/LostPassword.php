<?php

namespace App\Entity;

use App\Repository\LostPasswordRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LostPasswordRepository::class)]
class LostPassword
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $token = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\OneToOne(inversedBy: 'lostPassword', cascade: ['persist', 'remove'])]
    private ?User $userpass = null;

    public function __construct()
    {
        $this->token = sha1(random_bytes(12));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
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

    public function getUserpass(): ?User
    {
        return $this->userpass;
    }

    public function setUserpass(?User $userpass): self
    {
        $this->userpass = $userpass;

        return $this;
    }
}
