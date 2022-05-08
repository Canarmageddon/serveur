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
    #[Route('/api/steps/{id}/documents', name: 'documents_by_step', methods: 'GET')]
    public function documents(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Step $step */
        $step = $entityManager->getRepository(Step::class)->find($id);
        if ($step != null) {
            return $this->json($step->getDocuments(), 200, [], ['groups' => 'document:item']);
        } else {
            return $this->json([
                'message' => 'Step ' . $id . ' not found',
            ], 404);
        }
    }

    #[Route('/api/steps/{id}/poi', name: 'poi_by_step', methods: 'GET')]
    public function poi(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Step $step */
        $step = $entityManager->getRepository(Step::class)->find($id);
        if ($step != null) {
            return $this->json($step->getPointsOfInterest(), 200, [], ['groups' => 'pointOfInterest:item']);
        } else {
            return $this->json([
                'message' => 'Step ' . $id . ' not found',
            ], 404);
        }
    }

    #[Route('/api/steps/new', name: 'step_new', methods: 'POST')]
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
