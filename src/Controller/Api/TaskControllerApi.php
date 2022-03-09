<?php

namespace App\Controller\Api;

use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class TaskControllerForApi
 * @package App\Controller\Api
 * @Route("/api/task")
 */
class TaskControllerApi
{
    /**
     * @Route(name="api_task_collection_get", methods={"GET"})
     * @param TaskRepository $taskRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function collection(
        TaskRepository $taskRepository,
        SerializerInterface $serializer
    ): JsonResponse {

        return new JsonResponse(
            $serializer->serialize(
                $taskRepository->findAll(),
                "json",
                ["groups" => "task"]
            ),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_task_item_get", methods={"GET"})
     * @param  Task $task
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function item(
        Task $task,
        SerializerInterface $serializer
    ): JsonResponse {
        return new JsonResponse(
            $serializer->serialize($task, "json", ["groups" => "task"]),
            Response::HTTP_OK,
            [],
            true
        );
    }
}