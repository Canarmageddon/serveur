<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\PictureController;
use App\Controller\GetPicture;
use App\Repository\PictureRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @Vich\Uploadable
 */
#[ORM\Entity(repositoryClass: PictureRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get',
        'post' => [
            'controller' => PictureController::class,
            'deserialize' => false,
            'validation_groups' => ['Default', 'picture'],
            'openapi_context' => [
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'file' => [
                                        'type' => 'file',
                                        'format' => 'binary',
                                    ],
                                    'creator' => [
                                        'type' => 'creatorId',
                                        'format' => 'int'
                                    ],
                                    'album' => [
                                        'type' => 'albumId',
                                        'format' => 'int'
                                    ],
                                    'location' => [
                                        'type' => 'locationId',
                                        'format' => 'int'
                                    ]
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    itemOperations: ['get' => [],
        'getPicture' => [
            'controller' => GetPicture::class,
            'path' => '/pictures/file/{id}',
            'method' => 'GET',
            'read' => false
        ]
    ],
    normalizationContext: ['groups' => ['picture:read']],
    order: ['album' => 'ASC'],
    paginationEnabled: false,
)]
class Picture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['picture:list', 'picture:item', 'picture:read'])]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'pictures')]
    #[Groups(['picture:list', 'picture:item', 'picture:read'])]
    private ?User $creator;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['picture:list', 'picture:item'])]
    private ?DateTimeImmutable $creationDate;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'pictures')]
    #[Groups(['picture:list', 'picture:item', 'picture:read'])]
    private ?Location $location;

    #[ORM\ManyToOne(targetEntity: Album::class, inversedBy: 'pictures')]
    #[Groups(['picture:list', 'picture:item', 'picture:read'])]
    private ?Album $album;


    /**
     * @Vich\UploadableField(mapping="picture", fileNameProperty="filePath")
     */
    #[Assert\NotNull(groups: ['picture_create'])]
    public ?File $file = null;

    #[ORM\Column(nullable: true)] 
    #[Groups(['picture:read'])]
    public ?string $filePath = null;

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

    public function __construct(User $creator, Location $location, Album $album){
        $this->creationDate = new DateTimeImmutable('now');
        $this->creator = $creator;
        $this->location = $location;
        $this->album = $album;
    }

    public function setCreationDate(\DateTimeImmutable $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }
}
