<?php

namespace App\Controller;

use App\Dto\LogBookEntryInput;
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
            $creator?->addLogBookEntry($logBookEntry);

            /** @var Trip $trip */
            $trip = $entityManager->getRepository(Trip::class)->find($logBookEntryInput->getTrip());
            $trip?->addLogBookEntry($logBookEntry);

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
