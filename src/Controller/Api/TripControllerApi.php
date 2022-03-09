<?php

namespace App\Controller\Api;

use App\Repository\TripRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Trip;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class TripControllerForApi
 * @package App\Controller\Api
 * @Route("/api/trip")
 */
class TripControllerApi
{
    /**
     * @Route(name="api_trip_collection_get", methods={"GET"})
     * @param TripRepository $tripRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function collection(
        TripRepository $tripRepository,
        SerializerInterface $serializer
    ): JsonResponse {

        return new JsonResponse(
            $serializer->serialize(
                $tripRepository->findAll(),
                "json",
                ["groups" => "trip"]
            ),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_trip_item_get", methods={"GET"})
     * @param  Trip $trip
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function item(
        Trip $trip,
        SerializerInterface $serializer
    ): JsonResponse {
        return new JsonResponse(
            $serializer->serialize($trip, "json", ["groups" => "trip"]),
            Response::HTTP_OK,
            [],
            true
        );
    }
}