<?php

namespace App\Controller\Api;

use App\Repository\ItineraryRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Itinerary;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ItineraryControllerForApi
 * @package App\Controller\Api
 * @Route("/api/itinerary")
 */
class ItineraryControllerApi
{
    /**
     * @Route(name="api_itinerary_collection_get", methods={"GET"})
     * @param ItineraryRepository $itineraryRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function collection(
        ItineraryRepository $itineraryRepository,
        SerializerInterface $serializer
    ): JsonResponse {

        return new JsonResponse(
            $serializer->serialize(
                $itineraryRepository->findAll(),
                "json",
                ["groups" => "itinerary"]
            ),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_itinerary_item_get", methods={"GET"})
     * @param  Itinerary $itinerary
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function item(
        Itinerary $itinerary,
        SerializerInterface $serializer
    ): JsonResponse {
        return new JsonResponse(
            $serializer->serialize($itinerary, "json", ["groups" => "itinerary"]),
            Response::HTTP_OK,
            [],
            true
        );
    }
}