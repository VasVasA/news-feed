<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Контроллер для работы с парсингом
 */
class ParseController extends AbstractController
{
    #[Route('/parse', name: 'app_parse')]
    public function index(): Response
    {
        return new JsonResponse();
    }
}
