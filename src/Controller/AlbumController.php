<?php

namespace App\Controller;

use App\Dto\AlbumInput;
use App\Entity\Album;
use App\Entity\LogBookEntry;
use App\Entity\Picture;
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
class AlbumController extends AbstractController
{
    #[Route('/api/albums/{id}/data', name: 'album_elements_by_album', methods: 'GET')]
    public function albumElements(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Album $album */
        $album = $entityManager->getRepository(Album::class)->find($id);
        if ($album != null) {
            return $this->json($album->getAlbumElements(), 200, [], ['groups' => 'albumElement:item']);
        } else {
            return $this->json([
                'message' => 'Album ' . $id . ' not found',
            ], 404);
        }
    }

    #[Route('/api/albums/{id}/pictures', name: 'pictures_by_album', methods: 'GET')]
    public function pictures(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Album $album */
        $album = $entityManager->getRepository(Album::class)->find($id);
        if ($album != null) {
            $pictures = $entityManager->getRepository(Picture::class)->findBy(['album' => $album->getId()]);
            return $this->json($pictures, 200, [], ['groups' => 'picture:item']);
        } else {
            return $this->json([
                'message' => 'Album ' . $id . ' not found',
            ], 404);
        }
    }

    #[Route('/api/albums/{id}/logBookEntries', name: 'log_book_entries_by_album', methods: 'GET')]
    public function logBookEntries(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Album $album */
        $album = $entityManager->getRepository(Album::class)->find($id);
        if ($album != null) {
            $logBookEntries = $entityManager->getRepository(LogBookEntry::class)->findBy(['album' => $album->getId()]);
            return $this->json($logBookEntries, 200, [], ['groups' => 'logBookEntry:item']);
        } else {
            return $this->json([
                'message' => 'Album ' . $id . ' not found',
            ], 404);
        }
    }

    #[Route('/api/albums/new', name: 'album_new', methods: 'POST')]
    public function new(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer): Response
    {
        try {
            $data = $request->getContent();
            /** @var AlbumInput $albumInput */
            $albumInput = $serializer->deserialize($data, AlbumInput::class, 'json');
            $album = new Album();

            /** @var Trip $trip */
            $trip = $entityManager->getRepository(Trip::class)->find($albumInput->getTrip());

            if ($trip != null) {
                $album->setTrip($trip);
                $trip->setAlbum($album);

                $entityManager->persist($album);
                $entityManager->flush();

                return $this->json($album, 201, [], ['groups' => 'album:item']);
            }
            else {
                return $this->json([
                    'status' => 400,
                    'message' => 'Trip '. $albumInput->getTrip() . ' not found'
                ], 400);
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
