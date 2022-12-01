<?php

namespace App\Service\WebApi;

use Exception;

/**
 * Класс для запросов к ресурсам
 */
class WebApi implements WebApiInterface
{
    /**
     * Сделать запрос к ресурсу
     *
     * @param string $url
     * @param bool $isPost
     * @param array $postFields
     * @return string
     * @throws Exception
     */
    public function sendRequest(string $url, bool $isPost = false, array $postFields = []): string
    {
        $curlDescriptor = curl_init();
        curl_setopt_array(
            $curlDescriptor, [
                CURLOPT_URL => $url,
                CURLOPT_POST => $isPost,
                CURLOPT_POSTFIELDS => $postFields,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FAILONERROR => true
            ]
        );
        $curlResponse = curl_exec($curlDescriptor);
        if (curl_errno($curlDescriptor)) {
            $errorMessage = curl_error($curlDescriptor);
        }
        if ($curlResponse !== false) {
            if (!isset($errorMessage)) {
                return $curlResponse;
            } else {
                throw new Exception($errorMessage, curl_getinfo($curlDescriptor, CURLINFO_HTTP_CODE));
            }
        } else {
            throw new Exception('Unknown error', 503);
        }
    }
}