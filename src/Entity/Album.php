<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]

#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => 'album:list']],
        'new' => [
            'method' => 'POST',
            'route_name' => 'album_new',
            'openapi_context' => [
                'summary'     => 'Create an album',
                'description' => "Create an album and add it to a Trip",
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type' => 'object',
                                'properties' =>
                                    [
                                        'trip' => ['type' => 'int'],
                                    ],
                            ],
                            'example' => [
                                'trip' => 1,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    itemOperations: [
        'get' => ['normalization_context' => ['groups' => 'album:item']],
        'data' => [
            'method' => 'GET',
            'route_name' => 'album_elements_by_album',
            "order" => ["creationDate" => "ASC"]
        ],
        'pictures' => [
            'method' => 'GET',
            'route_name' => 'pictures_by_album',
            "order" => ["creationDate" => "ASC"]
        ],
        'logBookEntries' => [
            'method' => 'GET',
            'route_name' => 'log_book_entries_by_album',
            "order" => ["creationDate" => "ASC"]
        ],
        'delete'
    ],
    paginationEnabled: false,
)]
class Album
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['album:list', 'album:item', 'picture:read'])]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'album', targetEntity: Trip::class, cascade: ['persist'])]
    #[Groups(['album:list', 'album:item'])]
    private ?Trip $trip;

    #[ORM\OneToMany(mappedBy: 'album', targetEntity: AlbumElement::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups(['album:list', 'album:item'])]
    private Collection $albumElements;

    public function getId(): ?int
    {
        return $this->id;
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
            $albumElement->setAlbum($this);
        }

        return $this;
    }

    public function removeAlbumElement(AlbumElement $albumElement): self
    {
        if ($this->albumElements->removeElement($albumElement)) {
            // set the owning side to null (unless already changed)
            if ($albumElement->getAlbum() === $this) {
                $albumElement->setAlbum(null);
            }
        }

        return $this;
    }

    #[Pure] public function __construct()
    {
        $this->albumElements = new ArrayCollection();
    }
}
