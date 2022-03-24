<?php

namespace App\Controller\Api;

use App\Entity\Itinerary;
use App\Repository\PointOfInterestRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Location;
use App\Entity\PointOfInterest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class PointOfInterestControllerForApi
 * @package App\Controller\Api
 * @Route("/api/point_of_interest")
 */
class PointOfInterestControllerApi
{
    /**
     * @Route(name="api_point_of_interest_collection_get", methods={"GET"})
     * @param PointOfInterestRepository $pointOfInterestRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function collection(
        PointOfInterestRepository $pointOfInterestRepository,
        SerializerInterface $serializer
    ): JsonResponse {

        return new JsonResponse(
            $serializer->serialize(
                $pointOfInterestRepository->findAll(),
                "json",
                ["groups" => "pointOfInterest"]
            ),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_point_of_interest_item_get", methods={"GET"})
     * @param  PointOfInterest $pointOfInterest
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function item(
        PointOfInterest $pointOfInterest,
        SerializerInterface $serializer
    ): JsonResponse {
        return new JsonResponse(
            $serializer->serialize($pointOfInterest, "json", ["groups" => "pointOfInterest"]),
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/new/{id}/{latitude}/{longitude}', name: 'api_poi_new', methods: ['GET', 'POST'])]
    public function new(EntityManagerInterface $entityManager, int $id, float $latitude, float $longitude): Response
    {
        $pointOfInterest = new PointOfInterest();
        $location = new Location();
        $location->setLatitude($latitude);
        $location->setLongitude($longitude);
        $location->addPointOfInterest($pointOfInterest);
        $itinerary = $entityManager->getRepository(Itinerary::class)->find($id);
        $itinerary->addPointsOfInterest($pointOfInterest);
        $entityManager->persist($itinerary);
        $entityManager->persist($location);
        $entityManager->persist($pointOfInterest);
        $entityManager->flush();
        return new Response('Ajout réussi !');
    }

}