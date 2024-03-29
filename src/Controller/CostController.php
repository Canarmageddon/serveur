<?php

namespace App\Controller;

use App\Dto\CostInput;
use App\Dto\TripDto\UserInput;
use App\Entity\Cost;
use App\Entity\CostUser;
use App\Entity\Guest;
use App\Entity\SuperUser;
use App\Entity\Trip;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class CostController extends AbstractController
{
    #[Route('/api/costs', name: 'cost_new', methods: 'POST')]
    public function new(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer): Response
    {
        try {
            $data = $request->getContent();
            /** @var CostInput $costInput */
            $costInput = $serializer->deserialize($data, CostInput::class, 'json');
            $cost = new Cost();
            $cost->setValue($costInput->getValue());
            $cost->setLabel($costInput->getLabel());
            $cost->setCategory($costInput->getCategory());
            $beneficiaries = $costInput->getBeneficiaries();

            /** @var SuperUser $creator */
            $creator = $entityManager->getRepository(SuperUser::class)->find($costInput->getCreator());
            $creator?->addCost($cost);

            /** @var Trip $trip */
            $trip = $entityManager->getRepository(Trip::class)->find($costInput->getTrip());
            $trip?->addCost($cost);
            foreach($beneficiaries as $beneficiary) {
                /** @var SuperUser $user */
                $user = $entityManager->getRepository(SuperUser::class)->find($beneficiary);

                if ($user != null && $cost->getTrip() != null && $user->isMemberOf($cost->getTrip()->getId())) {
                    /** @var CostUser $costUser */
                    $costUser = $entityManager->getRepository(CostUser::class)->findOneBy(['cost' => $cost->getId(), 'user' => $user->getId()]);

                    //Vérifier si l'user est déjà dans le cost, sinon, l'ajouter et flush
                    if ($costUser == null) {
                        $costUser = new CostUser();
                        $cost->addCostUser($costUser);
                        $user->addCostUser($costUser);
                        $entityManager->persist($costUser);
                    }
                }
            }
            //Access control
            $this->denyAccessUnlessGranted('TRIP_EDIT', $cost);

            $entityManager->persist($cost);
            $entityManager->flush();

            return $this->json($cost, 201, [], ['groups' => 'cost:item']);
        }
        catch (NotEncodableValueException $e)
        {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    #[Route('/api/costs/{id}', name: 'cost_edit', methods: 'PUT')]
    public function edit(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, int $id): Response
    {
        try {
            $data = $request->getContent();
            /** @var CostInput $costInput */
            $costInput = $serializer->deserialize($data, CostInput::class, 'json');

            /** @var Cost $cost */
            $cost = $entityManager->getRepository(Cost::class)->find($id);
            if ($cost == null) {
                return $this->json([
                    'status' => 400,
                    'message' => "Cost " . $id . " not found"
                ], 400);
            }

            if ($costInput->getValue() != null) {
                $cost->setValue($costInput->getValue());
            }
            if ($costInput->getLabel() != null) {
                $cost->setLabel($costInput->getLabel());
            }
            if ($costInput->getCategory() != null) {
                $cost->setCategory($costInput->getCategory());
            }

            $entityManager->persist($cost);
            $entityManager->flush();

            return $this->json($cost, 201, [], ['groups' => 'cost:item']);
        }
        catch (NotEncodableValueException $e)
        {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    #[Route('/api/costs/{id}/addBeneficiary', name: 'cost_add_beneficiary', methods: 'PUT')]
    public function addBeneficiary(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, int $id): Response
    {
        try {
            $data = $request->getContent();
            /** @var UserInput $userInput */
            $userInput = $serializer->deserialize($data, UserInput::class, 'json');
            $userIdentifier = $userInput->getUserId();
            /** @var SuperUser $user */
            $user = $entityManager->getRepository(SuperUser::class)->find($userIdentifier);
            /** @var Cost $cost */
            $cost = $entityManager->getRepository(Cost::class)->find($id);

            if ($user != null && $cost != null) {

                if (!$user->isMemberOf($cost->getTrip()->getId())) {
                    return $this->json([
                        'message' => 'User ' . $userIdentifier . ' not member of Trip ' . $cost->getTrip()->getId(),
                    ], 401);
                }

                /** @var CostUser $costUser */
                $costUser = $entityManager->getRepository(CostUser::class)->findOneBy(['cost' => $cost->getId(), 'user' => $user->getId()]);

                //Vérifier si l'user est déjà dans le cost, sinon, l'ajouter et flush
                if ($costUser == null) {
                    $costUser = new CostUser();
                    $cost->addCostUser($costUser);
                    $user->addCostUser($costUser);
                    $entityManager->persist($costUser);
                    $entityManager->flush();
                    return $this->json([
                        'message' => 'User ' . $userIdentifier . ' added to Cost ' . $id,
                    ], 202);

                } else {
                    return $this->json([
                        'message' => 'User ' . $userIdentifier . ' already beneficiary of Cost ' . $id,
                    ], 200);
                }

            } elseif ($user == null && $cost == null) {
                return $this->json([
                    'message' => 'Cost ' . $id . ' and User ' . $userIdentifier . ' not found',
                ], 404);

            } elseif ($user == null) {
                return $this->json([
                    'message' => 'User ' . $userIdentifier . ' not found',
                ], 404);

            } else {
                return $this->json([
                    'message' => 'Cost ' . $id . ' not found',
                ], 404);
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

    #[Route('/api/costs/{id}/removeBeneficiary', name: 'cost_remove_beneficiary', methods: 'PUT')]
    public function removeBeneficiary(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, int $id): Response
    {
        try {
            $data = $request->getContent();
            /** @var UserInput $userInput */
            $userInput = $serializer->deserialize($data, UserInput::class, 'json');
            $userIdentifier = $userInput->getUserId();
            /** @var SuperUser $user */
            $user = $entityManager->getRepository(SuperUser::class)->find($userIdentifier);
            /** @var Cost $cost */
            $cost = $entityManager->getRepository(Cost::class)->find($id);

            if($user != null && $cost != null) {

                if (!$user->isMemberOf($cost->getTrip()->getId())) {
                    return $this->json([
                        'message' => 'User ' . $userIdentifier . ' not member of Trip ' . $cost->getTrip()->getId(),
                    ], 401);
                }

                /** @var CostUser $costUser */
                $costUser = $entityManager->getRepository(CostUser::class)->findOneBy(['cost' => $cost->getId(), 'user' => $user->getId()]);

                //Vérifier si l'user est déjà dans le cost, si oui, l'enlever et flush
                if ($costUser != null) {
                    $cost->removeCostUser($costUser);
                    $user->removeCostUser($costUser);
                    $entityManager->remove($costUser);
                    $entityManager->flush();
                    return $this->json([
                        'message' => 'User ' . $userIdentifier . ' removed from Cost ' . $id,
                    ], 202);

                } else {
                    return $this->json([
                        'message' => 'User ' . $userIdentifier . ' already not beneficiary of Cost ' . $id,
                    ]);
                }

            } elseif ($user == null && $cost == null) {
                return $this->json([
                    'message' => 'Cost ' . $id . ' and User ' . $userIdentifier . ' not found',
                ], 404);

            } elseif ($user == null) {
                return $this->json([
                    'message' => 'User ' . $userIdentifier . ' not found',
                ], 404);

            } else {
                return $this->json([
                    'message' => 'Cost ' . $id . ' not found',
                ], 404);
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
