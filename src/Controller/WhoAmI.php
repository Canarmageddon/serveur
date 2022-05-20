<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WhoAmI extends AbstractController
{
    #[Route('/api/whoami', name: 'whoami', methods: 'GET')]
    public function whoami(){
        return $this->json([
                'user' => $this->getUser() ? $this->getUser() : null]
        );
    }
}