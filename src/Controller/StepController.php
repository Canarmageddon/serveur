<?php

namespace App\Controller;

use App\Dto\StepInput;
use App\Entity\Location;
use App\Entity\Step;
use App\Entity\Travel;
use App\Entity\Trip;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\Pure;
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

    #[Route('/api/steps', name: 'step_new', methods: 'POST')]
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
            $start = $trip?->getSteps()->last();
            $trip?->addStep($step);

            $entityManager->persist($step);
            $entityManager->flush();

            $this->createTravel($step, $start, $entityManager);

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

    #[Route('/api/steps/{id}', name: 'step_edit', methods: 'PUT')]
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

    #[Route('/api/steps/{id}', name: 'delete_step', methods: 'DELETE')]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Step $step */
        $step = $entityManager->getRepository(Step::class)->find($id);
        if ($step != null) {
            $this->removeTravel($step, $entityManager);
            $entityManager->remove($step);
            $entityManager->flush();

            return $this->json([
                'message' => 'Step ' . $id . ' deleted',
            ], 204);
        } else {
            return $this->json([
                'message' => 'Step ' . $id . ' not found',
            ], 404);
        }
    }

    public function createTravel(Step $step, ?Step $start, EntityManagerInterface $entityManager) : void
    {
        $trip = $step->getTrip();

        if ($trip != null) {
            if ($start != null) {
                $travel = new Travel();
                $start->addStart($travel);
                $step->addEnd($travel);
                $start->getTrip()->addTravel($travel);
                $entityManager->persist($travel);
                $entityManager->flush();
            }
        }
    }

    public function removeTravel(Step $step, EntityManagerInterface $entityManager) : void
    {
        /** @var Travel $travel1 */
        $travel1 = $entityManager->getRepository(Travel::class)->findOneBy(['end' => $step->getId()]);
        /** @var Travel $travel2 */
        $travel2 = $entityManager->getRepository(Travel::class)->findOneBy(['start' => $step->getId()]);

        $start = null;
        $end = null;

        if ($travel1 != null) {
            $start = $travel1->getStart();
            $start->removeStart($travel1);
            $travel1->getEnd()->removeEnd($travel1);
            $entityManager->remove($travel1);
        }

        if ($travel2 != null) {
            $end = $travel2->getEnd();
            $travel2->getStart()->removeStart($travel2);
            $end->removeEnd($travel1);
            $entityManager->remove($travel2);
        }

        if ($start != null && $end != null) {
            $this->createTravel($end, $start, $entityManager);
        }

        $entityManager->flush();
    }
}
