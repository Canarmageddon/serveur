<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\LogBookEntry;
use App\Entity\Picture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class LocationController extends AbstractController
{
    #[Route('/api/locations/{id}/albumElements', name: 'album_elements_by_location', methods: 'GET')]
    public function albumElements(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Location $location */
        $location = $entityManager->getRepository(Location::class)->find($id);
        if ($location != null) {
            return $this->json($location->getAlbumElements(), 200, [], ['groups' => 'albumElements:item']);
        } else {
            return $this->json([
                'message' => 'Location ' . $id . ' not found',
            ], 404);
        }
    }
    #[Route('/api/locations/{id}/logBookEntries', name: 'log_book_entries_by_location', methods: 'GET')]
    public function logBookEntries(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Location $location */
        $location = $entityManager->getRepository(Location::class)->find($id);
        if ($location != null) {
            $logBookEntries = $entityManager->getRepository(LogBookEntry::class)->findBy(['location' => $location->getId()]);
            return $this->json($logBookEntries, 200, [], ['groups' => 'logBookEntry:item']);
        } else {
            return $this->json([
                'message' => 'Location ' . $id . ' not found',
            ], 404);
        }
    }

    #[Route('/api/locations/{id}/pictures', name: 'pictures_by_location', methods: 'GET')]
    public function pictures(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Location $location */
        $location = $entityManager->getRepository(Location::class)->find($id);
        if ($location != null) {
            $pictures = $entityManager->getRepository(Picture::class)->findBy(['location' => $location->getId()]);
            return $this->json($pictures, 200, [], ['groups' => 'picture:item']);
        } else {
            return $this->json([
                'message' => 'Location ' . $id . ' not found',
            ], 404);
        }
    }

    #[Route('/api/locations/{id}/poi', name: 'poi_by_location', methods: 'GET')]
    public function poi(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Location $location */
        $location = $entityManager->getRepository(Location::class)->find($id);
        if ($location != null) {
            return $this->json($location->getPointOfInterests(), 200, [], ['groups' => 'pointOfInterest:item']);
        } else {
            return $this->json([
                'message' => 'Location ' . $id . ' not found',
            ], 404);
        }
    }

    #[Route('/api/locations/{id}/steps', name: 'steps_by_location', methods: 'GET')]
    public function steps(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Location $location */
        $location = $entityManager->getRepository(Location::class)->find($id);
        if ($location != null) {
            return $this->json($location->getSteps(), 200, [], ['groups' => 'step:item']);
        } else {
            return $this->json([
                'message' => 'Location ' . $id . ' not found',
            ], 404);
        }
    }

    #[Route('/api/locations', name: 'location_new', methods: 'POST')]
    public function new(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer): Response
    {
        try {
            $data = $request->getContent();
            $location = $serializer->deserialize($data, Location::class, 'json');

            $entityManager->persist($location);
            $entityManager->flush();

            return $this->json($location, 201, [], ['groups' => 'location:item']);
        }
        catch (NotEncodableValueException $e)
        {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    #[Route('/api/locations/{id}', name: 'location_edit', methods: 'PUT')]
    public function edit(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, int $id): Response
    {
        try {
            $data = $request->getContent();
            /** @var Location $locationInput */
            $locationInput = $serializer->deserialize($data, Location::class, 'json');

            /** @var Location $location */
            $location = $entityManager->getRepository(Location::class)->find($id);
            if ($location == null) {
                return $this->json([
                    'status' => 400,
                    'message' => "Location " . $id . " not found"
                ], 400);
            }

            if ($locationInput->getName() != null) {
                $location->setName($locationInput->getName());
            }
            if ($locationInput->getType() != null) {
                $location->setType($locationInput->getType());
            }
            if ($locationInput->getLongitude() != null) {
                $location->setLongitude($locationInput->getLongitude());
            }
            if ($locationInput->getLatitude() != null) {
                $location->setLatitude($locationInput->getLatitude());
            }
            $entityManager->persist($location);
            $entityManager->flush();

            return $this->json($location, 201, [], ['groups' => 'location:item']);
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
