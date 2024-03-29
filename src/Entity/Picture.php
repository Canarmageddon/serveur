<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\PictureController;
use App\Controller\GetPicture;
use App\Repository\PictureRepository;
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
        'get' => [],
        'post' => [
            'method' => 'POST',
            'route_name' => 'picture_new',
            'deserialize' => false,
            'validation_groups' => ['Default', 'picture'],
            'openapi_context' => [
                'security' => [['bearerAuth' => []]],
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
                                    'trip' => [
                                        'type' => 'tripId',
                                        'format' => 'int'
                                    ],
                                    'album' => [
                                        'type' => 'albumId',
                                        'format' => 'int'
                                    ],
                                    'location' => [
                                        'type' => 'locationId',
                                        'format' => 'int'
                                    ],
                                    'latitude' => [
                                        'type' => 'latitude',
                                        'format' => 'float'
                                    ],
                                    'longitude' => [
                                        'type' => 'latitude',
                                        'format' => 'float'
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    itemOperations: [
        'get' => [],
        'getPicture' => [
            'controller' => GetPicture::class,
            'path' => '/pictures/file/{id}',
            'method' => 'GET',
            'read' => false
        ],
        'edit' => [
            'method' => 'PUT',
            'route_name' => 'picture_edit',
            'openapi_context' => [
                'summary'     => 'Edit a picture',
                'security' => [['bearerAuth' => []]],
                'description' => "Edit a picture",
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema'  => [
                                'type' => 'object',
                                'properties' =>
                                    [
                                        'album' => ['type' => 'int'],
                                    ],
                            ],
                            'example' => [
                                'album' => 1,
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'delete' => [
            "security" => "is_granted('TRIP_EDIT', object)",
            'openapi_context' => [
                'security' => [['bearerAuth' => []]]
            ]
        ]
    ],
    denormalizationContext: ['groups' => ['picture:write']],
    normalizationContext: ['groups' => ['picture:read']],
    paginationEnabled: false,
)]
class Picture extends AlbumElement
{
    /**
     * @Vich\UploadableField(mapping="picture", fileNameProperty="filePath")
     */
    #[Assert\NotNull(groups: ['picture_create'])]
    public ?File $file = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['albumElement:list', 'albumElement:item', 'picture:read', 'picture:item', 'trip:item', 'album:list', 'album:item', 'location:list', 'location:item'])]
    public ?string $filePath = null;

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function __construct()
    {
        parent::__construct();
        $this->setType2('picture');
    }
}