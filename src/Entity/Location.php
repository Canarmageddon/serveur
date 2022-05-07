<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => ['groups' => 'location:list']],
        'new' => [
            'method' => 'POST',
            'route_name' => 'location_new',
            'openapi_context' => [
                'summary'     => 'Create a location',
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
                                        'category' => ['type' => 'string'],
                                    ],
                            ],
                            'example' => [
                                'latitude' => 48.123,
                                'longitude' => 7.123,
                                'title' => "Title",
                                'category' => "Ex : Musée",
                            ],
                        ],
                    ],
                ],
            ],
        ]
    ],
    itemOperations: [
        'get' => ['normalization_context' => ['groups' => 'location:item']],
        'poi' => [
            'method' => 'GET',
            'route_name' => 'poi_by_location',
        ],
        'steps' => [
            'method' => 'GET',
            'route_name' => 'steps_by_location',
        ],
        'pictures' => [
            'method' => 'GET',
            'route_name' => 'pictures_by_location',
        ],
        'delete'
    ],
    paginationEnabled: false,
)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['location:list', 'location:item', 'trip:list', 'trip:item'])]
    private ?int $id;

    #[ORM\Column(type: 'float')]
    #[Groups(['location:list', 'location:item', 'pointOfInterest:list', 'pointOfInterest:item', 'step:list', 'step:item', 'trip:list', 'trip:item', 'picture:read'])]
    private ?float $latitude;

    #[ORM\Column(type: 'float')]
    #[Groups(['location:list', 'location:item', 'pointOfInterest:list', 'pointOfInterest:item', 'step:list', 'step:item', 'trip:list', 'trip:item', 'picture:read'])]
    private ?float $longitude;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['location:list', 'location:item', 'pointOfInterest:list', 'pointOfInterest:item', 'trip:list', 'trip:item', 'picture:read'])]
    private ?string $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['location:list', 'location:item'])]
    private ?string $type;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: PointOfInterest::class, cascade: ['persist', 'remove'])]
    #[Groups(['location:list', 'location:item'])]
    private Collection $pointOfInterests;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: Step::class,  cascade: ['persist', 'remove'])]
    #[Groups(['location:list', 'location:item'])]
    private Collection $steps;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: Picture::class)]
    #[Groups(['location:list', 'location:item'])]
    private Collection $pictures;

    #[Pure] public function __construct()
    {
        $this->pointOfInterests = new ArrayCollection();
        $this->steps = new ArrayCollection();
        $this->pictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, PointOfInterest>
     */
    public function getPointOfInterests(): Collection
    {
        return $this->pointOfInterests;
    }

    public function addPointOfInterest(PointOfInterest $pointOfInterest): self
    {
        if (!$this->pointOfInterests->contains($pointOfInterest)) {
            $this->pointOfInterests[] = $pointOfInterest;
            $pointOfInterest->setLocation($this);
        }

        return $this;
    }

    public function removePointOfInterest(PointOfInterest $pointOfInterest): self
    {
        if ($this->pointOfInterests->removeElement($pointOfInterest)) {
            // set the owning side to null (unless already changed)
            if ($pointOfInterest->getLocation() === $this) {
                $pointOfInterest->setLocation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Step>
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(Step $step): self
    {
        if (!$this->steps->contains($step)) {
            $this->steps[] = $step;
            $step->setLocation($this);
        }

        return $this;
    }

    public function removeStep(Step $step): self
    {
        if ($this->steps->removeElement($step)) {
            // set the owning side to null (unless already changed)
            if ($step->getLocation() === $this) {
                $step->setLocation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setLocation($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getLocation() === $this) {
                $picture->setLocation(null);
            }
        }

        return $this;
    }
}
