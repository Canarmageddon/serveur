<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TravelRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TravelRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => 'travel:list']],
        'new' => [
            'method' => 'POST',
            'route_name' => 'travel_new',
        ]    ],
    itemOperations: [
        'get' => ['normalization_context' => ['groups' => 'travel:item']],
        'delete'
    ],
    paginationEnabled: false,
)]
class Travel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['travel:list', 'travel:item', 'trip:list', 'trip:item'])]
    private ?int $id;

    #[ORM\Column(type: 'integer')]
    #[Groups(['travel:list', 'travel:item', 'trip:list', 'trip:item'])]
    private ?int $duration;

    #[ORM\ManyToOne(targetEntity: Trip::class, inversedBy: 'travels')]
    #[Groups(['travel:list', 'travel:item'])]
    private ?Trip $trip;

    #[ORM\ManyToOne(targetEntity: Step::class, inversedBy: 'starts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Step $start;

    #[ORM\ManyToOne(targetEntity: Step::class, inversedBy: 'ends')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Step $end;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStart(): ?Step
    {
        return $this->start;
    }

    public function setStart(?Step $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?Step
    {
        return $this->end;
    }

    public function setEnd(?Step $end): self
    {
        $this->end = $end;

        return $this;
    }
}
