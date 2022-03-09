<?php

namespace App\Controller\Api;

use App\Repository\AlbumRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Album;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class AlbumControllerForApi
 * @package App\Controller\Api
 * @Route("/api/album")
 */
class AlbumControllerApi
{
    /**
     * @Route(name="api_album_collection_get", methods={"GET"})
     * @param AlbumRepository $albumRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function collection(
        AlbumRepository $albumRepository,
        SerializerInterface $serializer
    ): JsonResponse {

        return new JsonResponse(
            $serializer->serialize(
                $albumRepository->findAll(),
                "json",
                ["groups" => "album"]
            ),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_album_item_get", methods={"GET"})
     * @param  Album $album
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function item(
        Album $album,
        SerializerInterface $serializer
    ): JsonResponse {
        return new JsonResponse(
            $serializer->serialize($album, "json", ["groups" => "album"]),
            Response::HTTP_OK,
            [],
            true
        );
    }
}