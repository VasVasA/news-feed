<?php

namespace App\Service\WebApi;

/**
 * Интерфейс для Web API
 */
interface WebApiInterface
{
    /**
     * Сделать запрос к ресурсу
     *
     * @param string $url
     * @param bool $isPost
     * @param array $postFields
     * @return mixed
     */
    public function sendRequest(string $url, bool $isPost, array $postFields): string;
}