<?php

namespace App\Controller;

use App\Dto\PointOfInterestInput;
use App\Entity\Location;
use App\Entity\PointOfInterest;
use App\Repository\PointOfInterestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class PointOfInterestController extends AbstractController
{
    #[Route('/api/point_of_interests/new', name: 'point_of_interest_new', methods: 'POST')]
    public function new(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer): Response
    {
        try {
            $data = $request->getContent();
            /** @var PointOfInterestInput $pointOfInterestInput */
            $pointOfInterestInput = $serializer->deserialize($data, PointOfInterestInput::class, 'json');
            $poi = new PointOfInterest();
            $location = new Location();
            $location->setLatitude($pointOfInterestInput->getLatitude());
            $location->setLongitude($pointOfInterestInput->getLongitude());
            $location->addPointOfInterest($poi);
            $entityManager->persist($location);

            if ($pointOfInterestInput->getCreator() != null) {
                $poi->setCreator($pointOfInterestInput->getCreator());
            }
            if ($pointOfInterestInput->getTitle() != null) {
                $poi->setTitle($pointOfInterestInput->getTitle());
            }
            if ($pointOfInterestInput->getDescription() != null) {
                $poi->setDescription($pointOfInterestInput->getDescription());
            }
            if ($pointOfInterestInput->getTrip() != null) {
                $poi->setTrip($pointOfInterestInput->getTrip());
            }
            $entityManager->persist($poi);
            $entityManager->flush();

            return $this->json($poi, 201, [], ['groups' => 'pointOfInterest:item']);
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
