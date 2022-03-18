<?php

namespace App\Controller\Api;

use App\Repository\ToDoListRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ToDoList;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ToDoListControllerForApi
 * @package App\Controller\Api
 * @Route("/api/to_do_list")
 */
class ToDoListControllerApi
{
    /**
     * @Route(name="api_to_do_list_collection_get", methods={"GET"})
     * @param ToDoListRepository $toDoListRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function collection(
        ToDoListRepository $toDoListRepository,
        SerializerInterface $serializer
    ): JsonResponse {

        return new JsonResponse(
            $serializer->serialize(
                $toDoListRepository->findAll(),
                "json",
                ["groups" => "to_do_list"]
            ),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_to_do_list_item_get", methods={"GET"})
     * @param  ToDoList $toDoList
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function item(
        ToDoList $toDoList,
        SerializerInterface $serializer
    ): JsonResponse {
        return new JsonResponse(
            $serializer->serialize($toDoList, "json", ["groups" => "to_do_list"]),
            Response::HTTP_OK,
            [],
            true
        );
    }
}