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
        'pictures' => [
            'method' => 'GET',
            'route_name' => 'pictures_by_album',
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
    private $id;

    #[ORM\OneToOne(inversedBy: 'album', targetEntity: Trip::class, cascade: ['persist'])]
    #[Groups(['album:list', 'album:item'])]
    private ?Trip $trip;

    #[ORM\OneToMany(mappedBy: 'album', targetEntity: Picture::class, cascade: ['persist', 'remove'])]
    #[Groups(['album:list', 'album:item'])]

    private Collection $pictures;

    #[Pure] public function __construct()
    {
        $this->pictures = new ArrayCollection();
    }

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
     * @return Collection<int, Picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setAlbum($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getAlbum() === $this) {
                $picture->setAlbum(null);
            }
        }

        return $this;
    }
}
