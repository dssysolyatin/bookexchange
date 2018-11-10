<?php

namespace App\Controller\Api;

use App\DTO\Request\CreateBookTransaction;
use App\Entity\Category;
use App\Form\Type\BookTransaction\CreateType;
use App\Manager\BookTransactionManager;
use App\Repository\UserBookCategoryRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/books_transactions")
 */
class BookTransactionController extends ApiController
{
    /**
     * @Route("", methods={"POST"})
     *
     * @param Request                    $request
     * @param UserBookCategoryRepository $userBookCategoryRepository
     * @param BookTransactionManager     $bookTransactionManager
     *
     * @return JsonResponse
     */
    public function create(
        Request $request,
        UserBookCategoryRepository $userBookCategoryRepository,
        BookTransactionManager $bookTransactionManager
    ) {
        $form = $this->createForm(CreateType::class, new CreateBookTransaction());
        $form->handleRequest($request);

        if (false === $form->isSubmitted()) {
            return new JsonResponse(['global' => 'The form isn\'t submitted']);
        }

        if (false === $form->isValid()) {
            return $this->errorResponse($form);
        }

        $dto = $form->getData();

        if ($dto->requester->getId() === $dto->keeper->getId()) {
            return new JsonResponse(['global' => 'The keeper and the requester is same user']);
        }

        $freeBook = $userBookCategoryRepository->findBy([
            'userId' => $dto->requester->getId(),
            'bookId' => $dto->book->getId(),
            'categoryId' => Category::FREE,
        ]);

        if (null === $freeBook) {
            return new JsonResponse(['global' => 'The keeper has\'t the book']);
        }

        try {
            $bookTransactionManager->createFromDTO($dto);
        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse(['global' => 'A transaction has been added yet.']);
        }

        return new JsonResponse();
    }
}
