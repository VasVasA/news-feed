<?php

namespace App\Service\Parser;

use App\Service\WebApi\WebApiInterface;
use DOMDocument;
use DOMXPath;

class RbkParser implements ParserInterface
{

    public function __construct(
        private readonly string $sourceUrl,
        private readonly WebApiInterface $webApi,
        private readonly string $classname = "news-feed__item"
    ) {
    }

    public function parse(): array
    {
        $page = $this->webApi->sendRequest($this->sourceUrl);
        $dom = new DOMDocument();
        $internalErrors = libxml_use_internal_errors(true);
        $dom->loadHTML(html_entity_decode($page));
        libxml_use_internal_errors($internalErrors);
        $finder = new DomXPath($dom);
        $expression = './/div[contains(concat(" ", normalize-space(@class), " "), " '.$this->classname.' ")]';
        $itemList = $finder->evaluate($expression);
        foreach ($itemList as $item) {

        }

        return json_encode($page);
    }

}