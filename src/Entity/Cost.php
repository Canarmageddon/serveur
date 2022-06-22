<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CostRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
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
                'security' => [['bearerAuth' => []]],
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type' => 'object',
                                'properties' =>
                                [
                                    'label' => ['type' => 'string'],
                                    'value' => ['type' => 'float'],
                                    'category' => ['type' => 'string'],
                                    'creator' => ['type' => 'int'],
                                    'trip' => ['type' => 'int'],
                                ],
                            ],
                            'example' => [
                                'label' => "Motif du coût",
                                'value' => 13.37,
                                'category' => "Hygiène",
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
        'edit' => [
            'method' => 'PUT',
            'route_name' => 'cost_edit',
            'openapi_context' => [
                'summary'     => 'Edit a cost',
                'security' => [['bearerAuth' => []]],
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
                                    'category' => ['type' => 'string']
                                ],
                            ],
                            'example' => [
                                'label' => "Motif du coût",
                                'value' => 13.37,
                                'category' => "Hygiène"
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'addBeneficiary' => [
            'method' => 'PUT',
            'route_name' => 'cost_add_beneficiary',
            'openapi_context' => [
                'summary'     => 'Add a Beneficiary to a Cost',
                'description' => "",
                'security' => [['bearerAuth' => []]],
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type' => 'object',
                                'properties' =>
                                [
                                    'userId' => ['type' => 'int'],
                                ],
                            ],
                            'example' => [
                                'userId' => 1,
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'removeBeneficiary' => [
            'method' => 'PUT',
            'route_name' => 'cost_remove_beneficiary',
            'openapi_context' => [
                'summary'     => 'Remove a Beneficiary from a Cost',
                'security' => [['bearerAuth' => []]],
                'description' => "",
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type' => 'object',
                                'properties' =>
                                [
                                    'userId' => ['type' => 'int'],
                                ],
                            ],
                            'example' => [
                                'userId' => 1,
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
        ]
    ],
    paginationEnabled: false,
)]
class Cost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['cost:list', 'cost:item', 'trip:item'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: SuperUser::class, cascade: ['persist'], inversedBy: 'costs')]
    #[Groups(['cost:list', 'cost:item', 'trip:item'])]
    private ?SuperUser $creator;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['cost:list', 'cost:item', 'trip:item'])]
    private ?DateTimeImmutable $creationDate;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['cost:list', 'cost:item', 'trip:item'])]
    private ?string $category;

    #[ORM\ManyToOne(targetEntity: Trip::class, cascade: ['persist'], inversedBy: 'costs')]
    #[Groups(['cost:list', 'cost:item'])]
    private ?Trip $trip;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['cost:list', 'cost:item', 'trip:item'])]
    private ?string $label;

    #[ORM\Column(type: 'float')]
    #[Groups(['cost:list', 'cost:item', 'trip:item'])]
    private ?float $value;

    #[ORM\OneToMany(mappedBy: 'cost', targetEntity: CostUser::class, orphanRemoval: true)]
    #[Groups(['cost:list', 'cost:item'])]
    private Collection $costUsers;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreator(): ?SuperUser
    {
        return $this->creator;
    }

    public function setCreator(?SuperUser $creator): self
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
        $this->costUsers = new ArrayCollection();
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

    public function setCreationDate(DateTimeImmutable $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * @return Collection<int, CostUser>
     */
    public function getCostUsers(): Collection
    {
        return $this->costUsers;
    }

    public function addCostUser(CostUser $costUser): self
    {
        if (!$this->costUsers->contains($costUser)) {
            $this->costUsers[] = $costUser;
            $costUser->setCost($this);
        }

        return $this;
    }

    public function removeCostUser(CostUser $costUser): self
    {
        if ($this->costUsers->removeElement($costUser)) {
            // set the owning side to null (unless already changed)
            if ($costUser->getCost() === $this) {
                $costUser->setCost(null);
            }
        }

        return $this;
    }

    #[Pure] public function getBeneficiaries(): array
    {
        $beneficiaries = [];
        foreach ($this->getCostUsers() as $costUser) {
            $beneficiaries[] = $costUser->getUser();
        }
        return $beneficiaries;
    }
}