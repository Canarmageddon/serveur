<?php

namespace App\Entity;

use App\Repository\StepRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StepRepository::class)]
class Step
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'steps')]
    private ?Location $location;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?DateTimeImmutable $creationDate;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'steps')]
    private ?User $creator;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $description;

    #[ORM\ManyToOne(targetEntity: Document::class, inversedBy: 'steps')]
    private ?Document $documents;

    #[ORM\ManyToOne(targetEntity: Itinerary::class, inversedBy: 'steps')]
    private ?Itinerary $itinerary;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getCreationDate(): ?DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function setCreationDate(DateTimeImmutable $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDocuments(): ?Document
    {
        return $this->documents;
    }

    public function setDocuments(?Document $documents): self
    {
        $this->documents = $documents;

        return $this;
    }

    public function getItinerary(): ?Itinerary
    {
        return $this->itinerary;
    }

    public function setItinerary(?Itinerary $itinerary): self
    {
        $this->itinerary = $itinerary;

        return $this;
    }
}
