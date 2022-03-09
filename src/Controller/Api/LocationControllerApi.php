<?php

namespace App\Controller\Api;

use App\Repository\LocationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Location;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class LocationControllerForApi
 * @package App\Controller\Api
 * @Route("/api/location")
 */
class LocationControllerApi
{
    /**
     * @Route(name="api_location_collection_get", methods={"GET"})
     * @param LocationRepository $locationRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function collection(
        LocationRepository $locationRepository,
        SerializerInterface $serializer
    ): JsonResponse {

        return new JsonResponse(
            $serializer->serialize(
                $locationRepository->findAll(),
                "json",
                ["groups" => "location"]
            ),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_location_item_get", methods={"GET"})
     * @param  Location $location
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function item(
        Location $location,
        SerializerInterface $serializer
    ): JsonResponse {
        return new JsonResponse(
            $serializer->serialize($location, "json", ["groups" => "location"]),
            Response::HTTP_OK,
            [],
            true
        );
    }
}