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
    #[Route('/api/toDoList/new', name: 'toDoList_new', methods: 'POST')]
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
}
