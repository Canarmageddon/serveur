<?php

namespace App\Controller;

use App\Dto\TaskInput;
use App\Entity\Task;
use App\Entity\ToDoList;
use App\Entity\User;
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
    #[Route('/api/tasks/new', name: 'task_new', methods: 'POST')]
    public function new(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer): Response
    {
        try {
            $data = $request->getContent();
            /** @var TaskInput $taskInput */
            $taskInput = $serializer->deserialize($data, TaskInput::class, 'json');
            $task = new Task();
            $task->setDescription($taskInput->getDescription());
            $task->setName($taskInput->getName());
            $task->setDate($taskInput->getDate());

            /** @var ToDoList $toDoList */
            $toDoList = $entityManager->getRepository(ToDoList::class)->find($taskInput->getToDoList());
            $toDoList?->addTask($task);

            /** @var User $creator */
            $creator = $entityManager->getRepository(User::class)->find($taskInput->getCreator());
            $creator?->addTask($task);


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

    #[Route('/api/tasks/{id}/edit', name: 'task_edit', methods: 'PUT')]
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
            if ($taskInput->getName() != null) {
                $task->setName($taskInput->getName());
            }
            if ($taskInput->getDescription() != null) {
                $task->setDescription($taskInput->getDescription());
            }
            if ($taskInput->getDate() != null) {
                $task->setDate($taskInput->getDate());
            }
            if ($taskInput->getIsDone() != null) {
                $task->setIsDone($taskInput->getIsDone());
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
