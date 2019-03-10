<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Moduls\Parse\ParseCountry;
use App\Moduls\Parse\ParseCity;
use App\Moduls\Parse\ParseJenre;
use App\Moduls\Parse\ParseRadioPage;


class MainParseController extends Controller
{
    public function show()
    {
        $country = (new ParseCountry())->parseAllCountriesInfo();
//        $city = (new ParseCity())->parseAllCitiesInfo();
//        $jenre = (new ParseJenre())->parseAllJenresInfo();
//        $radiostation = (new ParseRadioPage())->parseAllRadioInfo();

        dd($country);
    }
}
