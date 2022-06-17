<?php

namespace App\Controller;

use App\Dto\TripDto\TripInput;
use App\Dto\TripDto\UserInput;
use App\Entity\LogBookEntry;
use App\Entity\Picture;
use App\Entity\Step;
use App\Entity\Travel;
use App\Entity\Trip;
use App\Entity\TripUser;
use App\Entity\User;
use DateTime;
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

    #[Route('/api/trips/{id}/data', name: 'album_elements_by_trip', methods: 'GET')]
    public function albumElements(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Trip $trip */
        $trip = $entityManager->getRepository(Trip::class)->find($id);
        if ($trip != null) {
            return $this->json($trip->getAlbumElements(), 200, [], ['groups' => 'albumElement:item']);
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
            $logBookEntries = $entityManager->getRepository(LogBookEntry::class)->findBy(['trip' => $trip->getId()]);
            return $this->json($logBookEntries, 200, [], ['groups' => 'logBookEntry:item']);
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
            $pictures = $entityManager->getRepository(Picture::class)->findBy(['trip' => $trip->getId()]);
            return $this->json($pictures, 200, [], ['groups' => 'picture:item']);
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
            $travelers = [];
            $users = $trip->getUsers();
            foreach ($users as $user) {
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

    #[Route('/api/trips/{isEnded}/ended', name: 'api_trip_ended', methods: 'GET')]
    public function tripsEnded(EntityManagerInterface $entityManager, bool $isEnded): Response
    {
        $trips = $entityManager->getRepository(Trip::class)->findAll();
        $tripsReturned = [];
        $now = new DateTime('now');
        /** @var Trip $trip */
        foreach ($trips as $trip) {
            if ($trip->getSteps()->last()->getCreationDate() > $now == $isEnded) {
                $tripsReturned[] = $trip;
            }
        }

        return $this->json($tripsReturned, 200, [], ['groups' => 'trip:item']);
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
            //Access control
            $this->denyAccessUnlessGranted('TRIP_EDIT', $trip);
            $entityManager->persist($trip);
            $entityManager->flush();

            return $this->json($trip, 201, [], ['groups' => 'trip:item']);
        } catch (NotEncodableValueException $e) {
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

            //Access control
            $this->denyAccessUnlessGranted('TRIP_EDIT', $trip);

            if ($user != null && $trip != null) {

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
        } catch (NotEncodableValueException $e) {
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

            //Access control
            $this->denyAccessUnlessGranted('TRIP_EDIT', $trip);

            if ($user != null && $trip != null) {

                /** @var TripUser $tripUser */
                $tripUser = $entityManager->getRepository(TripUser::class)->findOneBy(['trip' => $trip->getId(), 'user' => $user->getId()]);

                //Vérifier si l'user est déjà dans le trip, si oui, l'enlever et flush
                if ($tripUser != null) {
                    $trip->removeTripUser($tripUser);
                    $user->removeTripUser($tripUser);
                    $entityManager->remove($tripUser);
                    $entityManager->flush();
                    return $this->json([
                        'message' => 'User ' . $emailUser . ' removed from Trip ' . $id,
                    ], 202);
                } else {
                    return $this->json([
                        'message' => 'User ' . $emailUser . ' already not member of Trip ' . $id,
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
        } catch (NotEncodableValueException $e) {
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
            //Access control
            $this->denyAccessUnlessGranted('TRIP_EDIT', $trip);

            if ($tripInput->getName() != null) {
                $trip->setName($tripInput->getName());
            }

            $entityManager->persist($trip);
            $entityManager->flush();

            return $this->json($trip, 201, [], ['groups' => 'trip:item']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    #[Route('/api/trips/{id}/generateLink', name: 'trip_generate_link', methods: 'PUT')]
    public function generateLink(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Trip $trip */
        $trip = $entityManager->getRepository(Trip::class)->find($id);

        if ($trip == null) {
            return $this->json([
                'status' => 400,
                'message' => 'Trip not found',
            ], 400);
        } else {
            //Access control
            $this->denyAccessUnlessGranted('TRIP_EDIT', $trip);
            $trip->generateLink();
            $entityManager->persist($trip);
            $entityManager->flush();
            return $this->json([
                'status' => 200,
                'message' => $trip->getLink(),
            ]);
        }
    }

    #[Route('/api/trips/{id}/removeLink', name: 'trip_remove_link', methods: 'PUT')]
    public function removeLink(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Trip $trip */
        $trip = $entityManager->getRepository(Trip::class)->find($id);

        if ($trip == null) {
            return $this->json([
                'status' => 400,
                'message' => 'Trip not found',
            ], 400);
        } else {
            //Access control
            $this->denyAccessUnlessGranted('TRIP_EDIT', $trip);
            $trip->setLink(null);
            $entityManager->persist($trip);
            $entityManager->flush();
            return $this->json([
                'status' => 200,
                'message' => 'Link removed',
            ]);
        }
    }

    #[Route('/api/trips/{id}/checkLink/{link}', name: 'trip_check_link', methods: 'GET')]
    public function checkLink(EntityManagerInterface $entityManager, int $id, string $link): Response
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

    #[Route('/api/trips/{id}/emptyPoi', name: 'trip_empty_poi', methods: 'PUT')]
    public function emptyPoi(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Trip $trip */
        $trip = $entityManager->getRepository(Trip::class)->find($id);

        if ($trip == null) {
            return $this->json([
                'status' => 400,
                'message' => 'Trip not found',
            ], 400);
        } else {
            $trip->emptyPointsOfInterest($entityManager);
            return $this->json([
                'status' => 200,
                'message' => 'Points of Interest deleted',
            ]);
        }
    }

    #[Route('/api/trips/{id}/emptySteps', name: 'trip_empty_steps', methods: 'PUT')]
    public function emptySteps(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Trip $trip */
        $trip = $entityManager->getRepository(Trip::class)->find($id);

        if ($trip == null) {
            return $this->json([
                'status' => 400,
                'message' => 'Trip not found',
            ], 400);
        } else {
            $trip->emptySteps($entityManager);
            return $this->json([
                'status' => 200,
                'message' => 'Steps deleted',
            ]);
        }
    }

    #[Route('/api/trips/{id}/clone', name: 'trip_clone', methods: 'PUT')]
    public function clone(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, int $id): Response
    {
        /** @var Trip $oldTrip */
        $oldTrip = $entityManager->getRepository(Trip::class)->find($id);

        if ($oldTrip == null) {
            return $this->json([
                'status' => 400,
                'message' => 'Trip not found',
            ], 400);
        } else {
            try {
                $data = $request->getContent();
                /** @var TripInput $tripInput */
                $tripInput = $serializer->deserialize($data, TripInput::class, 'json');
                $trip = new Trip();
                if ($tripInput->getName() != null) {
                    $trip->setName($tripInput->getName());
                }
                /** @var User $creator */
                $creator = null;
                if ($tripInput->getCreator() != null) {
                    $creator = $entityManager->getRepository(User::class)->find($tripInput->getCreator());
                    if ($creator != null) {
                        $tripUser = new TripUser();
                        $trip->addTripUser($tripUser);
                        $creator->addTripUser($tripUser);
                        $entityManager->persist($tripUser);
                    }
                }

                $oldStart = $oldTrip->getTravels()->first()->getStart();
                $start = new Step();
                $start->setTitle($oldStart?->getTitle());
                $creator?->addStep($start);
                $trip->addStep($start);
                $location = $oldStart->getLocation();
                $location?->addStep($start);
                $entityManager->persist($start);

                foreach ($oldTrip->getTravels() as $oldTravel) {
                    $travel = new Travel();

                    $oldEnd = $oldTravel->getEnd();
                    $end = new Step();
                    $end->setTitle($oldEnd?->getTitle());
                    $creator?->addStep($end);
                    $trip->addStep($end);
                    $location = $oldEnd->getLocation();
                    $location?->addStep($end);
                    $entityManager->persist($end);

                    $start->addStart($travel);
                    $end->addEnd($travel);
                    $trip->addTravel($travel);
                    $entityManager->persist($travel);
                    $start = $end;
                }

                $entityManager->persist($trip);
                $entityManager->flush();

                return $this->json($trip, 201, [], ['groups' => 'trip:item']);
            } catch (NotEncodableValueException $e) {
                return $this->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ], 400);
            }
        }
    }
}