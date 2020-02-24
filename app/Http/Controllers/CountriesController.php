<?php

namespace App\Http\Controllers;

use PragmaRX\Countries\Package\Countries;

class CountriesController extends Controller
{
    /**
     * Get All countries.
     * @return \Illuminate\Http\JsonResponse
     */
    public function countries()
    {
        $countries = new Countries();
        return $countries->all()->pluck('name.common')->toJson();
    }
}
