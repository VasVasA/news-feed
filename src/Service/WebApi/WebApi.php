<?php

namespace App\Service\WebApi;

use Exception;

/**
 * Класс для запросов к ресурсам
 */
class WebApi implements WebApiInterface
{
    /**
     * @var string
     */
    private string $cookie = "";

    /**
     * @var string
     */
    private string $user_agent = 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:30.0) Gecko/20100101 Firefox/30.0';

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
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_COOKIEJAR => $this->cookie,
                CURLOPT_COOKIEFILE => $this->cookie,
                CURLOPT_USERAGENT => $this->user_agent
            ]
        );
        if ($isPost) {
            curl_setopt($curlDescriptor, CURLOPT_POSTFIELDS, $postFields);
        }
        $curlResponse = curl_exec($curlDescriptor);
        if (curl_errno($curlDescriptor)) {
            $errorMessage = curl_error($curlDescriptor);
        }
        $curlResponseCode = curl_getinfo($curlDescriptor, CURLINFO_HTTP_CODE);
        if ($curlResponse !== false && $curlResponseCode === 200) {
            if (!isset($errorMessage)) {
                return $curlResponse;
            } else {
                throw new Exception($errorMessage, $curlResponseCode);
            }
        } else {
            throw new Exception($curlResponse, $curlResponseCode);
        }
    }
}