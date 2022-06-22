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
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => ['groups' => 'user:list'],
            'security' => "is_granted('TRIP_EDIT', object)",
            'openapi_context' => [
                'security' => [['bearerAuth' => []]]
            ]
        ],
        'byEmail' => [
            'method' => 'GET',
            'route_name' => 'user_by_email',
            "openapi_context" => [
                'security' => [['bearerAuth' => []]],
                "parameters" => [
                    [
                        "name" => "email",
                        "type" => "string",
                        "in" => "path",
                        "required" => true,
                    ]
                ]
            ]
        ],
        'post' => ['denormalization_context' => ['groups' => 'user:write']],
    ],
    itemOperations: [
        'get' => ['normalization_context' => ['groups' => 'user:item:read']],
        'trips' => [
            'method' => 'GET',
            'route_name' => 'trips_by_user',
        ],
        'tripsEnded' => [
            'method' => 'GET',
            'route_name' => 'trips_ended_by_user',
            'openapi_context' => [
                'parameters' => [
                    [
                        'name' => 'isEnded',
                        'in' => 'path',
                        'description' => 'Condition identifier : is the trip ended ? (0 or 1)',
                        'required' => true,
                        'type' => 'bool',
                    ]
                ]
            ]
        ],
        'edit' => [
            'method' => 'PUT',
            'route_name' => 'user_edit',
            'openapi_context' => [
                'summary'     => 'Edit a User',
                'security' => [['bearerAuth' => []]],
                'description' => "Edit a User",
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type' => 'object',
                                'properties' =>
                                [
                                    'firstName' => ['type' => 'string'],
                                    'lastName' => ['type' => 'string'],
                                ],
                            ],
                            'example' => [
                                'firstName' => "Prénom",
                                'lastName' => "Nom",
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'delete' => [
            'security' => "is_granted('TRIP_EDIT', object)",
            'openapi_context' => [
                'security' => [['bearerAuth' => []]]
            ]
        ],
    ],
    paginationEnabled: false,
)]
class User extends SuperUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['user:read', 'user:write', 'user:list', 'user:item', 'trip:item', 'user:item:read', 'picture:list', 'picture:item'])]
    private ?string $email;

    #[ORM\Column(type: 'json')]
    #[Groups(['user:list', 'user:item'])]
    private array $roles;

    #[ORM\Column(type: 'string')]
    private string $password;

    #[SerializedName("password")]
    #[Groups(['user:write'])]
    private ?string $plainPassword;

    #[ORM\Column(type: 'string', length: 50)]
    #[Groups(['user:read', 'user:write', 'user:list', 'user:item', 'trip:item', 'picture:read', 'picture:list', 'picture:item', 'cost:list', 'cost:item', 'user:item:read'])]
    private ?string $firstName;

    #[ORM\Column(type: 'string', length: 50)]
    #[Groups(['user:read', 'user:write', 'user:list', 'user:item', 'trip:item', 'picture:read', 'picture:list', 'picture:item', 'cost:list', 'cost:item', 'user:item:read'])]
    private ?string $lastName;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['user:read', 'user:list', 'user:item'])]
    private DateTimeImmutable $creationDate;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: PointOfInterest::class)]
    #[Groups(['user:list', 'user:item'])]
    private Collection $pointOfInterests;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Step::class)]
    #[Groups(['user:list', 'user:item'])]
    private Collection $steps;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: AlbumElement::class)]
    #[Groups(['user:list', 'user:item'])]
    private Collection $albumElements;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Document::class)]
    #[Groups(['user:list', 'user:item'])]
    private Collection $documents;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Task::class)]
    #[Groups(['user:list', 'user:item'])]
    private Collection $tasks;

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
     * @return ?string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
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
        parent::__construct();

        $this->creationDate = new DateTimeImmutable('now');
        $this->roles = ["ROLE_USER"];
        $this->pointOfInterests = new ArrayCollection();
        $this->steps = new ArrayCollection();
        $this->albumElements = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->documents = new ArrayCollection();
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

    public function __toString(): string
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    /**
     * @return Collection<int, AlbumElement>
     */
    public function getAlbumElements(): Collection
    {
        return $this->albumElements;
    }

    public function addAlbumElement(AlbumElement $albumElement): self
    {
        if (!$this->albumElements->contains($albumElement)) {
            $this->albumElements[] = $albumElement;
            $albumElement->setCreator($this);
        }

        return $this;
    }

    public function removeAlbumElement(AlbumElement $albumElement): self
    {
        if ($this->albumElements->removeElement($albumElement)) {
            // set the owning side to null (unless already changed)
            if ($albumElement->getTrip() === $this) {
                $albumElement->setTrip(null);
            }
        }

        return $this;
    }

    public function setCreationDate(DateTimeImmutable $creationDate): self
    {
        $this->creationDate = $creationDate;

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
            $document->setCreator($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getCreator() === $this) {
                $document->setCreator(null);
            }
        }

        return $this;
    }
}
