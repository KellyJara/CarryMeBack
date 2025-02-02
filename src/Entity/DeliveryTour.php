<?php

namespace App\Entity;

use App\Repository\DeliveryTourRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeliveryTourRepository::class)]
class DeliveryTour
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $Origin = null;

    #[ORM\Column(length: 100)]
    private ?string $Destination = null;

    #[ORM\Column(length: 100)]
    private ?string $MeansOfTransport = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $CreatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $UpdatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'deliveryTours')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrigin(): ?string
    {
        return $this->Origin;
    }

    public function setOrigin(string $Origin): static
    {
        $this->Origin = $Origin;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->Destination;
    }

    public function setDestination(string $Destination): static
    {
        $this->Destination = $Destination;

        return $this;
    }

    public function getMeansOfTransport(): ?string
    {
        return $this->MeansOfTransport;
    }

    public function setMeansOfTransport(string $MeansOfTransport): static
    {
        $this->MeansOfTransport = $MeansOfTransport;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeImmutable $CreatedAt): static
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->UpdatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $UpdatedAt): static
    {
        $this->UpdatedAt = $UpdatedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }

   /* public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }*/
}
