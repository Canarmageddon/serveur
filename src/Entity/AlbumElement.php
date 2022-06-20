<?php

namespace App\Entity;

use App\Repository\AlbumElementRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AlbumElementRepository::class)]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\DiscriminatorMap([
    "log_book_entry" => "LogBookEntry",
    "picture" => "Picture"
])]
abstract class AlbumElement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['albumElement:list', 'albumElement:item', 'logBookEntry:list', 'logBookEntry:item', 'picture:list', 'picture:item', 'picture:read', 'trip:list', 'trip:item', 'location:list', 'location:item'])]
    private ?int $id = null;

    private ?string $type;

    #[ORM\ManyToOne(targetEntity: Album::class, inversedBy: 'albumElements')]
    #[Groups(['albumElement:list', 'albumElement:item', 'logBookEntry:list', 'logBookEntry:item', 'picture:list', 'picture:item', 'picture:read', 'picture:write', 'trip:list', 'trip:item'])]
    private ?Album $album = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['albumElement:list', 'albumElement:item', 'logBookEntry:list', 'logBookEntry:item', 'picture:list', 'picture:item', 'trip:list', 'trip:item', 'location:list', 'location:item'])]
    private ?DateTimeImmutable $creationDate;

    #[ORM\ManyToOne(targetEntity: Trip::class, inversedBy: 'albumElements')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['albumElement:list', 'albumElement:item', 'logBookEntry:list', 'logBookEntry:item', 'picture:list', 'picture:item', 'picture:read'])]
    private ?Trip $trip;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'albumElements')]
    #[Groups(['albumElement:list', 'albumElement:item', 'logBookEntry:list', 'logBookEntry:item', 'picture:list', 'picture:item', 'picture:read', 'trip:list', 'trip:item', 'location:list', 'location:item'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $creator;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'albumElements')]
    #[Groups(['albumElement:list', 'albumElement:item', 'logBookEntry:list', 'logBookEntry:item', 'picture:list', 'picture:item', 'picture:read', 'picture:write'])]
    private ?Location $location;

    #[ORM\Column(type: 'string', length: 30)]
    #[Groups(['albumElement:list', 'albumElement:item', 'logBookEntry:list', 'logBookEntry:item', 'picture:list', 'picture:item', 'picture:read', 'picture:write', 'location:list', 'location:item'])]
    private ?string $type2;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getCreationDate(): ?DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function setCreationDate(DateTimeImmutable $creationDate): self
    {
        $this->creationDate = $creationDate;

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

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
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

    public function getType2(): ?string
    {
        return $this->type2;
    }

    public function setType2(string $type2): self
    {
        $this->type2 = $type2;

        return $this;
    }

    public function __construct()
    {
        $this->creationDate = new DateTimeImmutable('now');
    }
}