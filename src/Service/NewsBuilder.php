<?php

namespace App\Service;

use App\Entity\News;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class NewsBuilder
{
    public function buildNews(array $newsFields): News
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $news = $serializer->deserialize($newsFields, News::class, 'json');
        /** @var News $news */
        $news->setRating(rand(1, 10));

        return $news;
    }
}