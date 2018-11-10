<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    protected function errorResponse(Form $form)
    {
        $errors = array();

        foreach ($form->getErrors() as $error) {
            $errors['global'][] = $error->getMessage();
        }

        foreach ($form as $child) {
            if ($child->isValid()) {
                continue;
            }

            foreach ($child->getErrors() as $error) {
                $errors[$child->getName()][] = $error->getMessage();
            }
        }

        return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
    }
}
