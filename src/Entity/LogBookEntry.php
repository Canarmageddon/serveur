<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LogBookEntryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LogBookEntryRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => 'logBookEntry:list']],
        'new' => [
            'method' => 'POST',
            'route_name' => 'log_book_entry_new',
            'openapi_context' => [
                'summary'     => 'Create a Logbook Entry',
                'security' => [['bearerAuth' => []]],
                'description' => "Create a Logbook Entry",
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type' => 'object',
                                'properties' =>
                                [
                                    'content' => ['type' => 'string'],
                                    'creator' => ['type' => 'int'],
                                    'trip' => ['type' => 'int'],
                                    'location' => ['type' => 'int'],
                                    'latitude' => ['type' => 'float'],
                                    'longitude' => ['type' => 'float'],

                                ],
                            ],
                            'example' => [
                                'content' => "Contenu de l'entrée",
                                'creator' => 1,
                                'trip' => 1,
                                'location' => 1,
                                'latitude' => 1.234,
                                'longitude' => 5.678,
                            ],
                        ],
                    ],
                ],
            ],
        ]
    ],
    itemOperations: [
        'get' => ['normalization_context' => ['groups' => 'logBookEntry:item']],
        'edit' => [
            'method' => 'PUT',
            'route_name' => 'log_book_entry_edit',
            'openapi_context' => [
                'summary'     => 'Edit a logbook Entry',
                'security' => [['bearerAuth' => []]],
                'description' => "Edit a logbook Entry",
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type' => 'object',
                                'properties' =>
                                [
                                    'content' => ['type' => 'string'],
                                ],
                            ],
                            'example' => [
                                'content' => "Contenu de l'entrée",
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'delete'  => [
            'security' => "isGranted('TRIP_EDIT', object)",
            'openapi_context' => [
                'security' => [['bearerAuth' => []]]
            ]
        ],
    ],
    paginationEnabled: false
)]
class LogBookEntry extends AlbumElement
{
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['albumElement:list', 'albumElement:item', 'logBookEntry:list', 'logBookEntry:item', 'album:list', 'album:item', 'location:list', 'location:item'])]
    private ?string $content;

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function __construct()
    {
        parent::__construct();
        $this->setType2('log_book_entry');
    }
}