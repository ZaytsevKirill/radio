<?php
/**
 * Created by PhpStorm.
 * User: yafus
 * Date: 08.02.19
 * Time: 22:09
 */

/**
 * This class get data of jenres
 */

namespace App\Moduls\Parse;

use Symfony\Component\DomCrawler\Crawler;


class ParseJenre extends PageForParse
{
    private const URL_JENRE_PAGE = 'https://radiovolna.net/genre/';

    private $jenrePage;

    // Create new object for parse

    public function __construct()
    {
        $this->jenrePage = $this->getPage(self::URL_JENRE_PAGE);
    }

    // Get data of jenres

    public function parseAllJenresInfo()
    {
        $dataJenres = [];

        $nameGroupJenres = $this->jenrePage->filter('div.genres.row  div.item-title > span')->each(function (Crawler $node) {
            return trim($node->text());
        });

        foreach ($nameGroupJenres as $nameGroup) {
            $dataJenres[]['name_group'] = $nameGroup;
        }

        $jenreArr = $this->jenrePage->filter('div.genres.row  ul')->each(function (Crawler $node) {
            return $node->filter('li')->each(function (Crawler $node) {
                return trim($node->text());
            });
        });

        for ($i=0; $i<= count($dataJenres)-1; $i++) {
                $dataJenres[$i]['jenres'] = $jenreArr[$i];
        }

        return $dataJenres;
    }

}