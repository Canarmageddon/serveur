<?php

namespace App\Controller;

use App\Dto\CredentialsInput;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

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

    #[Route('/api/users/{id}/trips', name: 'trips_by_user', methods: 'GET')]
    public function users(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->find($id);
        if ($user != null) {
            return $this->json($user->getTrips(), 200, [], ['groups' => 'trip:item']);
        } else {
            return $this->json([
                'message' => 'User ' . $id . ' not found',
            ], 404);
        }
    }

    #[Route('/api/users/checkCredentials', name: 'check_credentials', methods: 'POST')]
    public function checkCredentials(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer, UserPasswordHasherInterface $passwordEncoder): Response
    {
        try {
            $data = $request->getContent();
            /** @var CredentialsInput $credentialsInput */
            $credentialsInput = $serializer->deserialize($data, CredentialsInput::class, 'json');
            $email = $credentialsInput->getEmail();
            if ($email != null) {
                $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
                if ($user != null) {
                    $userExists = $passwordEncoder->isPasswordValid($user, $credentialsInput->getPassword());
                    if ($userExists) {
                        return $this->json($user, 200, [], ['groups' => 'user:item']);
                    } else {
                        return $this->json([
                            'message' => 'Email or password is wrong !',
                        ], 404);
                    }
                } else {
                    return $this->json([
                        'message' => 'User ' . $email . ' not found',
                    ], 404);
                }
            } else {
                return $this->json([
                    'message' => 'No email given !',
                ], 400);
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
