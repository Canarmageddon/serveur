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
    collectionOperations: ['get' => ['normalization_context' => ['groups' => 'task:list']]],
    itemOperations: ['get' => ['normalization_context' => ['groups' => 'task:item']]],
    paginationEnabled: false,
)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['task:list', 'task:item', 'trip:item'])]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['task:list', 'task:item', 'trip:item'])]
    private ?string $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['task:list', 'task:item', 'trip:item'])]
    private ?string $description;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tasks')]
    #[Groups(['task:list', 'task:item', 'trip:item'])]
    private ?User $creator;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['task:list', 'task:item'])]
    private ?DateTimeImmutable $creationDate;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['task:list', 'task:item', 'trip:item'])]
    private ?DateTimeInterface $date;

    #[ORM\ManyToOne(targetEntity: ToDoList::class, inversedBy: 'tasks')]
    #[Groups(['task:list', 'task:item'])]
    private ?ToDoList $toDoList;

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
}
