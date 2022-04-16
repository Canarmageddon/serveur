<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    collectionOperations: ['get' => ['normalization_context' => ['groups' => 'user:list']]],
    itemOperations: ['get' => ['normalization_context' => ['groups' => 'user:item']]],
    order: ['creationDate' => 'ASC'],
    paginationEnabled: false,
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['user:list', 'user:item', 'trip:item'])]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['user:list', 'user:item', 'trip:item'])]
    private ?string $email;

    #[ORM\Column(type: 'json')]
    #[Groups(['user:list', 'user:item'])]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'string', length: 50)]
    #[Groups(['user:list', 'user:item', 'trip:item'])]
    private ?string $firstName;

    #[ORM\Column(type: 'string', length: 50)]
    #[Groups(['user:list', 'user:item', 'trip:item'])]
    private ?string $lastName;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['user:list', 'user:item'])]
    private DateTimeImmutable $creationDate;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: PointOfInterest::class)]
    #[Groups(['user:list', 'user:item'])]
    private Collection $pointOfInterests;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Step::class)]
    #[Groups(['user:list', 'user:item'])]
    private Collection $steps;

    #[ORM\ManyToOne(targetEntity: Trip::class, inversedBy: 'travelers')]
    #[Groups(['user:list', 'user:item'])]
    private ?Trip $trip;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Picture::class)]
    #[Groups(['user:list', 'user:item'])]
    private Collection $pictures;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Task::class)]
    #[Groups(['user:list', 'user:item'])]
    private Collection $tasks;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Cost::class)]
    #[Groups(['user:list', 'user:item'])]
    private Collection $costs;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCreationDate(): ?DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function __construct(){
        $this->creationDate = new DateTimeImmutable('now');
        $this->pointOfInterests = new ArrayCollection();
        $this->steps = new ArrayCollection();
        $this->pictures = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->costs = new ArrayCollection();
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
            $pointOfInterest->setCreator($this);
        }

        return $this;
    }

    public function removePointOfInterest(PointOfInterest $pointOfInterest): self
    {
        if ($this->pointOfInterests->removeElement($pointOfInterest)) {
            // set the owning side to null (unless already changed)
            if ($pointOfInterest->getCreator() === $this) {
                $pointOfInterest->setCreator(null);
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
            $step->setCreator($this);
        }

        return $this;
    }

    public function removeStep(Step $step): self
    {
        if ($this->steps->removeElement($step)) {
            // set the owning side to null (unless already changed)
            if ($step->getCreator() === $this) {
                $step->setCreator(null);
            }
        }

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
            $picture->setCreator($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getCreator() === $this) {
                $picture->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setCreator($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getCreator() === $this) {
                $task->setCreator(null);
            }
        }

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

    public function __toString(): string
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }
}
