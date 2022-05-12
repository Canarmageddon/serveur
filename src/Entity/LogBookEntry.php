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
class LogBookEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['logBookEntry:list', 'logBookEntry:item'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['logBookEntry:list', 'logBookEntry:item'])]
    private ?string $content;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['logBookEntry:list', 'logBookEntry:item'])]
    private ?DateTimeImmutable $creationDate;

    #[ORM\ManyToOne(targetEntity: Trip::class, inversedBy: 'logBookEntries')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['logBookEntry:list', 'logBookEntry:item'])]
    private ?Trip $trip;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'logBookEntries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $creator;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreationDate(): ?DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function setCreationDate(DateTimeImmutable $creationDate): self
    {
        $this->creationDate = $creationDate;

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

    public function __construct()
    {
        $this->creationDate = new DateTimeImmutable('now');
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
}
