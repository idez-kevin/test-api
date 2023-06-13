<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\CityService;

class CityController extends Controller
{
    private $cityService;

    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }
    
    public function cities()
    {
        $cities = $this->cityService->AllCities();

        return $cities;
    }

    public function details($city_id)
    {
        $details = $this->cityService->cityDetails($city_id);

        return $details;
    }
}
