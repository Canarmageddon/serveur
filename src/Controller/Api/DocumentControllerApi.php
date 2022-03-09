<?php

namespace App\Controller\Api;

use App\Repository\DocumentRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Document;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class DocumentControllerForApi
 * @package App\Controller\Api
 * @Route("/api/document")
 */
class DocumentControllerApi
{
    /**
     * @Route(name="api_document_collection_get", methods={"GET"})
     * @param DocumentRepository $documentRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function collection(
        DocumentRepository $documentRepository,
        SerializerInterface $serializer
    ): JsonResponse {

        return new JsonResponse(
            $serializer->serialize(
                $documentRepository->findAll(),
                "json",
                ["groups" => "document"]
            ),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_document_item_get", methods={"GET"})
     * @param  Document $document
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function item(
        Document $document,
        SerializerInterface $serializer
    ): JsonResponse {
        return new JsonResponse(
            $serializer->serialize($document, "json", ["groups" => "document"]),
            Response::HTTP_OK,
            [],
            true
        );
    }
}