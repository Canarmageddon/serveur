<?php

namespace App\Controller;

use App\Dto\TravelInput;
use App\Entity\Step;
use App\Entity\Travel;
use App\Entity\ToDoList;
use App\Entity\Trip;
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
class TravelController extends AbstractController
{
    #[Route('/api/travel/new', name: 'travel_new', methods: 'POST')]
    public function new(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer): Response
    {
        try {
            $data = $request->getContent();
            /** @var TravelInput $travelInput */
            $travelInput = $serializer->deserialize($data, TravelInput::class, 'json');
            $travel = new Travel();
            $travel->setDuration($travelInput->getDuration());

            /** @var Trip $trip */
            $trip = $entityManager->getRepository(Trip::class)->find($travelInput->getTrip());
            $trip?->addTravel($travel);

            /** @var Step $start */
            $start = $entityManager->getRepository(Step::class)->find($travelInput->getStart());
            $start?->addStart($travel);

            /** @var Step $end */
            $end = $entityManager->getRepository(Step::class)->find($travelInput->getEnd());
            $end?->addEnd($travel);

            $entityManager->persist($travel);
            $entityManager->flush();

            return $this->json($travel, 201, [], ['groups' => 'travel:item']);
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
