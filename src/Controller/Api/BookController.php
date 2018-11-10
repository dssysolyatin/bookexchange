<?php

namespace App\Controller\Api;

use Google_Service_Books;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/books")
 */
class BookController
{
    /**
     * @Route("/search")
     * @param Request $request
     * @param Google_Service_Books $googleBookService
     * @return JsonResponse
     */
    public function search(Request $request, Google_Service_Books $googleBookService)
    {
        $searchString = $request->query->get('q');

        if (mb_strlen($searchString) < 3) {
            return new JsonResponse([]);
        }



        return new JsonResponse(
            $googleBookService->volumes->listVolumes($searchString, [
                'maxResults' => 20,
                'startIndex' => $request->query->get('start_index', 0)
            ])
        );
    }

}