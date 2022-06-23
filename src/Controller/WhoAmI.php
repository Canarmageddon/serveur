<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WhoAmI extends AbstractController
{
    #[Route('/api/whoami', name: 'whoami', methods: 'GET')]
    public function whoami() : Response
    {
        $user = $this->getUser() ? $this->getUser() : null;
        return $this->json(
               $user , 200, [], ['groups' => 'user:item']
        );
    }
}