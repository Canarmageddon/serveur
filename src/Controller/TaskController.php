<?php

namespace App\Controller;

use App\Dto\TaskInput;
use App\Entity\MapElement;
use App\Entity\Task;
use App\Entity\ToDoList;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class TaskController extends AbstractController
{
    #[Route('/api/tasks', name: 'task_new', methods: 'POST')]
    public function new(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer): Response
    {
        try {
            $data = $request->getContent();
            /** @var TaskInput $taskInput */
            $taskInput = $serializer->deserialize($data, TaskInput::class, 'json');
            $task = new Task();
            $task->setDescription($taskInput->getDescription());
            $task->setName($taskInput->getName());
            if ($taskInput->getDate() != null && $taskInput->getDate() != "") {
                $date = DateTime::createFromFormat('d-m-Y', $taskInput->getDate());
                $task->setDate($date);
            }

            /** @var ToDoList $toDoList */
            $toDoList = $entityManager->getRepository(ToDoList::class)->find($taskInput->getToDoList());
            $toDoList?->addTask($task);

            /** @var User $creator */
            $creator = $entityManager->getRepository(User::class)->find($taskInput->getCreator());
            $creator?->addTask($task);
            //Access control
            $this->denyAccessUnlessGranted('TRIP_EDIT', $task);

            if ($taskInput->getMapElement()) {
                /** @var MapElement $mapElement */
                $mapElement = $entityManager->getRepository(MapElement::class)->find($taskInput->getMapElement());
                $mapElement?->addTask($task);
            }

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->json($task, 201, [], ['groups' => 'task:item']);
        }
        catch (NotEncodableValueException $e)
        {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    #[Route('/api/tasks/{id}', name: 'task_edit', methods: 'PUT')]
    public function edit(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, int $id): Response
    {
        try {
            $data = $request->getContent();
            /** @var TaskInput $taskInput */
            $taskInput = $serializer->deserialize($data, TaskInput::class, 'json');
            /** @var Task $task */
            $task = $entityManager->getRepository(Task::class)->find($id);
            if ($task == null) {
                return $this->json([
                    'status' => 400,
                    'message' => "Task " . $id . " not found"
                ], 400);
            }
            //Access control
            $this->denyAccessUnlessGranted('TRIP_EDIT', $task);
            if ($taskInput->getName() != null) {
                $task->setName($taskInput->getName());
            }
            if ($taskInput->getDescription() != null) {
                $task->setDescription($taskInput->getDescription());
            }
            if ($taskInput->getDate() != null && $taskInput->getDate() != "") {
                $date = DateTime::createFromFormat('d-m-Y H:i', $taskInput->getDate());
                $task->setDate($date);
            }
            if ($taskInput->getIsDone() != null) {
                $task->setIsDone($taskInput->getIsDone());
            }

            if ($taskInput->getMapElement()) {
                /** @var MapElement $mapElement */
                $mapElement = $entityManager->getRepository(MapElement::class)->find($taskInput->getMapElement());
                if ($task->getMapElement() != null && $task->getMapElement()->getTasks()->contains($task)) {
                    $task->getMapElement()->removeTask($task);
                }
                $mapElement?->addTask($task);
            }

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->json($task, 201, [], ['groups' => 'task:item']);
        }
        catch (NotEncodableValueException $e)
        {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
