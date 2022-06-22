<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GuestRepository;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GuestRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => ['groups' => 'guest:list'],
            'security' => "is_granted('TRIP_EDIT', object)",
            'openapi_context' => [
                'security' => [['bearerAuth' => []]]
            ]
        ],
        'new' => [
            'method' => 'POST',
            'route_name' => 'guest_new',
            'openapi_context' => [
                'summary'     => 'Create a Guest',
                'description' => "Create a Guest",
                'security' => [['bearerAuth' => []]],
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type' => 'object',
                                'properties' =>
                                    [
                                        'name' => ['type' => 'string'],
                                        'trip' => ['type' => 'int'],
                                    ],
                            ],
                            'example' => [
                                'name' => "Name",
                                'trip' => 1,
                            ],
                        ],
                    ],
                ],
            ],
        ]
    ],
    itemOperations: [
        'get' => ['normalization_context' => ['groups' => 'guest:item:read']],
        'edit' => [
            'method' => 'PUT',
            'route_name' => 'guest_edit',
            'openapi_context' => [
                'summary'     => 'Edit a Guest',
                'security' => [['bearerAuth' => []]],
                'description' => "Edit a Guest",
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type' => 'object',
                                'properties' =>
                                    [
                                        'name' => ['type' => 'string'],
                                    ],
                            ],
                            'example' => [
                                'name' => "Name",
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'delete' => [
            'security' => "is_granted('TRIP_EDIT', object)",
            'openapi_context' => [
                'security' => [['bearerAuth' => []]]
            ]
        ],
    ],
    paginationEnabled: false,
)]
class Guest extends SuperUser
{
    #[ORM\Column(type: 'string', length: 50)]
    #[Groups(['guest:list', 'guest:item', 'user:list', 'user:item'])]
    private ?string $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    #[Pure] public function __construct()
    {
        parent::__construct();
    }
}
