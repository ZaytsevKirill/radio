<?php
/**
 * Created by PhpStorm.
 * User: yafus
 * Date: 09.02.19
 * Time: 15:17
 */

/**
 * This class get data of radio stations
 */

namespace App\Moduls\Parse;

use Symfony\Component\DomCrawler\Crawler;

class ParseRadioPage extends PageForParse
{
    private const URL_RADIO_PAGE = 'https://radiovolna.net/radio/';
    private const URL_MAIN_PAGE = 'https://radiovolna.net';

    private $numPages;

    public function __construct()
    {
        $firstRadioPage = $this->getPage(self::URL_RADIO_PAGE);
        $this->numPages = $firstRadioPage->filter('div.pages li.link-pages > a')->last()->attr('data-page');
    }

    //Get radio station page links

    public function getUrlPagesForParse()
    {
        $urlRadio = [];
        $numPages = $this->numPages;

        for ($i = 0; $i <= $numPages; $i++) {
            $urlForSearch = self::URL_RADIO_PAGE . $i . '/';
            $Page = $this->getPage($urlForSearch);

            $urlRadio[] = $Page->filter('div.radio-stations a')->each(function (Crawler $node) {
                return trim($node->attr('href'));
            });
        }

        return $urlRadio;
    }

    // Copy images of the logos

    private function getImages(string $srcImage, string $logoName)
    {
        $logoName = substr($logoName, 1, -5);
        $destinFold = public_path('/img/logo/') . $logoName . '.jpg';
        copy($srcImage, $destinFold);
        return $logoName . '.jpg';
    }

    // Get the radio stations data

    public function parseAllRadioInfo()
    {
        $radioArr = [];

        $urlRadio = $this->getUrlPagesForParse();

        foreach ($urlRadio as $urlArr) {
            foreach ($urlArr as $url) {
                $radioPage = $this->getPage(self::URL_MAIN_PAGE . $url);

                $nameRadio = trim($radioPage->filter('div.row h1')->text());
                $urlStream = trim($radioPage->filter('button.jp-play')->attr('data-stream'));
                $srcImage = $radioPage->filter('div.row > div > figure > img')->attr('src');
                $jenre = $radioPage->filter('div.artist-tags > a')->each(function (Crawler $node) {
                    return trim($node->text());
                });
                $geoData = explode(',', $radioPage->filter('div.artist-country > span')->text());
                $country = trim($geoData[0]);
                $city = array_key_exists(1, $geoData) ? trim($geoData[1]) : null;
                $logo = $this->getImages($srcImage, $url);

                $radioArr[] = [
                    'name_radio' => $nameRadio,
                    'url_radio' => $urlStream,
                    'logo' => $logo,
                    'jenre' => $jenre,
                    'country' => $country,
                    'city' => $city,
                ];
            }
        }

        return $radioArr;
    }

}