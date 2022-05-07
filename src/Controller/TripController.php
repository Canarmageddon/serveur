<?php

namespace App\Controller;

use App\Dto\TripDto\UserInput;
use App\Entity\Location;
use App\Entity\Trip;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

class TripController extends AbstractController
{
    #[Route('/api/trips/{id}/costs', name: 'costs_by_trip', methods: 'GET')]
    public function costs(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Trip $trip */
        $trip = $entityManager->getRepository(Trip::class)->find($id);
        if ($trip != null) {
            return $this->json($trip->getCosts(), 200, [], ['groups' => 'cost:item']);
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
            return $this->json($trip->getPointsOfInterest(), 200, [], ['groups' => 'pointOfInterest:item']);
        } else {
            return $this->json([
                'message' => 'Trip ' . $id . ' not found',
            ], 404);
        }
    }

    #[Route('/api/trips/{id}/steps', name: 'steps_by_trip', methods: 'GET')]
    public function steps(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Trip $trip */
        $trip = $entityManager->getRepository(Trip::class)->find($id);
        if ($trip != null) {
            return $this->json($trip->getSteps(), 200, [], ['groups' => 'step:item']);
        } else {
            return $this->json([
                'message' => 'Trip ' . $id . ' not found',
            ], 404);
        }
    }

    #[Route('/api/trips/{id}/travels', name: 'travels_by_trip', methods: 'GET')]
    public function travels(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Trip $trip */
        $trip = $entityManager->getRepository(Trip::class)->find($id);
        if ($trip != null) {
            return $this->json($trip->getTravels(), 200, [], ['groups' => 'travel:item']);
        } else {
            return $this->json([
                'message' => 'Trip ' . $id . ' not found',
            ], 404);
        }
    }

    #[Route('/api/trips/{id}/to_do_lists', name: 'to_do_lists_by_trip', methods: 'GET')]
    public function toDoLists(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Trip $trip */
        $trip = $entityManager->getRepository(Trip::class)->find($id);
        if ($trip != null) {
            return $this->json($trip->getToDoLists(), 200, [], ['groups' => 'toDoList:item']);
        } else {
            return $this->json([
                'message' => 'Trip ' . $id . ' not found',
            ], 404);
        }
    }

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

    #[Route('/api/trips/addUser', name: 'trip_add_user', methods: 'POST')]
    public function addUser(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer): Response
    {
        try {
            $data = $request->getContent();
            /** @var UserInput $userInput */
            $userInput = $serializer->deserialize($data, UserInput::class, 'json');
            $emailUser = $userInput->getEmail();
            $idTrip = $userInput->getTrip();
            /** @var User $user */
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $emailUser]);
            /** @var Trip $trip */
            $trip = $entityManager->getRepository(Trip::class)->find($idTrip);

            if($user != null && $trip != null) {
                //Vérifier si l'user est déjà dans le trip, sinon, l'ajouter et flush
                if (!$trip->getTravelers()->contains($user)) {
                    $trip->addTraveler($user);
                    $entityManager->persist($trip);
                    $entityManager->flush();
                    return $this->json([
                        'message' => 'User ' . $emailUser . ' added to Trip ' . $idTrip,
                    ], 202);

                } else {
                    return $this->json([
                        'message' => 'User ' . $emailUser . ' already member of Trip ' . $idTrip,
                    ], 200);
                }

            } elseif ($user == null && $trip == null) {
                return $this->json([
                    'message' => 'Trip ' . $idTrip . ' and User ' . $emailUser . ' not found',
                ], 404);

            } elseif ($user == null) {
                return $this->json([
                    'message' => 'User ' . $emailUser . ' not found',
                ], 404);

            } else {
                return $this->json([
                    'message' => 'Trip ' . $idTrip . ' not found',
                ], 404);
            }
        }
        catch (NotEncodableValueException $e)
        {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    #[Route('/api/trips/removeUser', name: 'trip_remove_user', methods: 'POST')]
    public function removeUser(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer): Response
    {
        try {
            $data = $request->getContent();
            /** @var UserInput $userInput */
            $userInput = $serializer->deserialize($data, UserInput::class, 'json');
            $emailUser = $userInput->getEmail();
            $idTrip = $userInput->getTrip();
            /** @var User $user */
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $emailUser]);
            /** @var Trip $trip */
            $trip = $entityManager->getRepository(Trip::class)->find($idTrip);

            if($user != null && $trip != null) {
                //Vérifier si l'user est déjà dans le trip, si oui, l'enlever et flush
                if ($trip->getTravelers()->contains($user)) {
                    $trip->removeTraveler($user);
                    $entityManager->persist($trip);
                    $entityManager->flush();
                    return $this->json([
                        'message' => 'User ' . $emailUser . ' removed from ' . $idTrip . ' Trip',
                    ], 202);

                } else {
                    return $this->json([
                        'message' => 'User ' . $emailUser . ' already member of ' . $idTrip . ' Trip',
                    ], 200);
                }

            } elseif ($user == null && $trip == null) {
                return $this->json([
                    'message' => 'Trip ' . $idTrip . ' and User ' . $emailUser . ' not found',
                ], 404);

            } elseif ($user == null) {
                return $this->json([
                    'message' => 'User ' . $emailUser . ' not found',
                ], 404);

            } else {
                return $this->json([
                    'message' => 'Trip ' . $idTrip . ' not found',
                ], 404);
            }
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
