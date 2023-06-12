<?php
namespace App\Services;

use Illuminate\Support\Facades\Redis;

class CityService {
    
    private $brasil_api_link;
    private $ibge_api_link;
    private $redis;

    public function __construct(Redis $redis)
    {
        $this->brasil_api_link = env('BRASIL_API');
        $this->ibge_api_link = env('IBGE_API');
        $this->redis = $redis;
    }

    public function AllCities(){
        $data = $this->redis::get('cities');

        if (!$data) {
            $response = file_get_contents($this->brasil_api_link);
            $data = json_decode($response);

            $this->redis::set('cities', $response);
            $this->redis::expire('cities', 1800);

            return $data;
        }

        $cities = json_decode($data);

        return $cities;
    }

    public function cityDetails($city_id)
    {
        $data = $this->redis::get('city_details');

        if (!$data) {
            $response = file_get_contents($this->ibge_api_link . $city_id);
            $data = json_decode($response);

            $this->redis::set('city_details', $response);
            $this->redis::expire('city_details', 1800);

            return $data;
        }

        $city = json_decode($data);

        return $city;
    }
}