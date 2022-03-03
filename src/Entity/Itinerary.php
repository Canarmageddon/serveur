<?php

namespace App\Entity;

use App\Repository\ItineraryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: ItineraryRepository::class)]
class Itinerary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\OneToMany(mappedBy: 'itinerary', targetEntity: PointOfInterest::class)]
    private ArrayCollection $pointsOfInterest;

    #[ORM\OneToMany(mappedBy: 'itinerary', targetEntity: Step::class)]
    private ArrayCollection $steps;

    #[ORM\OneToMany(mappedBy: 'itinerary', targetEntity: travel::class)]
    private ArrayCollection $travels;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $description;

    #[ORM\OneToMany(mappedBy: 'itinerary', targetEntity: Document::class)]
    private ArrayCollection $documents;

    #[ORM\ManyToOne(targetEntity: Trip::class, inversedBy: 'itineraries')]
    private ?Trip $trip;

    #[Pure] public function __construct()
    {
        $this->pointsOfInterest = new ArrayCollection();
        $this->steps = new ArrayCollection();
        $this->travels = new ArrayCollection();
        $this->documents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, PointOfInterest>
     */
    public function getPointsOfInterest(): Collection
    {
        return $this->pointsOfInterest;
    }

    public function addPointsOfInterest(PointOfInterest $pointsOfInterest): self
    {
        if (!$this->pointsOfInterest->contains($pointsOfInterest)) {
            $this->pointsOfInterest[] = $pointsOfInterest;
            $pointsOfInterest->setItinerary($this);
        }

        return $this;
    }

    public function removePointsOfInterest(PointOfInterest $pointsOfInterest): self
    {
        if ($this->pointsOfInterest->removeElement($pointsOfInterest)) {
            // set the owning side to null (unless already changed)
            if ($pointsOfInterest->getItinerary() === $this) {
                $pointsOfInterest->setItinerary(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Step>
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(Step $step): self
    {
        if (!$this->steps->contains($step)) {
            $this->steps[] = $step;
            $step->setItinerary($this);
        }

        return $this;
    }

    public function removeStep(Step $step): self
    {
        if ($this->steps->removeElement($step)) {
            // set the owning side to null (unless already changed)
            if ($step->getItinerary() === $this) {
                $step->setItinerary(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, travel>
     */
    public function getTravels(): Collection
    {
        return $this->travels;
    }

    public function addTravel(travel $travel): self
    {
        if (!$this->travels->contains($travel)) {
            $this->travels[] = $travel;
            $travel->setItinerary($this);
        }

        return $this;
    }

    public function removeTravel(travel $travel): self
    {
        if ($this->travels->removeElement($travel)) {
            // set the owning side to null (unless already changed)
            if ($travel->getItinerary() === $this) {
                $travel->setItinerary(null);
            }
        }

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
            $document->setItinerary($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getItinerary() === $this) {
                $document->setItinerary(null);
            }
        }

        return $this;
    }

    public function getTrip(): ?Trip
    {
        return $this->trip;
    }

    public function setTrip(?Trip $trip): self
    {
        $this->trip = $trip;

        return $this;
    }
}
