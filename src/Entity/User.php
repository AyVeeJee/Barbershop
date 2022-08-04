<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 60, nullable: false)]
    private $first_name;

    #[ORM\Column(type: 'string', length: 60, nullable: false)]
    private $last_name;

    #[ORM\Column(type: 'string', length: 180, unique: true, nullable: false)]
    private $email;

    #[ORM\Column(type: 'string', nullable: false)]
    private $password;

    #[ORM\Column(type: 'string', nullable: false)]
    private $phone;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\OneToMany(mappedBy: 'user_comment', targetEntity: Comment::class, fetch: 'EAGER')]
    private $comments;

    #[ORM\OneToMany(mappedBy: 'appointer', targetEntity: Booking::class)]
    private $bookings;

    private $terms;

    #[ORM\OneToOne(mappedBy: 'userpass', cascade: ['persist', 'remove'])]
    private ?LostPassword $lostPassword = null;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    public function __toString(): string
    {
        return sprintf('%s %s', $this->first_name, $this->last_name);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUserComment($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUserComment() === $this) {
                $comment->setUserComment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBookings(Booking $bookings): self
    {
        if (!$this->bookings->contains($bookings)) {
            $this->bookings[] = $bookings;
            $bookings->setAppointer($this);
        }

        return $this;
    }

    public function removeBookings(Booking $bookings): self
    {
        if ($this->bookings->removeElement($bookings)) {
            // set the owning side to null (unless already changed)
            if ($bookings->getAppointer() === $this) {
                $bookings->setAppointer(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTerms(): bool
    {
        return $this->terms;
    }

    /**
     * @param mixed $terms
     */
    public function setTerms($terms): void
    {
        $this->terms = $terms;
    }

    public function getLostPassword(): ?LostPassword
    {
        return $this->lostPassword;
    }

    public function setLostPassword(?LostPassword $lostPassword): self
    {
        // unset the owning side of the relation if necessary
        if ($lostPassword === null && $this->lostPassword !== null) {
            $this->lostPassword->setUserpass(null);
        }

        // set the owning side of the relation if necessary
        if ($lostPassword !== null && $lostPassword->getUserpass() !== $this) {
            $lostPassword->setUserpass($this);
        }

        $this->lostPassword = $lostPassword;

        return $this;
    }
}
