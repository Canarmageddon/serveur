<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\StepRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StepRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => 'step:list']],
        'new' => [
            'method' => 'POST',
            'route_name' => 'step_new',
            'openapi_context' => [
                'summary'     => 'Create a step',
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
                                'description' => "Brief Step description",
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
        'get' => ['normalization_context' => ['groups' => 'step:item']],
        'documents' => [
            'method' => 'GET',
            'route_name' => 'documents_by_step',
        ],
        'poi' => [
            'method' => 'GET',
            'route_name' => 'poi_by_step',
        ],
        'new' => [
            'method' => 'PUT',
            'route_name' => 'step_edit',
            'openapi_context' => [
                'summary'     => 'Edit a step',
                'description' => "Edit a step",
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
                                    ],
                            ],
                            'example' => [
                                'latitude' => 48.123,
                                'longitude' => 7.123,
                                'name' => "Nom du lieu",
                                'type' => "Type du lieu",
                                'title' => "Titre de l'étape",
                                'description' => "Brief Step description",
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'delete'
    ],
    paginationEnabled: false,
)]

class Step extends MapElement
{
    #[ORM\ManyToOne(targetEntity: Location::class, cascade: ['persist'], inversedBy: 'steps')]
    #[Groups(['step:list', 'step:item', 'trip:list', 'trip:item'])]
    private ?Location $location;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['step:list', 'step:item', 'trip:list', 'trip:item'])]
    private ?DateTimeImmutable $creationDate;

    #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'], inversedBy: 'steps')]
    #[Groups(['step:list', 'step:item', 'trip:list', 'trip:item'])]
    private ?User $creator;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['step:list', 'step:item', 'trip:list', 'trip:item'])]
    private ?string $description;

    #[ORM\OneToMany(mappedBy: 'step', targetEntity: PointOfInterest::class)]
    #[Groups(['step:list', 'step:item'])]
    private Collection $pointsOfInterest;

    #[ORM\ManyToOne(targetEntity: Trip::class, cascade: ['persist'], inversedBy: 'steps')]
    #[Groups(['step:list', 'step:item'])]
    private ?Trip $trip;

    #[ORM\OneToMany(mappedBy: 'start', targetEntity: Travel::class, orphanRemoval: true)]
    private Collection $starts;

    #[ORM\OneToMany(mappedBy: 'end', targetEntity: Travel::class, orphanRemoval: true)]
    private Collection $ends;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['step:list', 'step:item', 'trip:list', 'trip:item'])]
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

    public function getCreationDate(): ?DateTimeImmutable
    {
        return $this->creationDate;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
        parent::__construct();
        $this->creationDate = new DateTimeImmutable('now');
        $this->pointsOfInterest = new ArrayCollection();
        $this->starts = new ArrayCollection();
        $this->ends = new ArrayCollection();
    }

    /**
     * @return Collection<int, PointOfInterest>
     */
    public function getPointsOfInterest(): Collection
    {
        return $this->pointsOfInterest;
    }

    public function addPointsOfInterest(PointOfInterest $pointsOfInterest): self
    {
        if (!$this->pointsOfInterest->contains($pointsOfInterest)) {
            $this->pointsOfInterest[] = $pointsOfInterest;
            $pointsOfInterest->setStep($this);
        }

        return $this;
    }

    public function removePointsOfInterest(PointOfInterest $pointsOfInterest): self
    {
        if ($this->pointsOfInterest->removeElement($pointsOfInterest)) {
            // set the owning side to null (unless already changed)
            if ($pointsOfInterest->getStep() === $this) {
                $pointsOfInterest->setStep(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Travel>
     */
    public function getStarts(): Collection
    {
        return $this->starts;
    }

    public function addStart(Travel $start): self
    {
        if (!$this->starts->contains($start)) {
            $this->starts[] = $start;
            $start->setStart($this);
        }

        return $this;
    }

    public function removeStart(Travel $start): self
    {
        if ($this->starts->removeElement($start)) {
            // set the owning side to null (unless already changed)
            if ($start->getStart() === $this) {
                $start->setStart(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Travel>
     */
    public function getEnds(): Collection
    {
        return $this->ends;
    }

    public function addEnd(Travel $end): self
    {
        if (!$this->ends->contains($end)) {
            $this->ends[] = $end;
            $end->setEnd($this);
        }

        return $this;
    }

    public function removeEnd(Travel $end): self
    {
        if ($this->ends->removeElement($end)) {
            // set the owning side to null (unless already changed)
            if ($end->getEnd() === $this) {
                $end->setEnd(null);
            }
        }

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
