<?php

namespace App\Controller\Api;

use App\Repository\PointOfInterestRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
}