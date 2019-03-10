<?php
/**
 * Created by PhpStorm.
 * User: yafus
 * Date: 28.01.19
 * Time: 23:04
 */

namespace App\Moduls\Parse;

use Symfony\Component\DomCrawler\Crawler;

class PageForParse
{
    protected function getPage(string $link)
    {
        // Get html remote text.
        $html = file_get_contents($link);

        // Create new instance for parser.
        $crawler = new Crawler(null, $link);
        $crawler->addHtmlContent($html, 'UTF-8');

        return $crawler;
    }
}