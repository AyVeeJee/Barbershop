<?php

namespace App\Entity;

use App\Repository\LostPasswordRepository;
use Doctrine\ORM\Mapping as ORM;
use Exception;

#[ORM\Table(options: ["collate" => "utf8_unicode_ci", "charset" => "utf8"])]
#[ORM\Entity(repositoryClass: LostPasswordRepository::class)]
#[ORM\Index(columns: ["token"], name: "token_lost_password_idx")]
#[ORM\HasLifecycleCallbacks]
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

    #[ORM\Column(type: 'datetime')]
    private $created_at = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column(length: 255)]
    private ?string $oldPassword = null;

    /**
     * @throws Exception
     */
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): self
    {
        $this->created_at = new \DateTime();

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }
}
