<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\Trip;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

class TripController extends AbstractController
{
    #[Route('/api/trips/new', name: 'trip_new', methods: 'POST')]
    public function new(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer): Response
    {
        try {
            $data = $request->getContent();
            $trip = $serializer->deserialize($data, Trip::class, 'json');
            $entityManager->persist($trip);
            $entityManager->flush();

            return $this->json($trip, 201, [], ['groups' => 'trip:item']);
        }
        catch (NotEncodableValueException $e)
        {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    #[Route('/api/trips/{id}/steps', name: 'steps_by_trip', methods: 'GET')]
    public function steps(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Trip $trip */
        $trip = $entityManager->getRepository(Trip::class)->find($id);
        if ($trip != null) {
            return $this->json($trip->getSteps(), 201, [], ['groups' => 'step:item']);
        } else {
            return $this->json([
                'message' => 'Trip ' . $id . ' not found',
            ], 404);
        }
    }

    #[Route('/api/trips/{id}/poi', name: 'poi_by_trip', methods: 'GET')]
    public function poi(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Trip $trip */
        $trip = $entityManager->getRepository(Trip::class)->find($id);
        if ($trip != null) {
            return $this->json($trip->getPointsOfInterest(), 201, [], ['groups' => 'pointOfInterest:item']);
        } else {
            return $this->json([
                'message' => 'Trip ' . $id . ' not found',
            ], 404);
        }
    }
}
