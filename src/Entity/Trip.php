<?php

namespace App\Entity;

use App\Repository\TripRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: TripRepository::class)]
class Trip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: Itinerary::class)]
    private Collection $itineraries;

    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: User::class)]
    private Collection $travelers;

    #[ORM\OneToOne(mappedBy: 'trip', targetEntity: Album::class, cascade: ['persist', 'remove'])]
    private ?Album $album;

    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: Cost::class)]
    private Collection $costs;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name;

    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: ToDoList::class)]
    private $toDoLists;

    #[Pure] public function __construct()
    {
        $this->itineraries = new ArrayCollection();
        $this->travelers = new ArrayCollection();
        $this->costs = new ArrayCollection();
        $this->toDoLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Itinerary>
     */
    public function getItineraries(): Collection
    {
        return $this->itineraries;
    }

    public function addItinerary(Itinerary $itinerary): self
    {
        if (!$this->itineraries->contains($itinerary)) {
            $this->itineraries[] = $itinerary;
            $itinerary->setTrip($this);
        }

        return $this;
    }

    public function removeItinerary(Itinerary $itinerary): self
    {
        if ($this->itineraries->removeElement($itinerary)) {
            // set the owning side to null (unless already changed)
            if ($itinerary->getTrip() === $this) {
                $itinerary->setTrip(null);
            }
        }

        return $this;
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
}
