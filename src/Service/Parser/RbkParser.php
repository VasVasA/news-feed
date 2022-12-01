<?php

namespace App\Service\Parser;

use App\Service\WebApi\WebApiInterface;

class RbkParser implements ParserInterface
{

    public function __construct(private readonly string $sourceUrl, private readonly WebApiInterface $webApi)
    {
    }

    public function parse(): array
    {
        $page = $this->webApi->sendRequest($this->sourceUrl);
        return [];
    }

}