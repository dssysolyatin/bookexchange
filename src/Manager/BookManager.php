<?php

namespace App\Manager;

use App\DTO\Response\BookList;
use App\Entity\Book;
use App\Entity\User;
use App\Entity\UserBookCategory;
use App\Exception\DuplicationUserCategoryBookException;
use App\Exception\GoogleNotFoundBookException;
use App\Repository\BookRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Google_Service_Books_Volume;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class BookManager
{
    /**
     * @var \Google_Service_Books
     */
    private $gBookService;
    /**
     * @var BookRepository
     */
    private $bookRepository;
    /**
     * @var ObjectNormalizer
     */
    private $objectNormalizer;
    /**
     * @var EntityManager|EntityManagerInterface
     */
    private $em;

    /**
     * Manager constructor.
     *
     * @param \Google_Service_Books  $gBookService
     * @param BookRepository         $bookRepository
     * @param ObjectNormalizer       $objectNormalizer
     * @param EntityManagerInterface $em
     */
    public function __construct(
        \Google_Service_Books $gBookService,
        BookRepository $bookRepository,
        NormalizerInterface $objectNormalizer,
        EntityManager $em
    ) {
        $this->gBookService = $gBookService;
        $this->bookRepository = $bookRepository;
        $this->objectNormalizer = $objectNormalizer;
        $this->em = $em;
    }

    /**
     * @param User   $user
     * @param int    $categoryId
     * @param string $bookId
     *
     * @return UserBookCategory
     */
    public function create(User $user, int $categoryId, string $bookId)
    {
        try {
            $this->createOrGetBookByVolumeId($bookId);
        } catch (\Google_Service_Exception $e) {
            throw new GoogleNotFoundBookException();
        }

        $userBookCategory = (new UserBookCategory())
            ->setUserId($user->getId())
            ->setBookId($bookId)
            ->setCategoryId($categoryId)
        ;

        try {
            $this->em->persist($userBookCategory);
            $this->em->flush($userBookCategory);
        } catch (UniqueConstraintViolationException $e) {
            throw new DuplicationUserCategoryBookException();
        }

        return $userBookCategory;
    }

    public function createOrGetBookByVolumeId(string $volumeId)
    {
        $book = $this->bookRepository->find($volumeId);

        if (null === $book) {
            $volume = $this->gBookService->volumes->get($volumeId);
            $bookDTO = \App\DTO\Response\Book::createByVolume($volume);

            $book = (new Book())
                ->setId($volumeId)
                ->setInfo($this->objectNormalizer->normalize($bookDTO))
            ;

            $this->em->persist($book);
            $this->em->flush($book);
        }

        return $book;
    }

    public function getBooksFromGoogleBook(string $query, $params = [])
    {
        $volumes = $this->gBookService->volumes->listVolumes($query, $params);

        $booksList = new BookList();
        $booksList->totalItems = $volumes->getTotalItems();

        foreach ($volumes->getItems() as $volume) {
            /*
             * @var Google_Service_Books_Volume $volume
             */
            $booksList->books[] = \App\DTO\Response\Book::createByVolume($volume);
        }

        return $booksList;
    }
}
