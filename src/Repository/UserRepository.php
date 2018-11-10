<?php

namespace App\Repository;

use App\Entity\User;
use App\Func;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @todo add pagination....
     * @param $bookId
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getUsersByBook($bookId)
    {
        $users = $this->_em->getConnection()->executeQuery(
        'SELECT u.* FROM 
                  user_book_category ubc 
              INNER JOIN 
                "user" u 
              ON 
                u.id = ubc.user_id 
              WHERE ubc.book_id = ?
        ', [$bookId])->fetchAll();

        return Func::allKeysToCamelCase($users);
    }
}
