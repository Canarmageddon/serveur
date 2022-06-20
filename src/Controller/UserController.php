<?php

namespace App\Controller;

use App\Dto\UserEditInput;
use App\Entity\Trip;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    #[Route('/api/users/{email}/email', name: 'user_by_email', methods: 'GET')]
    public function byEmail(EntityManagerInterface $entityManager, string $email): Response
    {
        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($user != null) {
            return $this->json($user, 201, [], ['groups' => 'user:item']);
        } else {
            return $this->json([
                'message' => 'User with email ' . $email . ' not found',
            ], 404);
        }
    }

    #[Route('/api/users/{id}/trips', name: 'trips_by_user', methods: 'GET')]
    public function trips(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->find($id);
        if ($user != null) {
            return $this->json($user->getTrips(), 200, [], ['groups' => 'trip:item']);
        } else {
            return $this->json([
                'message' => 'User ' . $id . ' not found',
            ], 404);
        }
    }

    #[Route('/api/users/{id}/trips/{isEnded}/ended', name: 'trips_ended_by_user', methods: 'GET')]
    public function tripsEnded(EntityManagerInterface $entityManager, int $id, bool $isEnded): Response
    {
        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->find($id);
        if ($user != null) {
            $trips = $user->getTrips();
            $tripsReturned = [];
            $now = new DateTime('now');
            /** @var Trip $trip */
            foreach ($trips as $trip) {
                if ($trip->getSteps()->last() != null && $trip->getSteps()->last()->getCreationDate() > $now == $isEnded) {
                    $tripsReturned[] = $trip;
                }
            }
            return $this->json($tripsReturned, 200, [], ['groups' => 'trip:item']);
        } else {
            return $this->json([
                'message' => 'User ' . $id . ' not found',
            ], 404);
        }
    }

    #[Route('/api/users/{id}/edit', name: 'user_edit', methods: 'PUT')]
    public function edit(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, int $id): Response
    {
        try {
            $data = $request->getContent();
            /** @var UserEditInput $userInput */
            $userInput = $serializer->deserialize($data, UserEditInput::class, 'json');

            /** @var User $user */
            $user = $entityManager->getRepository(User::class)->find($id);
            if ($user == null) {
                return $this->json([
                    'status' => 400,
                    'message' => "User " . $id . " not found"
                ], 400);
            }
            //Access control
            $this->denyAccessUnlessGranted('TRIP_EDIT', $user);

            if ($userInput->getFirstName() != null) {
                $user->setFirstName($userInput->getFirstName());
            }

            if ($userInput->getLastName() != null) {
                $user->setLastName($userInput->getLastName());
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->json($user, 201, [], ['groups' => 'user:item']);
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
