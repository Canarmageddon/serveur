<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TripRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: TripRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => 'trip:list', "enable_max_depth" => true]],
        'new' => [
            'method' => 'POST',
            'route_name' => 'trip_new',
            'openapi_context' => [
                'summary'     => 'Create a Trip',
                'security' => [['bearerAuth' => []]],
                'description' => "Create a Trip",
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type' => 'object',
                                'properties' =>
                                    [
                                        'name' => ['type' => 'string'],
                                        'creator' => ['type' => 'int']
                                    ],
                            ],
                            'example' => [
                                'name' => "Vacances au soleil",
                                'creator' => 1,
                            ],
                        ],
                    ],
                ],
            ],
        ]
    ],
    itemOperations: [
        'get' => ['normalization_context' => ['groups' => 'trip:item', "enable_max_depth" => true]],
        'album' => [
            'method' => 'GET',
            'route_name' => 'album_by_trip',
        ],
        'data' => [
            'method' => 'GET',
            'route_name' => 'album_elements_by_trip',
            "order" => ["creationDate" => "ASC"]
        ],
        'costs' => [
            'method' => 'GET',
            'route_name' => 'costs_by_trip',
        ],
        'log_book_entries' => [
            'method' => 'GET',
            'route_name' => 'log_book_entries_by_trip',
        ],
        'pictures' => [
            'method' => 'GET',
            'route_name' => 'pictures_by_trip',
        ],
        'poi' => [
            'method' => 'GET',
            'route_name' => 'poi_by_trip',
        ],
        'steps' => [
            'method' => 'GET',
            'route_name' => 'steps_by_trip',
        ],
        'travels' => [
            'method' => 'GET',
            'route_name' => 'travels_by_trip',
        ],
        'to_do_lists' => [
            'method' => 'GET',
            'route_name' => 'to_do_lists_by_trip',
        ],
        'users' => [
            'method' => 'GET',
            'route_name' => 'users_by_trip',
        ],
        'edit' => [
            'method' => 'PUT',
            'route_name' => 'trip_edit',
            'openapi_context' => [
                'summary'     => 'Edit a Trip',
                'security' => [['bearerAuth' => []]],
                'description' => "Edit a Trip",
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type' => 'object',
                                'properties' =>
                                    [
                                        'name' => ['type' => 'string'],
                                    ],
                            ],
                            'example' => [
                                'name' => "Vacances au soleil",
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'addUser' => [
            'method' => 'PUT',
            'route_name' => 'trip_add_user',
            'openapi_context' => [
                'summary'     => 'Add a User to a Trip',
                'security' => [['bearerAuth' => []]],
                'description' => "Nécessite l'email et l'id du trip, si aucun rôle précisé, rôle = 'guest'",
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type' => 'object',
                                'properties' =>
                                    [
                                        'email' => ['type' => 'string'],
                                    ],
                            ],
                            'example' => [
                                'email' => "root@root.fr",
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'removeUser' => [
            'method' => 'PUT',
            'route_name' => 'trip_remove_user',
            'openapi_context' => [
                'summary'     => 'Remove a User from a Trip',
                'security' => [['bearerAuth' => []]],
                'description' => "Vérifie si les deux données correspondent à des entités, puis l'enlève",
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type' => 'object',
                                'properties' =>
                                    [
                                        'email' => ['type' => 'string'],
                                    ],
                            ],
                            'example' => [
                                'email' => "root@root.fr",
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'generateLink' => [
            'method' => 'PUT',
            'route_name' => 'trip_generate_link',
            'openapi_context' => [
                'summary'     => 'Generate a link to see the trip without having an account',
                'security' => [['bearerAuth' => []]],
                'description' => "",
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type' => 'object',
                            ],
                            'example' => [
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'removeLink' => [
            'method' => 'PUT',
            'route_name' => 'trip_remove_link',
            'openapi_context' => [
                'summary'     => 'Remove the link',
                'security' => [['bearerAuth' => []]],
                'description' => "",
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                            ],
                            'example' => [
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'checkLink' => [
            'method' => 'GET',
            'route_name' => 'trip_check_link',
            'openapi_context' => [
                'parameters' => [
                    [
                        'name' => 'link',
                        'in' => 'path',
                        'description' => 'link',
                        'required' => true,
                        'type' => 'string',
                    ]
                ]
            ]
        ],
        'delete' => [
            "security" => "is_granted('TRIP_EDIT', object)",
            'openapi_context' => [
                'security' => [['bearerAuth' => []]]
            ]
        ]
    ],
    attributes: ["pagination_items_per_page" => 10] 
)]
class Trip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['trip:list', 'trip:item', 'travel:list', 'travel:item'])]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'trip', targetEntity: Album::class, cascade: ['persist', 'remove'])]
    #[Groups(['trip:list', 'trip:item'])]
    #[MaxDepth(1)]
    private ?Album $album;

    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: Cost::class,  cascade: ['persist', 'remove'])]
    #[Groups(['trip:list', 'trip:item'])]
    private Collection $costs;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['trip:list', 'trip:item'])]
    private ?string $name;

    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: ToDoList::class, cascade: ['persist', 'remove'])]
    #[Groups(['trip:list', 'trip:item'])]
    private Collection $toDoLists;

    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: PointOfInterest::class, cascade: ['persist', 'remove'])]
    #[Groups(['trip:list', 'trip:item'])]
    #[MaxDepth(2)]
    private Collection $pointsOfInterest;

    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: Step::class, cascade: ['persist', 'remove'])]
    #[Groups(['trip:list', 'trip:item'])]
    #[MaxDepth(2)]
    private Collection $steps;

    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: Travel::class, cascade: ['persist', 'remove'])]
    #[Groups(['trip:list', 'trip:item'])]
    private Collection $travels;

    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: TripUser::class, orphanRemoval: true)]
    private Collection $tripUsers;

    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: AlbumElement::class, orphanRemoval: true)]
    #[Groups(['trip:list', 'trip:item'])]
    #[MaxDepth(1)]
    private Collection $albumElements;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    private ?string $link = null;

    #[Pure] public function __construct()
    {
        $this->album = new Album();
        $this->costs = new ArrayCollection();
        $this->toDoLists = new ArrayCollection();
        $this->pointsOfInterest = new ArrayCollection();
        $this->steps = new ArrayCollection();
        $this->travels = new ArrayCollection();
        $this->tripUsers = new ArrayCollection();
        $this->albumElements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $tripUser->setTrip($this);
        }

        return $this;
    }

    public function removeTripUser(TripUser $tripUser): self
    {
        if ($this->tripUsers->removeElement($tripUser)) {
            // set the owning side to null (unless already changed)
            if ($tripUser->getTrip() === $this) {
                $tripUser->setTrip(null);
            }
        }

        return $this;
    }

    public function getUsers(): array
    {
        $travelers = [];
        foreach($this->getTripUsers() as $tripUser) {
            $travelers[] = $tripUser->getUser();
        }
        return $travelers;
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
            $albumElement->setTrip($this);
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

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function generateLink(): void
    {
        $length = 12;
        $this->setLink(substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length));
    }
}
