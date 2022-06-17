<?php

namespace App\Entity;

use App\Repository\MapElementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MapElementRepository::class)]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name:"type", type: "string")]
#[ORM\DiscriminatorMap([
    "point_of_interest" => "PointOfInterest",
    "step" => "Step",
    "travel" => "Travel"
])]
abstract class MapElement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['pointOfInterest:list', 'pointOfInterest:item', 'step:list', 'step:item', 'travel:list', 'travel:item', 'trip:list', 'trip:item'])]
    private ?int $id = null;

    /**
     * @var string|null
     */
    private ?string $type;

    #[ORM\OneToMany(mappedBy: 'mapElement', targetEntity: Document::class)]
    #[Groups(['pointOfInterest:list', 'pointOfInterest:item', 'step:list', 'step:item', 'travel:list', 'travel:item', 'trip:list', 'trip:item'])]
    private Collection $documents;

    #[ORM\OneToMany(mappedBy: 'mapElement', targetEntity: Task::class)]
    #[Groups(['pointOfInterest:list', 'pointOfInterest:item', 'step:list', 'step:item', 'travel:list', 'travel:item', 'trip:list', 'trip:item'])]
    private Collection $tasks;

    #[Pure] public function __construct()
    {
        $this->documents = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): self
    {
        $this->type = $type;

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
            $document->setMapElement($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getMapElement() === $this) {
                $document->setMapElement(null);
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
            $task->setMapElement($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getMapElement() === $this) {
                $task->setMapElement(null);
            }
        }

        return $this;
    }
}
