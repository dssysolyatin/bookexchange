<?php


namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/token")
 */
class TokenController extends Controller
{
    /**
     * @Route(path="/")
     * @param UserInterface $user
     * @param JWTTokenManagerInterface $tokenManager
     * @return Response
     */
    public function token(UserInterface $user, JWTTokenManagerInterface $tokenManager)
    {
        if (null === $user) {
            return new Response('', 403);
        }

        $response = new RedirectResponse('/');

        $response->headers->setCookie(
            new Cookie('token', $tokenManager->create($user), 0, '/', null, false, false)
        );

        return $response;
    }

    /**
     * @Route(path="/by_vk_token", methods={"POST"})
     * @param string $token
     */
    public function tokenByAccessToken(string $token)
    {

    }
}