<?php


namespace App\Controller\Api;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TestController
{
    /**
     * @Route(path="/api/test")
     * @param TokenStorageInterface $tokenStorage
     * @return Response
     */
    public function test(TokenStorageInterface $tokenStorage)
    {
        return new Response("Hello world");
    }
}