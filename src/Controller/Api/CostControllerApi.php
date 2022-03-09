<?php

namespace App\Controller\Api;

use App\Repository\CostRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Cost;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class CostControllerForApi
 * @package App\Controller\Api
 * @Route("/api/cost")
 */
class CostControllerApi
{
    /**
     * @Route(name="api_cost_collection_get", methods={"GET"})
     * @param CostRepository $costRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function collection(
        CostRepository $costRepository,
        SerializerInterface $serializer
    ): JsonResponse {

        return new JsonResponse(
            $serializer->serialize(
                $costRepository->findAll(),
                "json",
                ["groups" => "cost"]
            ),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_cost_item_get", methods={"GET"})
     * @param  Cost $cost
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function item(
        Cost $cost,
        SerializerInterface $serializer
    ): JsonResponse {
        return new JsonResponse(
            $serializer->serialize($cost, "json", ["groups" => "cost"]),
            Response::HTTP_OK,
            [],
            true
        );
    }
}