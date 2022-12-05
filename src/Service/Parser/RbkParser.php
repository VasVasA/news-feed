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
        $resultNews = [];
        $page = $this->webApi->sendRequest($this->sourceUrl);
        $dom = new DOMDocument();
        $internalErrors = libxml_use_internal_errors(true);
        $dom->loadHTML(html_entity_decode($page));
        libxml_use_internal_errors($internalErrors);
        $finder = new DomXPath($dom);
        $expression = './/a[contains(concat(" ", normalize-space(@class), " "), " '.$this->classname.' ")]';
        $itemList = $finder->evaluate($expression);
        foreach ($itemList as $item) {
            $news['url'] = $item->getAttribute('href');
            foreach ($item->childNodes as $child) {
                if ($child instanceof \DOMElement) {
                    $childClassName = $child->getAttribute('class');
                    if (str_contains($childClassName, 'news-feed__item__grid')) {
                        foreach ($child->childNodes as $newsContentBlock) {
                            foreach ($newsContentBlock->childNodes as $childContentBlock) {
                                if ($childContentBlock instanceof \DOMElement) {
                                    if (str_contains(
                                        $childContentBlock->getAttribute('class'),
                                        'news-feed__item__title'
                                    )) {
                                        $news['title'] = $childContentBlock->textContent;
                                    }
                                    if (str_contains(
                                        $childContentBlock->getAttribute('class'),
                                        'news-feed__item__date'
                                    )) {
                                        $news['date'] = $childContentBlock->textContent;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $resultNews[] = $news;
        }

        return $resultNews;
    }

}