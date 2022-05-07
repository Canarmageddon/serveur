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
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => 'document:list']]
    ],
    itemOperations: [
        'get' => ['normalization_context' => ['groups' => 'document:item']],
        'delete'
    ],
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

    #[ORM\ManyToOne(targetEntity: MapElement::class, inversedBy: 'documents')]
    private ?MapElement $mapElement;

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

    public function getStep(): ?Step
    {
        return $this->step;
    }

    public function setStep(?Step $step): self
    {
        $this->step = $step;

        return $this;
    }

    public function getMapElement(): ?MapElement
    {
        return $this->mapElement;
    }

    public function setMapElement(?MapElement $mapElement): self
    {
        $this->mapElement = $mapElement;

        return $this;
    }
}
