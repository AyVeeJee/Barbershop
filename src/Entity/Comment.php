<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Table(name: '`comments`')]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $content;

    #[ORM\Column(type: 'datetime')]
    private $created_at;

    #[Ignore]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private $user_comment;

    #[Ignore]
    #[ORM\ManyToOne(targetEntity: Employee::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private $employee;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): self
    {
        if (isset($this->created_at_fixtures)) {
            $this->created_at = $this->created_at_fixtures;
        } else {
            $this->created_at = new \DateTime();
        }

        return $this;
    }

    #[Ignore]
    public function setCreatedAtForFixtures($created_at): self
    {
        $this->created_at_fixtures = $created_at;

        return $this;
    }

    public function getUserComment(): ?User
    {
        return $this->user_comment;
    }

    public function setUserComment(?User $user_comment): self
    {
        $this->user_comment = $user_comment;

        return $this;
    }

    public function getEmployee()
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): self
    {
        $this->employee = $employee;

        return $this;
    }
}
