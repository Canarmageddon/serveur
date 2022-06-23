<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TripUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TripUserRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            "openapi_context"=>[
                "summary"=>"hidden"
            ]
        ]
    ],
    itemOperations: [
        'get' => [
            "openapi_context"=>[
                "summary"=>"hidden"
            ]
        ]
    ]
)]
class TripUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Trip::class, cascade: ['persist'], inversedBy: 'tripUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trip $trip;

    #[ORM\ManyToOne(targetEntity: SuperUser::class, cascade: ['persist'], inversedBy: 'tripUsers')]
    #[Groups(['user:item'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?SuperUser $user;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?SuperUser
    {
        return $this->user;
    }

    public function setUser(?SuperUser $user): self
    {
        $this->user = $user;

        return $this;
    }
}
