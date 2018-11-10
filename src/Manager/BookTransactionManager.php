<?php

namespace App\Manager;

use App\DTO\Request\CreateBookTransaction;
use App\Entity\BookTransaction;
use Doctrine\ORM\EntityManager;

class BookTransactionManager
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function createFromDTO(CreateBookTransaction $dto): BookTransaction
    {
        $transaction = (new BookTransaction())
            ->setBookId($dto->book->getId())
            ->setRequesterId($dto->requester->getId())
            ->setKeeperId($dto->keeper->getId())
        ;

        $this->em->persist($transaction);
        $this->em->flush($transaction);

        return $transaction;
    }
}
