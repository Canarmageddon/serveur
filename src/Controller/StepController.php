<?php

namespace App\Controller;

use App\Dto\StepInput;
use App\Entity\Location;
use App\Entity\Step;
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
            $location->setName($stepInput->getName());
            $location->setType($stepInput->getType());
            $location->addStep($step);
            $entityManager->persist($location);

            if ($stepInput->getDate() != null) {
                $date = new DateTime($stepInput->getDate());
                $step->setDate($date);
            }
            $step->setDescription($stepInput->getDescription());
            $step->setTitle($stepInput->getTitle());

            /** @var User $creator */
            $creator = $entityManager->getRepository(User::class)->find($stepInput->getCreator());
            $creator?->addStep($step);

            /** @var Trip $trip */
            $trip = $entityManager->getRepository(Trip::class)->find($stepInput->getTrip());
            $trip?->addStep($step);

            //Access control
            $this->denyAccessUnlessGranted('TRIP_EDIT', $step);

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

    #[Route('/api/steps/{id}/edit', name: 'step_edit', methods: 'PUT')]
    public function edit(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, int $id): Response
    {
        try {
            $data = $request->getContent();
            /** @var StepInput $stepInput */
            $stepInput = $serializer->deserialize($data, StepInput::class, 'json');
            /** @var Step $step */
            $step = $entityManager->getRepository(Step::class)->find($id);
            if ($step == null) {
                return $this->json([
                    'status' => 400,
                    'message' => "Step " . $id . " not found"
                ], 400);
            }
            //Access control
            $this->denyAccessUnlessGranted('TRIP_EDIT', $step);

            if ($stepInput->getLatitude() != null) {
                $step->getLocation()->setLatitude($stepInput->getLatitude());
            }
            if ($stepInput->getLongitude() != null) {
                $step->getLocation()->setLongitude($stepInput->getLongitude());
            }
            if ($stepInput->getName() != null) {
                $step->getLocation()->setName($stepInput->getName());
            }
            if ($stepInput->getType() != null) {
                $step->getLocation()->setType($stepInput->getType());
            }

            if ($stepInput->getTitle() != null) {
                $step->setTitle($stepInput->getTitle());
            }
            if ($stepInput->getDescription() != null) {
                $step->setDescription($stepInput->getDescription());
            }

            if ($stepInput->getDate() != null) {
                $date = DateTime::createFromFormat('d-m-Y', $stepInput->getDate());
                $step->setDate($date);
            }

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
