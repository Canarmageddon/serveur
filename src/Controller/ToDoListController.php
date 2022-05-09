<?php

namespace App\Controller;

use App\Dto\ToDoListInput;
use App\Entity\ToDoList;
use App\Entity\Trip;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class ToDoListController extends AbstractController
{
    #[Route('/api/to_do_lists/{id}/tasks', name: 'tasks_by_to_do_list', methods: 'GET')]
    public function poi(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var ToDoList $toDoList */
        $toDoList = $entityManager->getRepository(ToDoList::class)->find($id);
        if ($toDoList != null) {
            return $this->json($toDoList->getTasks(), 200, [], ['groups' => 'task:item']);
        } else {
            return $this->json([
                'message' => 'To Do List ' . $id . ' not found',
            ], 404);
        }
    }

    #[Route('/api/to_do_lists/new', name: 'to_do_list_new', methods: 'POST')]
    public function new(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer): Response
    {
        try {
            $data = $request->getContent();
            /** @var ToDoListInput $toDoListInput */
            $toDoListInput = $serializer->deserialize($data, ToDoListInput::class, 'json');
            $toDoList = new ToDoList();
            $toDoList->setName($toDoListInput->getName());

            /** @var Trip $trip */
            $trip = $entityManager->getRepository(Trip::class)->find($toDoListInput->getTrip());
            $trip?->addToDoList($toDoList);
            $entityManager->persist($toDoList);
            $entityManager->flush();

            return $this->json($toDoList, 201, [], ['groups' => 'toDoList:item']);
        }
        catch (NotEncodableValueException $e)
        {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    #[Route('/api/to_do_lists/{id}/edit', name: 'to_do_list_edit', methods: 'PUT')]
    public function edit(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, int $id): Response
    {
        try {
            $data = $request->getContent();
            /** @var ToDoListInput $toDoListInput */
            $toDoListInput = $serializer->deserialize($data, ToDoListInput::class, 'json');
            /** @var ToDoList $toDoList */
            $toDoList = $entityManager->getRepository(ToDoList::class)->find($id);
            if ($toDoList == null) {
                return $this->json([
                    'status' => 400,
                    'message' => "To Do List " . $id . " not found"
                ], 400);
            }

            if ($toDoListInput->getName() != null) {
                $toDoList->setName($toDoListInput->getName());
            }

            $entityManager->persist($toDoList);
            $entityManager->flush();

            return $this->json($toDoList, 201, [], ['groups' => 'toDoList:item']);
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
