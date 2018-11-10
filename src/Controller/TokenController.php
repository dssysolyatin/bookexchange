<?php

namespace App\Controller;

use App\Manager\UserManager;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use VK\Client\VKApiClient;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;

/**
 * @Route("/token")
 */
class TokenController extends Controller
{
    /**
     * @Route(path="/")
     *
     * @param UserInterface            $user
     * @param JWTTokenManagerInterface $tokenManager
     *
     * @return Response
     */
    public function token(Request $request, UserInterface $user, JWTTokenManagerInterface $tokenManager)
    {
        if (null === $user) {
            return new Response('', 403);
        }

        if ('bookex.ru' === $request->getHost()) {
            $cookieDomain = '.'.$request->getHost();
            $redirect = 'http://app.bookex.ru';
        } else {
            $cookieDomain = null;
            $redirect = '/';
        }

        $response = new RedirectResponse($redirect);

        $response->headers->setCookie(
            new Cookie('token', $tokenManager->create($user), 0, '/', $cookieDomain, false, false)
        );

        return $response;
    }

    /**
     * @Route(path="/by_vk_token", methods={"POST"})
     *
     * @param Request     $request
     * @param UserManager $userManager
     *
     * @return JsonResponse
     */
    public function tokenByAccessToken(Request $request, UserManager $userManager, JWTTokenManagerInterface $tokenManager)
    {
        $token = $request->request->get('token');

        if (null === $token) {
            return new JsonResponse(['error' => 'Invalid token']);
        }

        try {
            $vkUserData = (new VKApiClient())->users()->get($token, [
                'fields' => [
                    'first_name',
                    'last_name',
                    'photo_medium',
                    'domain',
                ],
            ]);
        } catch (VKApiException $e) {
            return new JsonResponse(['error' => 'Invalid token']);
        } catch (VKClientException $e) {
            return new JsonResponse(['error' => 'Invalid token']);
        }

        $user = $userManager->get($vkUserData[0]['id']);
        if (null === $user) {
            $user = $userManager->createByVkData($vkUserData[0]);
            $user->setVkToken($token);
        }

        return new JsonResponse(['token' => $tokenManager->create($user)]);
    }
}
