<?php

namespace App\Controller\API\V1;

use App\Entity\News;
use App\Service\NewsBuilder;
use App\Service\Parser\ParserInterface;
use Doctrine\Persistence\ManagerRegistry;
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
    public function parseSource(
        ManagerRegistry $doctrine,
        ParserInterface $parser,
        NewsBuilder $newsBuilder
    ): Response {
        $entityManager = $doctrine->getManager(News::class);
        $elementArray = $parser->parse();
        foreach ($elementArray as $newsFields) {
            $news = $newsBuilder->buildNews($newsFields);
            $entityManager->persist($news);
        }
        $entityManager->flush();

        return new JsonResponse($elementArray);
    }
}
