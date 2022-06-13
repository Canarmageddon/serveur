<?php

namespace App\Controller;

use App\Dto\TravelInput;
use App\Entity\Step;
use App\Entity\Travel;
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
class TravelController extends AbstractController
{
    #[Route('/api/travel/{id}/documents', name: 'documents_by_travel', methods: 'GET')]
    public function documents(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Travel $travel */
        $travel = $entityManager->getRepository(Travel::class)->find($id);
        if ($travel != null) {
            return $this->json($travel->getDocuments(), 200, [], ['groups' => 'document:item']);
        } else {
            return $this->json([
                'message' => 'Travel ' . $id . ' not found',
            ], 404);
        }
    }

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

    #[Route('/api/travel/{id}/edit', name: 'travel_edit', methods: 'PUT')]
    public function edit(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, int $id): Response
    {
        try {
            $data = $request->getContent();
            /** @var TravelInput $travelInput */
            $travelInput = $serializer->deserialize($data, TravelInput::class, 'json');
            /** @var Travel $travel */
            $travel = $entityManager->getRepository(Travel::class)->find($id);
            if ($travel == null) {
                return $this->json([
                    'status' => 400,
                    'message' => "Travel " . $id . " not found"
                ], 400);
            }

            if ($travelInput->getDuration() != null) {
                $travel->setDuration($travelInput->getDuration());
            }

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
