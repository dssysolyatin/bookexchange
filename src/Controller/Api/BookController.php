<?php

namespace App\Controller\Api;

use App\Book\Manager;
use App\DTO\Request\InsertBookCategoryDTO;
use App\Exception\DuplicationUserCategoryBookException;
use App\Exception\GoogleNotFoundBookException;
use App\Form\Type\Book\InsertBookCategoryType;
use App\Repository\BookRepository;
use Google_Service_Books;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/books")
 */
class BookController extends ApiController
{
    /**
     * @Route("/search")
     * @param Request $request
     * @param Google_Service_Books $googleBookService
     * @return JsonResponse
     */
    public function search(Request $request, Manager $bookManager)
    {
        $searchString = $request->query->get('q');

        if (mb_strlen($searchString) < 3) {
            return new JsonResponse([]);
        }

        return new JsonResponse(
            $bookManager->getBooksFromGoogleBook($searchString, [
                'maxResults' => 20,
                'startIndex' => $request->query->get('start_index', 0)
            ])
        );
    }

    /**
     * @Route("/free_book_search")
     * @param Request $request
     * @param BookRepository $bookRepository
     * @return JsonResponse|void
     */
    public function freeBookSearch(Request $request, BookRepository $bookRepository, UserInterface $user)
    {
        $searchString = $request->query->get('q');

        if (mb_strlen($searchString) < 3) {
            return new JsonResponse([]);
        }

        return new JsonResponse($bookRepository->searchFreeBooks($searchString, $user->getId()));
    }


    /**
     * @Route("", methods={"GET"})
     * @param UserInterface $user
     */
    public function index(UserInterface $user, BookRepository $bookRepository)
    {
        return new JsonResponse($bookRepository->getUserBooks($user->getId()));
    }

    /**
     * @Route("/link", methods={"POST"})
     * @param Manager $bookManager
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function link(Manager $bookManager, Request $request, UserInterface $user)
    {
        $form = $this->createForm(InsertBookCategoryType::class, new InsertBookCategoryDTO());

        $form->handleRequest($request);

        if (false === $form->isSubmitted() || false === $form->isValid()) {
            return  $this->errorResponse($form);
        }

        $dto = $form->getData();

        try {
            $category = $bookManager->create($user, $dto->categoryId, $dto->bookId);
        } catch (DuplicationUserCategoryBookException $e) {
            return new JsonResponse(
                ['global' => 'The user has added this book yet.'],
                Response::HTTP_BAD_REQUEST
            );
        } catch (GoogleNotFoundBookException $e) {
            return new JsonResponse(
                ['bookId' => 'The book with current id doesn\'t find in Google Books.'],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse($category);
    }

}