<?php

namespace App\Controller;

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
    #[Route('/pointOfInterests', name: 'point_of_interest_new', methods: 'POST')]
    public function new(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer): Response
    {
        try {
            $data = $request->getContent();
            $location = $serializer->deserialize($data, Location::class, 'json');
            $poi = new PointOfInterest();
            $poi->setLocation($location);
            $entityManager->persist($location);
            $entityManager->persist($poi);
            $entityManager->flush();

            return $this->json($poi, 201, [], ['groups' => 'pointOfInterest:list']);
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
