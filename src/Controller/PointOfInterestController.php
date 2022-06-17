<?php

namespace App\Controller;

use App\Dto\PointOfInterestInput;
use App\Entity\Location;
use App\Entity\PointOfInterest;
use App\Entity\Step;
use App\Entity\Travel;
use App\Entity\Trip;
use App\Entity\User;
use DateTime;
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
    #[Route('/api/point_of_interests/{id}/documents', name: 'documents_by_poi', methods: 'GET')]
    public function poi(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var PointOfInterest $pointOfInterest */
        $pointOfInterest = $entityManager->getRepository(PointOfInterest::class)->find($id);
        if ($pointOfInterest != null) {
            return $this->json($pointOfInterest->getDocuments(), 200, [], ['groups' => 'document:item']);
        } else {
            return $this->json([
                'message' => 'Point of Interest ' . $id . ' not found',
            ], 404);
        }
    }

    #[Route('/api/point_of_interests', name: 'point_of_interest_new', methods: 'POST')]
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
            $location->setName($pointOfInterestInput->getName());
            $location->setType($pointOfInterestInput->getType());
            $location->addPointOfInterest($poi);
            $entityManager->persist($location);

            /** @var User $creator */
            $creator = $entityManager->getRepository(User::class)->find($pointOfInterestInput->getCreator());
            $creator?->addPointOfInterest($poi);

            /** @var Trip $trip */
            $trip = $entityManager->getRepository(Trip::class)->find($pointOfInterestInput->getTrip());
            $trip?->addPointsOfInterest($poi);

            if ($pointOfInterestInput->getTitle() != null) {
                $poi->setTitle($pointOfInterestInput->getTitle());
            }
            if ($pointOfInterestInput->getDescription() != null) {
                $poi->setDescription($pointOfInterestInput->getDescription());
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

    #[Route('/api/point_of_interests/{id}', name: 'point_of_interest_edit', methods: 'PUT')]
    public function edit(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, int $id): Response
    {
        try {
            $data = $request->getContent();
            /** @var PointOfInterestInput $pointOfInterestInput */
            $pointOfInterestInput = $serializer->deserialize($data, PointOfInterestInput::class, 'json');

            /** @var PointOfInterest $poi */
            $poi = $entityManager->getRepository(PointOfInterest::class)->find($id);
            if ($poi == null) {
                return $this->json([
                    'status' => 400,
                    'message' => "Point of Interest " . $id . " not found"
                ], 400);
            }

            if ($pointOfInterestInput->getLatitude() != null) {
                $poi->getLocation()->setLatitude($pointOfInterestInput->getLatitude());
            }
            if ($pointOfInterestInput->getLongitude() != null) {
                $poi->getLocation()->setLongitude($pointOfInterestInput->getLongitude());
            }
            if ($pointOfInterestInput->getName() != null) {
                $poi->getLocation()->setName($pointOfInterestInput->getName());
            }
            if ($pointOfInterestInput->getType() != null) {
                $poi->getLocation()->setType($pointOfInterestInput->getType());
            }

            if ($pointOfInterestInput->getTitle() != null) {
                $poi->setTitle($pointOfInterestInput->getTitle());
            }
            if ($pointOfInterestInput->getDescription() != null) {
                $poi->setDescription($pointOfInterestInput->getDescription());
            }

            if ($pointOfInterestInput->getStep() != null) {
                /** @var Step $step */
                $step = $entityManager->getRepository(Step::class)->find($pointOfInterestInput->getStep());
                if ($step != null) {
                    if (!$step->getPointsOfInterest()->contains($poi)) {
                        if ($step->getTrip() === $poi->getTrip()) {
                            $step->addPointsOfInterest($poi);
                        } else {
                            return $this->json([
                                'status' => 400,
                                'message' => "Step and POI are not from the same Trip",
                            ], 400);
                        }
                    }
                    $entityManager->persist($step);
                }
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

    #[Route('/api/point_of_interests/{id}/toStep', name: 'point_of_interest_to_step', methods: 'PUT')]
    public function toStep(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, int $id): Response
    {
        /** @var PointOfInterest $poi */
        $poi = $entityManager->getRepository(PointOfInterest::class)->find($id);
        if ($poi == null) {
            return $this->json([
                'status' => 400,
                'message' => "Point of Interest " . $id . " not found"
            ], 400);
        }

        $step = new Step();

        $location = $poi->getLocation();
        $location->addStep($step);
        $entityManager->persist($location);

        $step->setDescription($poi->getDescription());
        $step->setTitle($poi->getTitle());

        /** @var User $creator */
        $creator = $entityManager->getRepository(User::class)->find($poi->getCreator());
        $creator?->addStep($step);

        /** @var Trip $trip */
        $trip = $entityManager->getRepository(Trip::class)->find($poi->getTrip());
        $start = $trip?->getSteps()->last();
        $trip?->addStep($step);

        $entityManager->persist($step);
        $entityManager->remove($poi);
        $entityManager->flush();

        $this->createTravel($step, $start, $entityManager);

        return $this->json($poi, 201, [], ['groups' => 'step:item']);
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
}
