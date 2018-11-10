<?php


namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class BookRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @param int $userId
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getUserBooks(int $userId)
    {
        $books = $this->_em->getConnection()->executeQuery(
            "SELECT * FROM user_book_category ubc INNER JOIN book i ON i.id = ubc.book_id WHERE user_id = ?",
            [$userId],
            [\PDO::PARAM_INT]
        )->fetchAll();

        $booksResult = [];
        foreach ($books as $book) {
            $booksResult[] =  array_merge(json_decode($book['info'], true), [
                'userId' => $book['user_id'],
                'bookId' => $book['book_id'],
                'category_id' => $book['category_id']
            ]);
        }

        return $booksResult;
    }
}