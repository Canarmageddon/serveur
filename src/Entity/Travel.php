<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TravelRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TravelRepository::class)]
#[ApiResource(
    collectionOperations: ['get' => ['normalization_context' => ['groups' => 'travel:list']]],
    itemOperations: ['get' => ['normalization_context' => ['groups' => 'travel:item']]],
    order: ['trip' => 'ASC'],
    paginationEnabled: false,
)]
class Travel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['travel:list', 'travel:item'])]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'starts')]
    #[Groups(['travel:list', 'travel:item'])]
    private ?Location $start;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'ends')]
    #[Groups(['travel:list', 'travel:item'])]
    private ?Location $end;

    #[ORM\Column(type: 'integer')]
    #[Groups(['travel:list', 'travel:item'])]
    private ?int $duration;

    #[ORM\ManyToOne(targetEntity: Trip::class, inversedBy: 'travels')]
    #[Groups(['travel:list', 'travel:item'])]
    private ?Trip $trip;

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
