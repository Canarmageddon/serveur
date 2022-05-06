<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CostRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CostRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => 'cost:list']],
        'new' => [
            'method' => 'POST',
            'route_name' => 'cost_new',
            'openapi_context' => [
                'summary'     => 'Create a cost',
                'description' => "Gestion des bénéficiaires changée prochainement",
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type' => 'object',
                                'properties' =>
                                    [
                                        'label' => ['type' => 'string'],
                                        'value' => ['type' => 'float'],
                                        'beneficiaries' => ['type' => 'string'],
                                        'creator' => ['type' => 'int'],
                                        'trip' => ['type' => 'int'],
                                    ],
                            ],
                            'example' => [
                                'label' => "Motif du coût",
                                'value' => 13.37,
                                'beneficiaries' => "Adresses mails des Users concernés (sera changé bientôt)",
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
        'get' => ['normalization_context' => ['groups' => 'cost:item']],
        'delete'
    ],
    paginationEnabled: false,
)]
class Cost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['cost:list', 'cost:item'])]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'], inversedBy: 'costs')]
    #[Groups(['cost:list', 'cost:item'])]
    private ?User $creator;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['cost:list', 'cost:item'])]
    private ?DateTimeImmutable $creationDate;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['cost:list', 'cost:item'])]
    private ?string $category;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['cost:list', 'cost:item'])]
    private ?string $beneficiaries;

    #[ORM\ManyToOne(targetEntity: Trip::class, cascade: ['persist'], inversedBy: 'costs')]
    #[Groups(['cost:list', 'cost:item'])]
    private ?Trip $trip;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['cost:list', 'cost:item'])]
    private ?string $label;

    #[ORM\Column(type: 'float')]
    #[Groups(['cost:list', 'cost:item'])]
    private ?float $value;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getBeneficiaries(): ?string
    {
        return $this->beneficiaries;
    }

    public function setBeneficiaries(string $beneficiaries): self
    {
        $this->beneficiaries = $beneficiaries;

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

    public function __construct(){
        $this->creationDate = new DateTimeImmutable('now');
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }
}
