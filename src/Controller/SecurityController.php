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

}