<?php

namespace App\Manager;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;

class UserManager
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Manager constructor.
     *
     * @param EntityManager  $em
     * @param UserRepository $userRepository
     */
    public function __construct(EntityManager $em, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
    }

    /**
     * @param UserResponseInterface $response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createByVkData(array $data)
    {
        $user = (new User())
            ->setAvatarUrl($data['photo_medium'])
            ->setCreatedAt(new \DateTimeImmutable())
            ->setFirstname($data['first_name'])
            ->setLastname($data['last_name'])
            ->setId($data['id'])
            ->setVkDomain($data['domain'])
        ;

        $this->em->persist($user);
        $this->em->flush($user);

        return $user;
    }

    /**
     * @param $userId
     *
     * @return mixed
     */
    public function get($userId)
    {
        return $this->userRepository->find($userId);
    }
}
