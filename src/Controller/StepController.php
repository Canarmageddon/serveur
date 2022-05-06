<?php

namespace App\Controller;

use App\Dto\StepInput;
use App\Entity\Location;
use App\Entity\Step;
use App\Entity\Trip;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

class StepController extends AbstractController
{
    #[Route('/api/step/new', name: 'step_new', methods: 'POST')]
    public function new(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer): Response
    {
        try {
            $data = $request->getContent();
            /** @var StepInput $stepInput */
            $stepInput = $serializer->deserialize($data, StepInput::class, 'json');
            $step = new Step();
            $location = new Location();
            $location->setLatitude($stepInput->getLatitude());
            $location->setLongitude($stepInput->getLongitude());
            $location->addStep($step);
            $entityManager->persist($location);

            $step->setDescription($stepInput->getDescription());

            /** @var User $creator */
            $creator = $entityManager->getRepository(User::class)->find($stepInput->getCreator());
            $creator?->addStep($step);

            /** @var Trip $trip */
            $trip = $entityManager->getRepository(Trip::class)->find($stepInput->getTrip());
            $trip?->addStep($step);

            $entityManager->persist($step);
            $entityManager->flush();

            return $this->json($step, 201, [], ['groups' => 'step:item']);
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
