<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(options: ["collate" => "utf8_unicode_ci", "charset" => "utf8"])]
#[ORM\Entity(repositoryClass: BookingRepository::class)]
#[ORM\Index(columns: ["appointer_id"], name: "appointer_booking_idx")]
#[ORM\Index(columns: ["service_id"], name: "service_booking__idx")]
#[ORM\Index(columns: ["employee_id"], name: "employee_booking_idx")]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $beginAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $endAt;

    #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist', 'refresh'], inversedBy: 'bookings')]
    private $appointer;

    #[ORM\ManyToOne(targetEntity: Service::class, cascade: ['persist', 'refresh'], inversedBy: 'bookings')]
    private $service;

    #[ORM\ManyToOne(targetEntity: Employee::class, cascade: ['persist', 'refresh'], inversedBy: 'bookings')]
    private $employee;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->appointer;
    }

    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getBeginAt(): ?\DateTimeInterface
    {
        return $this->beginAt;
    }

    public function setBeginAt(?\DateTimeInterface $beginAt): self
    {
        $this->beginAt = $beginAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeInterface $endAt = null): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getAppointer(): ?User
    {
        return $this->appointer;
    }

    public function setAppointer(?User $appointer): self
    {
        $this->appointer = $appointer;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): self
    {
        $this->employee = $employee;

        return $this;
    }
}
