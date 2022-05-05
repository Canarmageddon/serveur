<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PictureRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PictureRepository::class)]
#[ApiResource(
    collectionOperations: ['get' => ['normalization_context' => ['groups' => 'picture:list']]],
    itemOperations: [
        'get' => ['normalization_context' => ['groups' => 'picture:item']],
        'delete'
    ],
    paginationEnabled: false,
)]
class Picture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['picture:list', 'picture:item'])]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'pictures')]
    #[Groups(['picture:list', 'picture:item'])]
    private ?User $creator;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['picture:list', 'picture:item'])]
    private ?DateTimeImmutable $creationDate;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'pictures')]
    #[Groups(['picture:list', 'picture:item'])]
    private ?Location $location;

    #[ORM\ManyToOne(targetEntity: Album::class, inversedBy: 'pictures')]
    #[Groups(['picture:list', 'picture:item'])]
    private ?Album $album;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    public function setAlbum(?Album $album): self
    {
        $this->album = $album;

        return $this;
    }

    public function __construct(){
        $this->creationDate = new DateTimeImmutable('now');
    }
}
