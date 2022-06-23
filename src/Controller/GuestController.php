<?php

namespace App\Controller;

use App\Dto\GuestInput;
use App\Entity\Guest;
use App\Entity\Trip;
use App\Entity\TripUser;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

class GuestController extends AbstractController
{
    #[Route('/api/guests', name: 'guest_new', methods: 'POST')]
    public function new(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer): Response
    {
        try {
            $data = $request->getContent();
            /** @var GuestInput $guestInput */
            $guestInput = $serializer->deserialize($data, GuestInput::class, 'json');
            $guest = new Guest();
            $guest->setName($guestInput->getName());

            /** @var Trip $trip */
            $trip = $entityManager->getRepository(Trip::class)->find($guestInput->getTrip());

            if ($trip != null) {
                /** @var TripUser $tripUser */
                $tripUser = $entityManager->getRepository(TripUser::class)->findOneBy(['trip' => $trip->getId(), 'user' => $guest->getId()]);

                //Vérifier si l'user est déjà dans le trip, sinon, l'ajouter et flush
                if ($tripUser == null) {
                    $tripUser = new TripUser();
                    $trip->addTripUser($tripUser);
                    $guest->addTripUser($tripUser);
                    $entityManager->persist($tripUser);
                } else {
                    return $this->json([
                        'message' => 'User ' . $guest->getName() . ' already member of Trip ' . $trip->getId(),
                    ]);
                }
            }
            else {
                return $this->json([
                    'message' => 'Trip ' . $trip->getId() . ' not found',
                ], 404);
            }
            //Access control
//            $this->denyAccessUnlessGranted('TRIP_EDIT', $guest);

            $entityManager->persist($guest);
            $entityManager->flush();

            return $this->json($guest, 201, [], ['groups' => 'guest:item']);
        }
        catch (NotEncodableValueException $e)
        {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    #[Route('/api/guests/{id}', name: 'guest_edit', methods: 'PUT')]
    public function edit(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, int $id): Response
    {
        try {
            $data = $request->getContent();
            /** @var GuestInput $guestInput */
            $guestInput = $serializer->deserialize($data, GuestInput::class, 'json');

            /** @var Guest $guest */
            $guest = $entityManager->getRepository(Guest::class)->find($id);
            if ($guest == null) {
                return $this->json([
                    'status' => 400,
                    'message' => "User " . $id . " not found"
                ], 400);
            }
            //Access control
//            $this->denyAccessUnlessGranted('TRIP_EDIT', $guest);

            if ($guestInput->getName() != null) {
                $guest->setName($guestInput->getName());
            }

            $entityManager->persist($guest);
            $entityManager->flush();

            return $this->json($guest, 201, [], ['groups' => 'guest:item']);
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
