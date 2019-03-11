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

use App\Models\City;
use App\Models\Country;

class ParseCity extends PageForParse
{
    private const URL_MAIN_PAGE = [
        'eng' => 'https://radiovolna.net/en',
        'ru' => 'https://radiovolna.net',
    ];

    private $urlCountries = [];
    private $NameCountries = [];

    public function __construct()
    {
        // Get names of countries

        $this->NameCountries = (new Country())->select('name_en', 'id')->get();

        // Get URL country pages

        $arrCountry = new ParseCountry();
        $this->urlCountries = $arrCountry->countryPageRu->filter('div.countries > div > ul > li > a')->each(function (Crawler $node) {
            $url = $node->attr('href');
            $arrUrl = [
                'eng' => self::URL_MAIN_PAGE['eng'] . $url,
                'ru' => self::URL_MAIN_PAGE['ru'] . $url,
            ];

            return $arrUrl;
        });
    }

    public function parseAllCitiesInfoToDB()
    {
        $urlCountries = $this->urlCountries;
        $NameCountries = $this->NameCountries;

        $cityInfo = [];

        // Get city information

        foreach ($urlCountries as $value) {

            $countryPageEn = $this->getPage($value['eng']);
            $countryPageRu = $this->getPage($value['ru']);

            $countryName = trim($countryPageEn->filter('div.path > span')->last()->text());
            $countryID = null;

            foreach ($NameCountries as $country) {
                if ($country->name_en === $countryName) {
                    $countryID = $country->id;
                    break;
                }
            }

            $cityArrEn = $countryPageEn->filter('div.cityes > ul > li > a')->each(function (Crawler $node) {
                return trim($node->text());
            });
            $cityArrRu = $countryPageRu->filter('div.cityes > ul > li > a')->each(function (Crawler $node) {
                return trim($node->text());
            });

            for ($i = 0; $i <= (count($cityArrRu) - 1); $i++) {
                $cityInfo[] = [
                    'name_en' => $cityArrEn[$i],
                    'name_ru' => $cityArrRu[$i],
                    'country_id' => $countryID,
                ];
            }
        }

        // Save to DB

        $city = new City;
        $city->insert($cityInfo);

        return $cityInfo;
    }
}