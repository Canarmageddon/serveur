<?php

namespace App\Entity;

use App\Repository\SuperUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SuperUserRepository::class)]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\DiscriminatorMap([
    "user" => "User",
    "guest" => "Guest"
])]
abstract class SuperUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['guest:list', 'guest:item', 'user:read', 'user:write', 'user:list', 'user:item', 'trip:item', 'cost:list', 'cost:item'])]
    private ?int $id = null;

    private ?string $type;

    /** Costs where the User is the Creator */
    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Cost::class, cascade: ['persist', 'remove'])]
    #[Groups(['guest:list', 'guest:item', 'user:list', 'user:item'])]
    private Collection $costs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: TripUser::class, orphanRemoval: true)]
    #[Groups(['guest:list', 'guest:item', 'user:item'])]
    private Collection $tripUsers;

    /** Costs where the User is a beneficiary */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CostUser::class, orphanRemoval: true)]
    private Collection $costUsers;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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
            $cost->setCreator($this);
        }

        return $this;
    }

    public function removeCost(Cost $cost): self
    {
        if ($this->costs->removeElement($cost)) {
            // set the owning side to null (unless already changed)
            if ($cost->getCreator() === $this) {
                $cost->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TripUser>
     */
    public function getTripUsers(): Collection
    {
        return $this->tripUsers;
    }

    public function addTripUser(TripUser $tripUser): self
    {
        if (!$this->tripUsers->contains($tripUser)) {
            $this->tripUsers[] = $tripUser;
            $tripUser->setUser($this);
        }

        return $this;
    }

    public function removeTripUser(TripUser $tripUser): self
    {
        if ($this->tripUsers->removeElement($tripUser)) {
            // set the owning side to null (unless already changed)
            if ($tripUser->getUser() === $this) {
                $tripUser->setUser(null);
            }
        }

        return $this;
    }

    public function getTrips(): array
    {
        $trips = [];
        foreach($this->getTripUsers() as $tripUser) {
            $trips[] = $tripUser->getTrip();
        }
        return $trips;
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
            $costUser->setUser($this);
        }

        return $this;
    }

    public function removeCostUser(CostUser $costUser): self
    {
        if ($this->costUsers->removeElement($costUser)) {
            // set the owning side to null (unless already changed)
            if ($costUser->getUser() === $this) {
                $costUser->setUser(null);
            }
        }

        return $this;
    }

    public function isMemberOf(int $id): bool
    {
        foreach($this->getTrips() as $trip) {
            if ($trip->getId() == $id) {
                return true;
            }
        }

        return false;
    }

    #[Pure] public function __construct()
    {
        $this->costs = new ArrayCollection();
        $this->costUsers = new ArrayCollection();
        $this->tripUsers = new ArrayCollection();
    }
}
