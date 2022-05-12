<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Controller\DocumentController;
use App\Controller\GetDocument;
use Symfony\Component\HttpFoundation\File\File;


/**
 * @Vich\Uploadable
 */
#[ORM\Entity(repositoryClass: DocumentRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => 'document:list']],
        'post' => [
            'controller' => DocumentController::class,
            'deserialize' => false,
            'validation_groups' => ['Default', 'document'],
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
                                    'mapElement' => [
                                        'type' => 'mapElementId',
                                        'format' => 'int'
                                    ],
                                    'name' => [
                                        'type' => 'fileName',
                                        'format' => 'string'
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
        'get' => ['normalization_context' => ['groups' => 'document:item']],
        'getDocument' => [
            'controller' => GetDocument::class,
            'path' => '/documents/file/{id}',
            'method' => 'GET',
            'read' => false
        ],
        'delete'
    ],
    normalizationContext: ['groups' => ['picture:read']],
    paginationEnabled: false,
)]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['document:list', 'document:item', 'picture:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['document:list', 'document:item', 'picture:read'])]
    private ?string $name;


    #[ORM\ManyToOne(targetEntity: MapElement::class, inversedBy: 'documents')]
    #[Groups(['document:list', 'document:item', 'picture:read'])]
    private ?MapElement $mapElement;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $filePath = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'documents')]
    #[Groups(['document:list', 'document:item', 'document:read'])]
    private ?User $creator;

    /**
     * @Vich\UploadableField(mapping="document", fileNameProperty="filePath")
     */
    #[Assert\NotNull(groups: ['document_create'])]
    public ?File $file = null;

    public function __construct(User $creator, MapElement $mapElement, string $name){
        $this->creator = $creator;
        $this->mapElement = $mapElement;
        $this->name = $name;
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


    public function getMapElement(): ?MapElement
    {
        return $this->mapElement;
    }

    public function setMapElement(?MapElement $mapElement): self
    {
        $this->mapElement = $mapElement;

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
