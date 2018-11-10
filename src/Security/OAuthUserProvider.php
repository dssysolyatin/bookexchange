<?php

namespace App\Security;

use App\Entity\User;
use App\User\Manager;
use HWI\Bundle\OAuthBundle\Connect\AccountConnectorInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class OAuthUserProvider implements UserProviderInterface, AccountConnectorInterface, OAuthAwareUserProviderInterface
{
    /**
     * @var Manager
     */
    private $userManager;


    /**
     * OAuthUserProvider constructor.
     * @param Manager $userManager
     */
    public function __construct(Manager $userManager)
    {
        $this->userManager = $userManager;
    }

    public function loadUserByUsername($username)
    {
        return $this->userManager->get($username);
    }

    public function refreshUser(UserInterface $user)
    {
        $user = $this->userManager->get($user->getId());

        if (null === $user) {
            throw new UsernameNotFoundException();
        }

        return $user;
    }

    public function supportsClass($class)
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }

    /**
     * Connects the response to the user object.
     *
     * @param UserInterface $user The user object
     * @param UserResponseInterface $response The oauth response
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {

    }

    /**
     * Loads the user by a given UserResponseInterface object.
     *
     * @param UserResponseInterface $response
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $data = $response->getData()['response'][0];
        $user = $this->userManager->get($data['id']);

        if (null === $user) {
            return $this->userManager->createByVkData($data);
        }

        return $user;
    }
}