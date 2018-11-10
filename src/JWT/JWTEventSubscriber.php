<?php


namespace App\JWT;


use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTAuthenticatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class JWTEventSubscriber implements EventSubscriberInterface
{

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * JWTEventSubscriber constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::JWT_CREATED => [
                ['onJWTCreated', 0]
            ],
            Events::JWT_AUTHENTICATED => [
                ['onJWTAuthenticated', 0]
            ]
        ];
    }

    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $token = $this->tokenStorage->getToken();
        $user = $event->getUser();

        if (null !== $user->getVkToken()) {
            return $event->setData(array_merge($event->getData(), ['vk_token' => $user->getVkToken()]));
        }

        if (!$token instanceof OAuthToken) {
            return;
        }

        if ($user->getId() !== $event->getData()['username']) {
            return;
        }

        $event->setData(array_merge($event->getData(), ['vk_token' => $token->getAccessToken()]));
    }

    public function onJWTAuthenticated(JWTAuthenticatedEvent $event)
    {
        $user = $event->getToken()->getUser();
        $payload = $event->getPayload();

        if (isset($payload['vk_token'])) {
            $user->setVkToken($payload['vk_token']);
        }
    }
}