<?php

namespace App\Controller;

use App\Dto\CostInput;
use App\Entity\Cost;
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
    #[Route('/api/cost/new', name: 'cost_new', methods: 'POST')]
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
            $cost->setBeneficiaries($costInput->getBeneficiaries());

            /** @var User $creator */
            $creator = $entityManager->getRepository(User::class)->find($costInput->getCreator());
            $creator?->addCost($cost);

            /** @var Trip $trip */
            $trip = $entityManager->getRepository(Trip::class)->find($costInput->getTrip());
            $trip?->addCost($cost);

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
}
