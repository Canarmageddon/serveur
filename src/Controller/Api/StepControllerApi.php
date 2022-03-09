<?php

namespace App\Controller\Api;

use App\Repository\StepRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Step;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class StepControllerForApi
 * @package App\Controller\Api
 * @Route("/api/step")
 */
class StepControllerApi
{
    /**
     * @Route(name="api_step_collection_get", methods={"GET"})
     * @param StepRepository $stepRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function collection(
        StepRepository $stepRepository,
        SerializerInterface $serializer
    ): JsonResponse {

        return new JsonResponse(
            $serializer->serialize(
                $stepRepository->findAll(),
                "json",
                ["groups" => "step"]
            ),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_step_item_get", methods={"GET"})
     * @param  Step $step
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function item(
        Step $step,
        SerializerInterface $serializer
    ): JsonResponse {
        return new JsonResponse(
            $serializer->serialize($step, "json", ["groups" => "step"]),
            Response::HTTP_OK,
            [],
            true
        );
    }
}