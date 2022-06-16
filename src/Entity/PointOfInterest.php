<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PointOfInterestRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PointOfInterestRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => 'pointOfInterest:list']],
        'new' => [
            'method' => 'POST',
            'route_name' => 'point_of_interest_new',
            'openapi_context' => [
                'summary'     => 'Create a point of interest',
                'security' => [['bearerAuth' => []]],
                'description' => "Longitude and latitude needed, others are nullable",
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type' => 'object',
                                'properties' =>
                                    [
                                        'latitude' => ['type' => 'float'],
                                        'longitude' => ['type' => 'float'],
                                        'name' => ['type' => 'string'],
                                        'type' => ['type' => 'string'],
                                        'title' => ['type' => 'string'],
                                        'description' => ['type' => 'string'],
                                        'creator' => ['type' => 'int'],
                                        'trip' => ['type' => 'int'],
                                    ],
                            ],
                            'example' => [
                                'latitude' => 48.123,
                                'longitude' => 7.123,
                                'name' => "Nom du lieu",
                                'type' => "Type du lieu",
                                'title' => "Title",
                                'description' => "Brief POI description",
                                'creator' => 1,
                                'trip' => 1,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    itemOperations: [
        'get' => ['normalization_context' => ['groups' => 'pointOfInterest:item']],
        'documents' => [
            'method' => 'GET',
            'route_name' => 'documents_by_poi',
        ],
        'edit' => [
            'method' => 'PUT',
            'route_name' => 'point_of_interest_edit',
            'openapi_context' => [
                'summary'     => 'Edit a point of interest',
                'security' => [['bearerAuth' => []]],
                'description' => "Edit a point of interest",
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type' => 'object',
                                'properties' =>
                                    [
                                        'latitude' => ['type' => 'float'],
                                        'longitude' => ['type' => 'float'],
                                        'name' => ['type' => 'string'],
                                        'type' => ['type' => 'string'],
                                        'title' => ['type' => 'string'],
                                        'description' => ['type' => 'string'],
                                        'step' => ['type' => 'int'],
                                    ],
                            ],
                            'example' => [
                                'latitude' => 48.123,
                                'longitude' => 7.123,
                                'name' => "Nom du lieu",
                                'type' => "Type du lieu",
                                'title' => "Titre du POI",
                                'description' => "Brief POI description",
                                'step' => 1,
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'toStep' => [
            'method' => 'PUT',
            'route_name' => 'point_of_interest_to_step',
        ],
        'delete' => [
            "security" => "is_granted('TRIP_EDIT', object)",
            'openapi_context' => [
                'security' => [['bearerAuth' => []]]
            ]
        ],
    ],
    paginationEnabled: false,
)]
class PointOfInterest extends MapElement
{
    #[ORM\ManyToOne(targetEntity: Location::class, cascade: ['persist'], inversedBy: 'pointOfInterests')]
    #[Groups(['pointOfInterest:list', 'pointOfInterest:item', 'trip:list', 'trip:item'])]
    private ?Location $location;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'pointOfInterests')]
    #[Groups(['pointOfInterest:list', 'pointOfInterest:item', 'trip:list', 'trip:item'])]
    private ?User $creator;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['pointOfInterest:list', 'pointOfInterest:item', 'trip:list', 'trip:item'])]
    private DateTimeImmutable $creationDate;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['pointOfInterest:list', 'pointOfInterest:item', 'trip:list', 'trip:item'])]
    private ?string $description;

    #[ORM\ManyToOne(targetEntity: Step::class, inversedBy: 'pointsOfInterest')]
    #[Groups(['pointOfInterest:list', 'pointOfInterest:item', 'trip:list', 'trip:item'])]
    private ?Step $step;

    #[ORM\ManyToOne(targetEntity: Trip::class, inversedBy: 'pointsOfInterest')]
    #[Groups(['pointOfInterest:list', 'pointOfInterest:item'])]
    private ?Trip $trip;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['pointOfInterest:list', 'pointOfInterest:item', 'trip:list', 'trip:item'])]
    private ?string $title;

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
        parent::__construct();
        $this->creationDate = new DateTimeImmutable('now');
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

    public function getStep(): ?Step
    {
        return $this->step;
    }

    public function setStep(?Step $step): self
    {
        $this->step = $step;

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setCreationDate(\DateTimeImmutable $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }
}
