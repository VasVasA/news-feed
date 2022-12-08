<?php

namespace App\Service\Parser;

use App\Service\WebApi\WebApiInterface;
use DateTime;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Exception;

/**
 * Класс для парсинга новостей с РБК
 */
class RbkParser implements ParserInterface
{

    public function __construct(
        private readonly string $sourceUrl,
        private readonly WebApiInterface $webApi,
        private readonly string $classname = "news-feed__item"
    ) {
    }

    /**
     * @param int $count
     * @return array
     * @throws Exception
     */
    public function parse(int $count = 15): array
    {
        $page = $this->makeRequestToSource($this->sourceUrl);
        $dom = $this->createDomDocument($page);

        return $this->parseNewsList($dom, $count);
    }

    /**
     * @param $sourceUrl
     * @return string
     */
    private function makeRequestToSource($sourceUrl): string
    {
        return $this->webApi->sendRequest($sourceUrl);
    }

    /**
     * @param string $page
     * @return DOMDocument
     */
    private function createDomDocument(string $page): DOMDocument
    {
        $dom = new DOMDocument();
        $internalErrors = libxml_use_internal_errors(true);
        $dom->loadHTML(html_entity_decode($page));
        libxml_use_internal_errors($internalErrors);

        return $dom;
    }

    /**
     * @param DOMDocument $dom
     * @param int $count
     * @return array
     * @throws Exception
     */
    private function parseNewsList(DOMDocument $dom, int $count): array
    {
        $resultNews = [];
        $itemList = $this->searchElementByClassAndTagNames($dom, $this->classname, 'a');
        foreach ($itemList as $item) {
            $news = ['source' => $this->sourceUrl];
            $news['url'] = $item->getAttribute('href');
            if (str_contains($news['url'], $this->sourceUrl)) {
                for ($i = 0; $i < $count; $i++) {
                    $child = $item->childNodes[$i];
                    if ($child instanceof DOMElement) {
                        $childClassName = $child->getAttribute('class');
                        if (str_contains($childClassName, 'news-feed__item__grid')) {
                            foreach ($child->childNodes as $newsContentBlock) {
                                foreach ($newsContentBlock->childNodes as $childContentBlock) {
                                    if ($childContentBlock instanceof DOMElement) {
                                        if (str_contains(
                                            $childContentBlock->getAttribute('class'),
                                            'news-feed__item__title'
                                        )) {
                                            $news['title'] = trim($childContentBlock->textContent);
                                        }
                                        if (str_contains(
                                            $childContentBlock->getAttribute('class'),
                                            'news-feed__item__date'
                                        )) {
                                            $childContentBlockList = explode(',', $childContentBlock->textContent);
                                            $news['category'] = trim($childContentBlockList[0]);
                                            $news['publicationDateTime'] = $this->createDateTimeForNews(
                                                substr(trim($childContentBlockList[1]), 2)
                                            );
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if (isset($news['title'])) {
                $fullData = $this->parseSingleNews($news['url']);
                $news = array_merge($news, $fullData);
                $resultNews[] = $news;
            }
        }

        return $resultNews;
    }

    /**
     * @param DOMDocument $dom
     * @param string $className
     * @param string $tagName
     * @return mixed
     */
    private function searchElementByClassAndTagNames(DOMDocument $dom, string $className, string $tagName): mixed
    {
        $finder = new DomXPath($dom);
        $expression = './/'.$tagName.'[contains(concat(" ", normalize-space(@class), " "), " '.$className.' ")]';

        return $finder->evaluate($expression);
    }

    /**
     * @throws Exception
     */
    private function createDateTimeForNews(string $time): DateTime
    {
        return new DateTime($time);
    }

    /**
     * @param string $url
     * @return string[]
     */
    private function parseSingleNews(string $url): array
    {
        $news = ['text' => ''];
        $page = $this->makeRequestToSource($url);
        $dom = $this->createDomDocument($page);
        $itemList = $this->searchElementByClassAndTagNames($dom, 'article__text', 'div');
        if (!is_null($itemList)) {
            foreach ($itemList[0]->childNodes as $item) {
                if ($item instanceof DOMElement) {
                    if ($item->tagName === 'p') {
                        $news['text'] .= trim($item->textContent).'<br>';
                    } elseif ($item->tagName === 'div' && $item->getAttribute('class') === 'article__main-image') {
                        $imageUrl = $item->
                        firstElementChild->firstElementChild->firstElementChild->getAttribute('srcset');
                        $news['image'] = explode(' ', $imageUrl)[0];
                    }
                }
            }
        }

        return $news;
    }

}