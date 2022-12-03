<?php

namespace App\Controller\API\V1;

use App\Service\Parser\ParserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Контроллер для работы с парсингом
 */
#[Route('/api/v1/parse', name: 'parse_')]
class ParseController extends AbstractController
{
    #[Route('/source', name: 'source', methods: ['GET'])]
    public function parseSource(ParserInterface $parser): Response
    {
        $elementArray = $parser->parse();
        return new JsonResponse($elementArray);
    }
}
