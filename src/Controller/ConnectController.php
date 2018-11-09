<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConnectController extends \HWI\Bundle\OAuthBundle\Controller\ConnectController
{
    /**
     * @Route("/login/{service}", defaults={"service"="vkontakte"})
     * @param Request $request
     * @param string $service
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToServiceAction(Request $request, $service)
    {
        return parent::redirectToServiceAction($request, $service);
    }

}