<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TaskRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => 'task:list']],
        'new' => [
            'method' => 'POST',
            'route_name' => 'task_new',
            'openapi_context' => [
                'summary'     => 'Create a task',
                'description' => "Create a task and add it to a ToDoList",
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type' => 'object',
                                'properties' =>
                                    [
                                        'name' => ['type' => 'string'],
                                        'description' => ['type' => 'string'],
                                        'creator' => ['type' => 'int'],
                                        'toDoList' => ['type' => 'int'],
                                        'date' => ['type' => 'string'],
                                    ],
                            ],
                            'example' => [
                                'name' => "Intitulé de la tâche",
                                'description' => "Courte description",
                                'creator' => 1,
                                'toDoList' => 1,
                                'date' => "01-09-1998 16:30",
                            ],
                        ],
                    ],
                ],
            ],
        ]
    ],
    itemOperations: [
        'get' => ['normalization_context' => ['groups' => 'task:item']],
        'edit' => [
            'method' => 'PUT',
            'route_name' => 'task_edit',
            'openapi_context' => [
                'summary'     => 'Edit a task',
                'description' => "Edit a task",
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type' => 'object',
                                'properties' =>
                                    [
                                        'name' => ['type' => 'string'],
                                        'description' => ['type' => 'string'],
                                        'date' => ['type' => 'string'],
                                        'isDone' => ['type' => 'bool']
                                    ],
                            ],
                            'example' => [
                                'name' => "Intitulé de la tâche",
                                'description' => "Courte description",
                                'date' => "01-09-1998 16:30",
                                'isDone' => false
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'delete'
    ],
    paginationEnabled: false,
)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['task:list', 'task:item', 'trip:item', 'toDoList:list', 'toDoList:item'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['task:list', 'task:item', 'trip:item', 'toDoList:list', 'toDoList:item'])]
    private ?string $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['task:list', 'task:item', 'trip:item', 'toDoList:list', 'toDoList:item'])]
    private ?string $description;

    #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'], inversedBy: 'tasks')]
    #[Groups(['task:list', 'task:item', 'trip:item', 'toDoList:list', 'toDoList:item'])]
    private ?User $creator;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['task:list', 'task:item'])]
    private ?DateTimeImmutable $creationDate;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['task:list', 'task:item', 'trip:item', 'toDoList:list', 'toDoList:item'])]
    private ?DateTimeInterface $date;

    #[ORM\ManyToOne(targetEntity: ToDoList::class, cascade: ['persist'], inversedBy: 'tasks')]
    #[Groups(['task:list', 'task:item'])]
    private ?ToDoList $toDoList;

    #[ORM\Column(type: 'boolean')]
    private ?bool $isDone = false;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
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

    public function getCreationDate(): ?DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function __construct(){
        $this->creationDate = new DateTimeImmutable('now');
    }

    public function getToDoList(): ?ToDoList
    {
        return $this->toDoList;
    }

    public function setToDoList(?ToDoList $toDoList): self
    {
        $this->toDoList = $toDoList;

        return $this;
    }

    public function getIsDone(): ?bool
    {
        return $this->isDone;
    }

    public function setIsDone(bool $isDone): self
    {
        $this->isDone = $isDone;

        return $this;
    }

    public function setCreationDate(\DateTimeImmutable $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }
}
