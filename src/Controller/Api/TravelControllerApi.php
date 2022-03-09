<?php

namespace App\Controller\Api;

use App\Repository\TravelRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Travel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class TravelControllerForApi
 * @package App\Controller\Api
 * @Route("/api/travel")
 */
class TravelControllerApi
{
    /**
     * @Route(name="api_travel_collection_get", methods={"GET"})
     * @param TravelRepository $travelRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function collection(
        TravelRepository $travelRepository,
        SerializerInterface $serializer
    ): JsonResponse {

        return new JsonResponse(
            $serializer->serialize(
                $travelRepository->findAll(),
                "json",
                ["groups" => "travel"]
            ),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_travel_item_get", methods={"GET"})
     * @param  Travel $travel
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function item(
        Travel $travel,
        SerializerInterface $serializer
    ): JsonResponse {
        return new JsonResponse(
            $serializer->serialize($travel, "json", ["groups" => "travel"]),
            Response::HTTP_OK,
            [],
            true
        );
    }
}