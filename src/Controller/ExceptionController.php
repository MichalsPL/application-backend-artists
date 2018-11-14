<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ExceptionController extends AbstractController
{

  public function showException()
  {
    return new JsonResponse(
      [
        'error' => 'there is nothing here'
      ],
      JsonResponse::HTTP_CREATED
    );
  }
}
