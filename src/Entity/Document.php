<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
#[ApiResource(
    collectionOperations: ['get' => ['normalization_context' => ['groups' => 'document:list']]],
    itemOperations: ['get' => ['normalization_context' => ['groups' => 'document:item']]],
    paginationEnabled: false,
)]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['document:list', 'document:item'])]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['document:list', 'document:item'])]
    private ?string $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['document:list', 'document:item'])]
    private ?string $route;

    #[ORM\ManyToOne(targetEntity: PointOfInterest::class, inversedBy: 'documents')]
    #[Groups(['document:list', 'document:item'])]
    private ?PointOfInterest $pointOfInterest;

    #[ORM\OneToMany(mappedBy: 'documents', targetEntity: Step::class)]
    #[Groups(['document:list', 'document:item'])]
    private Collection $steps;

    #[Pure] public function __construct()
    {
        $this->steps = new ArrayCollection();
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

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function getPointOfInterest(): ?PointOfInterest
    {
        return $this->pointOfInterest;
    }

    public function setPointOfInterest(?PointOfInterest $pointOfInterest): self
    {
        $this->pointOfInterest = $pointOfInterest;

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
            $step->setDocuments($this);
        }

        return $this;
    }

    public function removeStep(Step $step): self
    {
        if ($this->steps->removeElement($step)) {
            // set the owning side to null (unless already changed)
            if ($step->getDocuments() === $this) {
                $step->setDocuments(null);
            }
        }

        return $this;
    }
}
