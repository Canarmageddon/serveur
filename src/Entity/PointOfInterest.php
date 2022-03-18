<?php

namespace App\Entity;

use App\Repository\PointOfInterestRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PointOfInterestRepository::class)]
class PointOfInterest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['pointOfInterest'])]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'pointOfInterests')]
    #[Groups(['pointOfInterest'])]
    private ?Location $location;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'pointOfInterests')]
    #[Groups(['pointOfInterest'])]
    private ?User $creator;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['pointOfInterest'])]
    private DateTimeImmutable $creationDate;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['pointOfInterest'])]
    private ?string $description;

    #[ORM\OneToMany(mappedBy: 'pointOfInterest', targetEntity: Document::class)]
    #[Groups(['pointOfInterest'])]
    private Collection $documents;

    #[ORM\ManyToOne(targetEntity: Itinerary::class, inversedBy: 'pointsOfInterest')]
    #[Groups(['pointOfInterest'])]
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

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getCreationDate(): ?DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function __construct(){
        $this->creationDate = new DateTimeImmutable('now');
        $this->documents = new ArrayCollection();
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

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setPointOfInterest($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getPointOfInterest() === $this) {
                $document->setPointOfInterest(null);
            }
        }

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
