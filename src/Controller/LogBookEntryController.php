<?php

namespace App\Controller;

use App\Dto\LogBookEntryInput;
use App\Entity\Album;
use App\Entity\Location;
use App\Entity\LogBookEntry;
use App\Entity\Trip;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

class LogBookEntryController extends AbstractController
{
    #[Route('/api/log_book_entries/new', name: 'log_book_entry_new', methods: 'POST')]
    public function new(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer): Response
    {
        try {
            $data = $request->getContent();
            /** @var LogBookEntryInput $logBookEntryInput */
            $logBookEntryInput = $serializer->deserialize($data, LogBookEntryInput::class, 'json');
            $logBookEntry = new LogBookEntry();
            $logBookEntry->setContent($logBookEntryInput->getContent());

            /** @var User $creator */
            $creator = $entityManager->getRepository(User::class)->find($logBookEntryInput->getCreator());
            $creator?->addAlbumElement($logBookEntry);

            /** @var Trip $trip */
            $trip = $entityManager->getRepository(Trip::class)->find($logBookEntryInput->getTrip());
            $trip?->addAlbumElement($logBookEntry);

            /** @var Album $album */
            if ($logBookEntryInput->getAlbum()) {
                $album = $entityManager->getRepository(Album::class)->find($logBookEntryInput->getAlbum());
                $album?->addAlbumElement($logBookEntry);
            }

            /** @var Location $location */
            if ($logBookEntryInput->getLocation()) {
                $location = $entityManager->getRepository(Location::class)->find($logBookEntryInput->getLocation());
                $location?->addAlbumElement($logBookEntry);
            } else if ($logBookEntryInput->getLatitude() != null && $logBookEntryInput->getLongitude() != null) {
                $location = new Location();
                $location->setLatitude($logBookEntryInput->getLatitude());
                $location->setLongitude($logBookEntryInput->getLongitude());
                $entityManager->persist($location);
            }

            //Access control
            $this->denyAccessUnlessGranted('TRIP_EDIT', $logBookEntry);

            $entityManager->persist($logBookEntry);
            $entityManager->flush();

            return $this->json($logBookEntry, 201, [], ['groups' => 'logBookEntry:item']);
        }
        catch (NotEncodableValueException $e)
        {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    #[Route('/api/log_book_entries/{id}/edit', name: 'log_book_entry_edit', methods: 'PUT')]
    public function edit(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, int $id): Response
    {
        try {
            $data = $request->getContent();
            /** @var LogBookEntryInput $logBookEntryInput */
            $logBookEntryInput = $serializer->deserialize($data, LogBookEntryInput::class, 'json');

            /** @var LogBookEntry $logBookEntry */
            $logBookEntry = $entityManager->getRepository(LogBookEntry::class)->find($id);
            if ($logBookEntry == null) {
                return $this->json([
                    'status' => 400,
                    'message' => "LogBookEntry " . $id . " not found"
                ], 400);
            }

            if ($logBookEntryInput->getContent() != null) {
                $logBookEntry->setContent($logBookEntryInput->getContent());
            }

            $entityManager->persist($logBookEntry);
            $entityManager->flush();

            return $this->json($logBookEntry, 201, [], ['groups' => 'logBookEntry:item']);
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
