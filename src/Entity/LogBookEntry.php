<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LogBookEntryRepository;
use DateTimeImmutable;
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
                                    ],
                            ],
                            'example' => [
                                'content' => "Contenu de l'entrée",
                                'creator' => 1,
                                'trip' => 1,
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
        'delete',
    ],
    paginationEnabled: false
)]
class LogBookEntry extends AlbumElement
{
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['albumElement:list', 'albumElement:item', 'logBookEntry:list', 'logBookEntry:item'])]
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
