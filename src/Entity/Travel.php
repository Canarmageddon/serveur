<?php

namespace App\Entity;

use App\Repository\TravelRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TravelRepository::class)]
class Travel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['travel'])]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'starts')]
    #[Groups(['travel'])]
    private ?Location $start;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'ends')]
    #[Groups(['travel'])]
    private ?Location $end;

    #[ORM\Column(type: 'integer')]
    #[Groups(['travel'])]
    private ?int $duration;

    #[ORM\ManyToOne(targetEntity: Itinerary::class, inversedBy: 'travels')]
    #[Groups(['travel'])]
    private ?Itinerary $itinerary;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?Location
    {
        return $this->start;
    }

    public function setStart(?Location $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?Location
    {
        return $this->end;
    }

    public function setEnd(?Location $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

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
