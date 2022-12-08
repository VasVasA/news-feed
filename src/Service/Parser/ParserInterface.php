<?php

namespace App\Service\Parser;

use App\Service\WebApi\WebApiInterface;

/**
 * Интерфейс парсера
 */
interface ParserInterface
{
    /**
     * Парсим страницу, получаем список новостей
     *
     * @param int $count
     * @return array
     */
    public function parse(int $count): array;
}