<?php


namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * @Route("/api/users")
 */
class UserController
{
    /**
     * @Route("/current")
     * @param UserInterface $user
     * @return Response
     */
    public function index(UserInterface $user, SerializerInterface $serializer)
    {
        return new Response(
            $serializer->serialize($user, 'json', ['groups' => ['user']])
        );
    }
}