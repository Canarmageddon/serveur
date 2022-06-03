<?php

namespace App\Controller;

use App\Dto\TripDto\TripInput;
use App\Dto\TripDto\UserInput;
use App\Entity\Trip;
use App\Entity\TripUser;
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
    #[Route('/api/trips/{id}/album', name: 'album_by_trip', methods: 'GET')]
    public function album(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Trip $trip */
        $trip = $entityManager->getRepository(Trip::class)->find($id);
        if ($trip != null) {
            return $this->json($trip->getAlbum(), 200, [], ['groups' => 'album:item']);
        } else {
            return $this->json([
                'message' => 'Trip ' . $id . ' not found',
            ], 404);
        }
    }

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

    #[Route('/api/trips/{id}/logBookEntries', name: 'log_book_entries_by_trip', methods: 'GET')]
    public function logBookEntries(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Trip $trip */
        $trip = $entityManager->getRepository(Trip::class)->find($id);
        if ($trip != null) {
            return $this->json($trip->getLogBookEntries(), 200, [], ['groups' => 'logBookEntry:item']);
        } else {
            return $this->json([
                'message' => 'Trip ' . $id . ' not found',
            ], 404);
        }
    }

    #[Route('/api/trips/{id}/pictures', name: 'pictures_by_trip', methods: 'GET')]
    public function pictures(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Trip $trip */
        $trip = $entityManager->getRepository(Trip::class)->find($id);
        if ($trip != null) {
            return $this->json($trip->getPictures(), 200, [], ['groups' => 'picture:item']);
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

    #[Route('/api/trips/{id}/toDoLists', name: 'to_do_lists_by_trip', methods: 'GET')]
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

    #[Route('/api/trips/{id}/users', name: 'users_by_trip', methods: 'GET')]
    public function users(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Trip $trip */
        $trip = $entityManager->getRepository(Trip::class)->find($id);
        if ($trip != null) {
//            return $this->json($trip->getUsers(), 200, [], ['groups' => 'user:item']);
            $travelers = [];
            $users = $trip->getUsers();
            foreach($users as $user) {
                $traveler = $entityManager->getRepository(TripUser::class)->findOneBy(['trip' => $trip->getId(), 'user' => $user->getId()]);
                if ($traveler != null) {
                    $travelers[] = $traveler;
                }
            }
            return $this->json($travelers, 200, [], ['groups' => 'user:item']);
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
            /** @var TripInput $tripInput */
            $tripInput = $serializer->deserialize($data, TripInput::class, 'json');
            $trip = new Trip();
            if ($tripInput->getName() != null) {
                $trip->setName($tripInput->getName());
            }
            if ($tripInput->getCreator() != null) {
                $creator = $entityManager->getRepository(User::class)->find($tripInput->getCreator());
                if ($creator != null) {
                    $tripUser = new TripUser();
                    $trip->addTripUser($tripUser);
                    $creator->addTripUser($tripUser);
                    $entityManager->persist($tripUser);
                }
            }
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

    #[Route('/api/trips/{id}/addUser', name: 'trip_add_user', methods: 'PUT')]
    public function addUser(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, int $id): Response
    {
        try {
            $data = $request->getContent();
            /** @var UserInput $userInput */
            $userInput = $serializer->deserialize($data, UserInput::class, 'json');
            $emailUser = $userInput->getEmail();
            /** @var User $user */
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $emailUser]);
            /** @var Trip $trip */
            $trip = $entityManager->getRepository(Trip::class)->find($id);

            if($user != null && $trip != null) {

                /** @var TripUser $tripUser */
                $tripUser = $entityManager->getRepository(TripUser::class)->findOneBy(['trip' => $trip->getId(), 'user' => $user->getId()]);

                //Vérifier si l'user est déjà dans le trip, sinon, l'ajouter et flush
                if ($tripUser == null) {
                    $tripUser = new TripUser();
                    $trip->addTripUser($tripUser);
                    $user->addTripUser($tripUser);
                    $entityManager->persist($tripUser);
                    $entityManager->flush();
                    return $this->json([
                        'message' => 'User ' . $emailUser . ' added to Trip ' . $id,
                    ], 202);

                } else {
                    return $this->json([
                        'message' => 'User ' . $emailUser . ' already member of Trip ' . $id,
                    ], 200);
                }

            } elseif ($user == null && $trip == null) {
                return $this->json([
                    'message' => 'Trip ' . $id . ' and User ' . $emailUser . ' not found',
                ], 404);

            } elseif ($user == null) {
                return $this->json([
                    'message' => 'User ' . $emailUser . ' not found',
                ], 404);

            } else {
                return $this->json([
                    'message' => 'Trip ' . $id . ' not found',
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

    #[Route('/api/trips/{id}/removeUser', name: 'trip_remove_user', methods: 'PUT')]
    public function removeUser(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, int $id): Response
    {
        try {
            $data = $request->getContent();
            /** @var UserInput $userInput */
            $userInput = $serializer->deserialize($data, UserInput::class, 'json');
            $emailUser = $userInput->getEmail();
            /** @var User $user */
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $emailUser]);
            /** @var Trip $trip */
            $trip = $entityManager->getRepository(Trip::class)->find($id);

            if($user != null && $trip != null) {

                /** @var TripUser $tripUser */
                $tripUser = $entityManager->getRepository(TripUser::class)->findOneBy(['trip' => $trip->getId(), 'user' => $user->getId()]);

                //Vérifier si l'user est déjà dans le trip, si oui, l'enlever et flush
                if ($tripUser != null) {
                    $trip->removeTripUser($tripUser);
                    $user->removeTripUser($tripUser);
                    $entityManager->remove($tripUser);
                    $entityManager->flush();
                    return $this->json([
                        'message' => 'User ' . $emailUser . ' removed from ' . $id . ' Trip',
                    ], 202);

                } else {
                    return $this->json([
                        'message' => 'User ' . $emailUser . ' already member of ' . $id . ' Trip',
                    ]);
                }

            } elseif ($user == null && $trip == null) {
                return $this->json([
                    'message' => 'Trip ' . $id . ' and User ' . $emailUser . ' not found',
                ], 404);

            } elseif ($user == null) {
                return $this->json([
                    'message' => 'User ' . $emailUser . ' not found',
                ], 404);

            } else {
                return $this->json([
                    'message' => 'Trip ' . $id . ' not found',
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

    #[Route('/api/trips/{id}/edit', name: 'trip_edit', methods: 'PUT')]
    public function edit(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, int $id): Response
    {
        try {
            $data = $request->getContent();
            /** @var Trip $tripInput */
            $tripInput = $serializer->deserialize($data, Trip::class, 'json');
            /** @var Trip $trip */
            $trip = $entityManager->getRepository(Trip::class)->find($id);
            if ($trip == null) {
                return $this->json([
                    'status' => 400,
                    'message' => "Trip " . $id . " not found"
                ], 400);
            }

            if ($tripInput->getName() != null) {
                $trip->setName($tripInput->getName());
            }

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

    #[Route('/api/trips/{id}/generateLink', name: 'trip_generate_link', methods: 'PUT')]
    public function generateLink(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, int $id): Response
    {
        /** @var Trip $trip */
        $trip = $entityManager->getRepository(Trip::class)->find($id);

        if ($trip == null) {
            return $this->json([
                'status' => 400,
                'message' => 'Trip not found',
            ], 400);
        } else {
            $trip->generateLink();
            $entityManager->persist($trip);
            $entityManager->flush();
            return $this->json([
                'status' => 200,
                'message' => 'Link ' . $trip->getLink() . ' generated',
            ], 200);
        }
    }

    #[Route('/api/trips/{id}/removeLink', name: 'trip_remove_link', methods: 'PUT')]
    public function removeLink(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, int $id): Response
    {
        /** @var Trip $trip */
        $trip = $entityManager->getRepository(Trip::class)->find($id);

        if ($trip == null) {
            return $this->json([
                'status' => 400,
                'message' => 'Trip not found',
            ], 400);
        } else {
            $trip->setLink(null);
            $entityManager->persist($trip);
            $entityManager->flush();
            return $this->json([
                'status' => 200,
                'message' => 'Link removed',
            ], 200);
        }
    }

    #[Route('/api/trips/{id}/checkLink/{link}', name: 'trip_check_link', methods: 'GET')]
    public function checkLink(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, int $id, string $link): Response
    {
        /** @var Trip $trip */
        $trip = $entityManager->getRepository(Trip::class)->find($id);

        if ($trip == null) {
            return $this->json([
                'status' => 400,
                'message' => 'Trip not found',
            ], 400);
        } else {
            if ($trip->getLink() == $link) {
                return $this->json($trip, 200, [], ['groups' => 'trip:item']);
            } else {
                return $this->json([
                    'status' => 401,
                    'message' => 'Wrong link',
                ], 401);
            }
        }
    }
}
