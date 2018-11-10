<?php

namespace App\Controller\Api;

use App\Book\Manager;
use App\DTO\Request\InsertBookCategoryDTO;
use App\Form\Type\Book\InsertBookCategoryType;
use Google_Service_Books;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/books")
 */
class BookController extends Controller
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
     * @Route("", methods={"POST"})
     * @param Manager $bookManager
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function create(Manager $bookManager, Request $request, ValidatorInterface $validator)
    {
        $form = $this->createForm(InsertBookCategoryType::class, new InsertBookCategoryDTO());

        $form->handleRequest($request);

        if (false === $form->isSubmitted() && false === $form->isValid()) {
            return new JsonResponse($form->getErrors());
        }

        $book = $bookManager->createOrGetBookByVolumeId($request->request->get('volume_id'));

        return new JsonResponse(\App\DTO\Response\Book::createByBookEntity($book));
    }

}