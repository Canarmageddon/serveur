<?php

namespace App\Controller\Api;

use App\Repository\PictureRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Picture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class PictureControllerForApi
 * @package App\Controller\Api
 * @Route("/api/picture")
 */
class PictureControllerApi
{
    /**
     * @Route(name="api_picture_collection_get", methods={"GET"})
     * @param PictureRepository $pictureRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function collection(
        PictureRepository $pictureRepository,
        SerializerInterface $serializer
    ): JsonResponse {

        return new JsonResponse(
            $serializer->serialize(
                $pictureRepository->findAll(),
                "json",
                ["groups" => "picture"]
            ),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_picture_item_get", methods={"GET"})
     * @param  Picture $picture
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function item(
        Picture $picture,
        SerializerInterface $serializer
    ): JsonResponse {
        return new JsonResponse(
            $serializer->serialize($picture, "json", ["groups" => "picture"]),
            Response::HTTP_OK,
            [],
            true
        );
    }
}