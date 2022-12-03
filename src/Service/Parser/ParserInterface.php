<?php

namespace App\Service\Parser;

use App\Service\WebApi\WebApiInterface;

/**
 * Интерфейс парсера
 */
interface ParserInterface
{
    /**
     * Парсить страницу по указанному URL
     *
     * @return array
     */
    public function parse(): array;
}