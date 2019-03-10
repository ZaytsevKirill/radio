<?php
/**
 * Created by PhpStorm.
 * User: yafus
 * Date: 04.02.19
 * Time: 23:21
 */

/**
 * This class get data of cities
 */

namespace App\Moduls\Parse;

use Symfony\Component\DomCrawler\Crawler;

class ParseCity extends PageForParse
{
    private const URL_MAIN_PAGE = [
        'eng' => 'https://radiovolna.net/en',
        'ru' => 'https://radiovolna.net',
    ];

    private $urlCountries = [];

    // Get URL country pages

    public function __construct()
    {
        $arrCountry = new ParseCountry();
        $this->urlCountries = $arrCountry->countryPageRu->filter('div.countries > div > ul > li > a')->each(function (Crawler $node) {
            $url = $node->attr('href');
            $arrUrl = [
                'url_eng' => self::URL_MAIN_PAGE['eng'] . $url,
                'url_ru' => self::URL_MAIN_PAGE['ru'] . $url,
            ];

            return $arrUrl;
        });
    }

    // Get city information

    public function parseAllCitiesInfo()
    {
        $urlCountries = $this->urlCountries;

        foreach ($urlCountries as &$value) {
            foreach ($value as $key => $url) {

                $countryPage = $this->getPage($url);
                $countryName = trim($countryPage->filter('div.path > span')->last()->text());
                $cityArr = $countryPage->filter('div.cityes > ul > li > a')->each(function (Crawler $node) {
                    return trim($node->text());
                });

                $value[$key] = [$countryName => $cityArr];
            }
        }
        unset($value);

        return $urlCountries;
    }
}