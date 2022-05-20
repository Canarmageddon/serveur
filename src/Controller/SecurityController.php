<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route('/api/login', name: 'app_login', methods: 'POST')]
    public function login(){
        return $this->json([
                'user' => $this->getUser() ? $this->getUser() : null]
        );
    }

    #[Route('/api/logout', name: 'app_logout')]
    public function logout()
    {
        throw new \Exception('should not be reached');
    }

    #[Route('/api/logoutRes', name: 'logout_success')]
    public function logoutRes(): Response
    {
        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);
        return $response;
    }

    #[Route('/api/loginFailed', name: 'login_failed')]
    public function loginFailed(): Response
    {
        $response = new Response();
        $response->setStatusCode(Response::HTTP_UNAUTHORIZED);
        $response->setContent('Invalid credentials');
        $response->headers->set('Content-Type', 'text/html');
        return $response;
    }
}