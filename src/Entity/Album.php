<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]

#[ApiResource(
    collectionOperations: ['get' => ['normalization_context' => ['groups' => 'album:list']]],
    itemOperations: [
        'get' => ['normalization_context' => ['groups' => 'album:item']],
        'delete'
    ],
    paginationEnabled: false,
)]
class Album
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['album:list', 'album:item'])]
    private $id;

    #[ORM\OneToOne(inversedBy: 'album', targetEntity: Trip::class, cascade: ['persist'])]
    #[Groups(['album:list', 'album:item'])]
    private ?Trip $trip;

    #[ORM\OneToMany(mappedBy: 'album', targetEntity: Picture::class, cascade: ['persist', 'remove'])]
    #[Groups(['album:list', 'album:item'])]

    private Collection $pictures;

    public function __construct()
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
