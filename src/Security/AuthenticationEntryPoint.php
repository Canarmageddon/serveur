<?php

namespace App\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        $response = new Response();
        $response->setStatusCode(Response::HTTP_UNAUTHORIZED);
        $response->setContent('Access denied');
        $response->headers->set('Content-Type', 'text/html');


        return $response;
    }
}