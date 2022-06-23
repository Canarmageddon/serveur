<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TravelRepository;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TravelRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => 'travel:list']],
        'new' => [
            'method' => 'POST',
            'security' => [['bearerAuth' => []]],
            'route_name' => 'travel_new',
            'openapi_context' => [
                'summary'     => 'Create a travel',
                'description' => "Create a travel from two steps",
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type' => 'object',
                                'properties' =>
                                    [
                                        'duration' => ['type' => 'int'],
                                        'trip' => ['type' => 'int'],
                                        'start' => ['type' => 'int'],
                                        'end' => ['type' => 'int'],
                                    ],
                            ],
                            'example' => [
                                'duration' => 3600,
                                'trip' => 1,
                                'start' => 1,
                                'end' => 1,
                            ],
                        ],
                    ],
                ],
            ],
        ]
    ],
    itemOperations: [
        'get' => ['normalization_context' => ['groups' => 'travel:item']],
        'documents' => [
            'method' => 'GET',
            'route_name' => 'documents_by_travel',
        ],
        'edit' => [
            'method' => 'PUT',
            'route_name' => 'travel_edit',
            'openapi_context' => [
                'summary'     => 'Edit a travel',
                'security' => [['bearerAuth' => []]],
                'description' => "Edit a travel",
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type' => 'object',
                                'properties' =>
                                    [
                                        'duration' => ['type' => 'int'],
                                    ],
                            ],
                            'example' => [
                                'duration' => 3600
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'delete' => [
            "security" => "is_granted('TRIP_EDIT', object)",
            'openapi_context' => [
                'security' => [['bearerAuth' => []]]
            ]
        ]
    ],
    paginationEnabled: false,
)]
class Travel extends MapElement
{
    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['travel:list', 'travel:item', 'trip:list', 'trip:item'])]
    private ?int $duration = null;

    #[ORM\ManyToOne(targetEntity: Trip::class, inversedBy: 'travels')]
    #[Groups(['travel:list', 'travel:item'])]
    private ?Trip $trip;

    #[ORM\ManyToOne(targetEntity: Step::class, cascade: ['persist', 'remove'], inversedBy: 'starts')]
    #[Groups(['travel:list', 'travel:item', 'trip:list', 'trip:item'])]
    #[ORM\JoinColumn(name:"start_id", referencedColumnName:"id", nullable:true, onDelete: "SET NULL")]
    private ?Step $start;

    #[ORM\ManyToOne(targetEntity: Step::class, cascade: ['persist', 'remove'], inversedBy: 'ends')]
    #[Groups(['travel:list', 'travel:item', 'trip:list', 'trip:item'])]
    #[ORM\JoinColumn(name:"end_id", referencedColumnName:"id", nullable:true, onDelete: "SET NULL")]
    private ?Step $end;

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

    #[Pure] public function __construct()
    {
        parent::__construct();
    }
}
