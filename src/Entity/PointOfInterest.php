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
                                        'title' => ['type' => 'string'],
                                        'description' => ['type' => 'string'],
                                        'creator' => ['type' => 'string'],
                                        'trip' => ['type' => 'string'],
                                    ],
                            ],
                            'example' => [
                                'latitude' => 48.123,
                                'longitude' => 7.123,
                                'title' => "Title",
                                'description' => "Brief POI description",
                                'creator' => "/api/users/id",
                                'trip' => "/api/trips/id",
                            ],
                        ],
                    ],
                ],
            ],
        ]],
    itemOperations: [
        'get' => ['normalization_context' => ['groups' => 'pointOfInterest:item']],
        'put',
        'delete',
        ],
    paginationEnabled: false,
)]
class PointOfInterest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['pointOfInterest:list', 'pointOfInterest:item', 'trip:list', 'trip:item'])]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'pointOfInterests')]
    #[Groups(['pointOfInterest:list', 'pointOfInterest:item', 'trip:list', 'trip:item'])]
    private ?Location $location;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'pointOfInterests')]
    #[Groups(['pointOfInterest:list', 'pointOfInterest:item', 'trip:list', 'trip:item'])]
    private ?User $creator;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['pointOfInterest:list', 'pointOfInterest:item', 'trip:list', 'trip:item'])]
    private DateTimeImmutable $creationDate;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['pointOfInterest', 'trip:list', 'trip:item'])]
    private ?string $description;

    #[ORM\OneToMany(mappedBy: 'pointOfInterest', targetEntity: Document::class)]
    #[Groups(['pointOfInterest:list', 'pointOfInterest:item', 'trip:list', 'trip:item'])]
    private Collection $documents;

    #[ORM\ManyToOne(targetEntity: Step::class, inversedBy: 'pointsOfInterest')]
    #[Groups(['pointOfInterest:list', 'pointOfInterest:item', 'trip:item'])]
    private ?Step $step;

    #[ORM\ManyToOne(targetEntity: Trip::class, inversedBy: 'pointsOfInterest')]
    #[Groups(['pointOfInterest:list', 'pointOfInterest:item'])]
    private ?Trip $trip;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $title;

    public function getId(): ?int
    {
        return $this->id;
    }

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
        $this->creationDate = new DateTimeImmutable('now');
        $this->documents = new ArrayCollection();
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

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setPointOfInterest($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getPointOfInterest() === $this) {
                $document->setPointOfInterest(null);
            }
        }

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
}
