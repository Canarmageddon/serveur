<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'float')]
    private $latitude;

    #[ORM\Column(type: 'float')]
    private $longitude;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $type;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: PointOfInterest::class)]
    private $pointOfInterests;

    public function __construct()
    {
        $this->pointOfInterests = new ArrayCollection();
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
}
