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
    collectionOperations: ['get' => ['normalization_context' => ['groups' => 'step:list']]],
    itemOperations: ['get' => ['normalization_context' => ['groups' => 'step:item']]],
    order: ['trip' => 'ASC'],
    paginationEnabled: false,
)]
class Step
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['step:list', 'step:item', 'trip:list', 'trip:item'])]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'steps')]
    #[Groups(['step:list', 'step:item', 'trip:list', 'trip:item'])]
    private ?Location $location;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['step:list', 'step:item', 'trip:list', 'trip:item'])]
    private ?DateTimeImmutable $creationDate;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'steps')]
    #[Groups(['step:list', 'step:item', 'trip:list', 'trip:item'])]
    private ?User $creator;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['step:list', 'step:item', 'trip:list', 'trip:item'])]
    private ?string $description;

    #[ORM\ManyToOne(targetEntity: Document::class, inversedBy: 'steps')]
    #[Groups(['step:list', 'step:item', 'trip:list', 'trip:item'])]
    private ?Document $documents;

    #[ORM\OneToMany(mappedBy: 'step', targetEntity: PointOfInterest::class)]
    #[Groups(['step:list', 'step:item'])]
    private Collection $pointsOfInterest;

    #[ORM\ManyToOne(targetEntity: Trip::class, inversedBy: 'steps')]
    #[Groups(['step:list', 'step:item'])]
    private ?Trip $trip;

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

    public function getDocuments(): ?Document
    {
        return $this->documents;
    }

    public function setDocuments(?Document $documents): self
    {
        $this->documents = $documents;

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
        $this->pointsOfInterest = new ArrayCollection();
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
}
