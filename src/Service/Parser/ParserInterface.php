<?php

namespace App\Service\Parser;


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