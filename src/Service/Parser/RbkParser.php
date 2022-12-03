<?php

namespace App\Service\Parser;

use App\Service\WebApi\WebApiInterface;
use DOMDocument;

class RbkParser implements ParserInterface
{

    public function __construct(private readonly string $sourceUrl, private readonly WebApiInterface $webApi)
    {
    }

    public function parse(): array
    {
        $page = $this->webApi->sendRequest($this->sourceUrl);
        $dom = new DOMDocument();
        $dom->loadHTML($page);
        return json_encode($page);
    }

}