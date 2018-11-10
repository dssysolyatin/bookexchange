<?php


namespace App\Repository;

use App\BestLanguageDetector;
use App\Entity\Book;
use App\Func;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class BookRepository extends ServiceEntityRepository
{
    /**
     * @var BestLanguageDetector
     */
    private $languageDetector;

    public function __construct(RegistryInterface $registry, BestLanguageDetector $languageDetector)
    {
        parent::__construct($registry, Book::class);

        $this->languageDetector = $languageDetector;
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
                'categoryId' => $book['category_id']
            ]);
        }

        return $booksResult;
    }

    /**
     * @param string $text
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function searchFreeBooks(string $text, int $excludeUserId)
    {
        $language = $this->languageDetector->detect($text);

        $books = $this->_em->getConnection()->executeQuery(
            '
                  SELECT b.* FROM 
                      book b 
                  INNER JOIN 
                    user_book_category ubc ON ubc.book_id = b.id AND ubc.category_id = 0 AND ubc.user_id != ?
                  WHERE to_tsvector(?, info->>\'title\') @@ to_tsquery(?, ?)
            ',
            [$excludeUserId, $language, $language, str_replace(' ', '', $text)],
            [\PDO::PARAM_INT, \PDO::PARAM_STR, \PDO::PARAM_STR, \PDO::PARAM_STR]
        )->fetchAll();


        $booksResult = [];
        foreach ($books as $book) {
            $booksResult[] = array_merge(
                json_decode($book['info'], true),
                ['id' => $book['id']]
            );
        }

        return $booksResult;

    }
}