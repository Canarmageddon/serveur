<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ToDoListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ToDoListRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => 'toDoList:list']],
        'new' => [
            'method' => 'POST',
            'route_name' => 'to_do_list_new',
            'openapi_context' => [
                'summary'     => 'Create a To Do List',
                'description' => "Create a To Do List and add it to a Trip",
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type' => 'object',
                                'properties' =>
                                    [
                                        'name' => ['type' => 'string'],
                                        'trip' => ['type' => 'int'],
                                    ],
                            ],
                            'example' => [
                                'name' => "Nom de la To Do List",
                                'trip' => 1,
                            ],
                        ],
                    ],
                ],
            ],
        ]
    ],
    itemOperations: [
        'get' => ['normalization_context' => ['groups' => 'toDoList:item']],
        'tasks' => [
            'method' => 'GET',
            'route_name' => 'tasks_by_to_do_list',
        ],
        'delete'
    ],
    paginationEnabled: false,
)]
class ToDoList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['toDoList:list', 'toDoList:item', 'trip:item'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['toDoList:list', 'toDoList:item', 'trip:item'])]
    private ?string $name;

    #[ORM\OneToMany(mappedBy: 'toDoList', targetEntity: Task::class, cascade: ['persist', 'remove'])]
    #[Groups(['toDoList:list', 'toDoList:item', 'trip:item'])]
    private Collection $tasks;

    #[ORM\ManyToOne(targetEntity: Trip::class, inversedBy: 'toDoLists')]
    #[Groups(['toDoList:list', 'toDoList:item'])]
    private ?Trip $trip;

    #[Pure] public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $task->setToDoList($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getToDoList() === $this) {
                $task->setToDoList(null);
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
}
