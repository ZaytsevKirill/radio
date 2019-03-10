<?php
/**
 * Created by PhpStorm.
 * User: yafus
 * Date: 28.01.19
 * Time: 22:19
 */

/**
 * This class get data of countries
 */

namespace App\Moduls\Parse;

use Symfony\Component\DomCrawler\Crawler;
use App\Models\Country;

class ParseCountry extends PageForParse
{
    private const URL_COUNTRY_PAGE = [
        'eng_country' => 'https://radiovolna.net/en/countries/',
        'ru_country' => 'https://radiovolna.net/countries/',
    ];

    public $countryPageEng;
    public $countryPageRu;

    // Create new object for parse

    public function __construct()
    {
        $this->countryPageEng = $this->getPage(self::URL_COUNTRY_PAGE['eng_country']);
        $this->countryPageRu = $this->getPage(self::URL_COUNTRY_PAGE['ru_country']);
    }


    // Get country information in english

    private function parseEngCountryInfo()
    {
        return $this->countryPageEng->filter('li > a > img')->each(function (Crawler $node) {

            $nameEng = $node->attr('alt');

            $arrCountry = [
                'name_en' => $nameEng,
                'src' => $node->attr('src'),
                'flag_name' => str_replace(" ", '', $nameEng) . '.gif',
            ];

            return $arrCountry;
        });
    }

    // Get country information in russian

    private function parseRuCountryInfo()
    {
        return $this->countryPageRu->filter('li > a > img')->each(function (Crawler $node) {

            $nameRu = $node->attr('alt');

            $arrCountry = [
                'name_ru' => $nameRu,
                'src' => $node->attr('src'),
            ];

            return $arrCountry;
        });
    }

    // Copy images of the flags

    private function getImages(array $countryArr)
    {
        foreach ($countryArr as $arr) {
            foreach ($arr as $key => $value) {
                if ($key === 'name_en') {
                    $destinFold = public_path('/img/flags/') . $value . '.gif';
                    copy($arr['src'], $destinFold);
                }
            }
        }
    }

    // Get all data of countries

    public function parseAllCountriesInfoToDB()
    {
        $countryInfoEng = $this->parseEngCountryInfo();
        $countryInfoRu = $this->parseRuCountryInfo();
        $this->getImages($countryInfoEng);
        $countryInfo = [];

        for ($i = 0; $i <= (count($countryInfoRu) - 1); $i++) {
            for ($j = 0; $j <= (count($countryInfoEng) - 1); $j++) {
                if ($countryInfoRu[$i]['src'] === $countryInfoEng[$j]['src']) {
                    $countryInfo[] = [
                        'name_ru' => trim($countryInfoRu[$i]['name_ru']),
                        'name_en' => trim($countryInfoEng[$j]['name_en']),
                        'flag_name' => trim($countryInfoEng[$j]['flag_name']),
                    ];
                    continue;
                }
            }
        }

        $countries = new Country;
        $countries->insert($countryInfo);


        return $countryInfo;
    }
}