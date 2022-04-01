<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\TripController;
use App\Repository\TripRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TripRepository::class)]
#[ApiResource(
    collectionOperations: ['get' => ['normalization_context' => ['groups' => 'trip:list']]],
    itemOperations: ['get' => ['normalization_context' => ['groups' => 'trip:item']],
        'trip_new' => [
            'method' => 'POST',
            'path' => '/api/trips',
            'controller' => TripController::class,
        ],
        'put',
        'delete',
    ],
    order: ['name' => 'ASC'],
    paginationEnabled: false,
)]
class Trip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['trip:list', 'trip:item'])]
    private ?int $id;

    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: User::class)]
    #[Groups(['trip:list', 'trip:item'])]
    private Collection $travelers;

    #[ORM\OneToOne(mappedBy: 'trip', targetEntity: Album::class, cascade: ['persist', 'remove'])]
    #[Groups(['trip:list', 'trip:item'])]
    private ?Album $album;

    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: Cost::class)]
    #[Groups(['trip:list', 'trip:item'])]
    private Collection $costs;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['trip:list', 'trip:item'])]
    private ?string $name;

    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: ToDoList::class)]
    #[Groups(['trip:list', 'trip:item'])]
    private Collection $toDoLists;

    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: PointOfInterest::class)]
    #[Groups(['trip:list', 'trip:item'])]
    private Collection $pointsOfInterest;

    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: Step::class)]
    #[Groups(['trip:list', 'trip:item'])]
    private Collection $steps;

    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: Travel::class)]
    #[Groups(['trip:list', 'trip:item'])]
    private Collection $travels;

    #[Pure] public function __construct()
    {
        $this->travelers = new ArrayCollection();
        $this->costs = new ArrayCollection();
        $this->toDoLists = new ArrayCollection();
        $this->pointsOfInterest = new ArrayCollection();
        $this->steps = new ArrayCollection();
        $this->travels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, User>
     */
    public function getTravelers(): Collection
    {
        return $this->travelers;
    }

    public function addTraveler(User $traveler): self
    {
        if (!$this->travelers->contains($traveler)) {
            $this->travelers[] = $traveler;
            $traveler->setTrip($this);
        }

        return $this;
    }

    public function removeTraveler(User $traveler): self
    {
        if ($this->travelers->removeElement($traveler)) {
            // set the owning side to null (unless already changed)
            if ($traveler->getTrip() === $this) {
                $traveler->setTrip(null);
            }
        }

        return $this;
    }

    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    public function setAlbum(?Album $album): self
    {
        // unset the owning side of the relation if necessary
        if ($album === null && $this->album !== null) {
            $this->album->setTrip(null);
        }

        // set the owning side of the relation if necessary
        if ($album !== null && $album->getTrip() !== $this) {
            $album->setTrip($this);
        }

        $this->album = $album;

        return $this;
    }

    /**
     * @return Collection<int, Cost>
     */
    public function getCosts(): Collection
    {
        return $this->costs;
    }

    public function addCost(Cost $cost): self
    {
        if (!$this->costs->contains($cost)) {
            $this->costs[] = $cost;
            $cost->setTrip($this);
        }

        return $this;
    }

    public function removeCost(Cost $cost): self
    {
        if ($this->costs->removeElement($cost)) {
            // set the owning side to null (unless already changed)
            if ($cost->getTrip() === $this) {
                $cost->setTrip(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, ToDoList>
     */
    public function getToDoLists(): Collection
    {
        return $this->toDoLists;
    }

    public function addToDoList(ToDoList $toDoList): self
    {
        if (!$this->toDoLists->contains($toDoList)) {
            $this->toDoLists[] = $toDoList;
            $toDoList->setTrip($this);
        }

        return $this;
    }

    public function removeToDoList(ToDoList $toDoList): self
    {
        if ($this->toDoLists->removeElement($toDoList)) {
            // set the owning side to null (unless already changed)
            if ($toDoList->getTrip() === $this) {
                $toDoList->setTrip(null);
            }
        }

        return $this;
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
            $pointsOfInterest->setTrip($this);
        }

        return $this;
    }

    public function removePointsOfInterest(PointOfInterest $pointsOfInterest): self
    {
        if ($this->pointsOfInterest->removeElement($pointsOfInterest)) {
            // set the owning side to null (unless already changed)
            if ($pointsOfInterest->getTrip() === $this) {
                $pointsOfInterest->setTrip(null);
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
            $step->setTrip($this);
        }

        return $this;
    }

    public function removeStep(Step $step): self
    {
        if ($this->steps->removeElement($step)) {
            // set the owning side to null (unless already changed)
            if ($step->getTrip() === $this) {
                $step->setTrip(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Travel>
     */
    public function getTravels(): Collection
    {
        return $this->travels;
    }

    public function addTravel(Travel $travel): self
    {
        if (!$this->travels->contains($travel)) {
            $this->travels[] = $travel;
            $travel->setTrip($this);
        }

        return $this;
    }

    public function removeTravel(Travel $travel): self
    {
        if ($this->travels->removeElement($travel)) {
            // set the owning side to null (unless already changed)
            if ($travel->getTrip() === $this) {
                $travel->setTrip(null);
            }
        }

        return $this;
    }
}
