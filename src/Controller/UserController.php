<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/api/users/{email}/email', name: 'user_by_email', methods: 'GET')]
    public function byEmail(EntityManagerInterface $entityManager, string $email): Response
    {
        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($user != null) {
            return $this->json($user, 201, [], ['groups' => 'user:item']);
        } else {
            return $this->json([
                'message' => 'User with email ' . $email . ' not found',
            ], 404);
        }
    }
}
