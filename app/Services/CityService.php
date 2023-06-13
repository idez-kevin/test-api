<?php
namespace App\Services;

use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
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

    public function AllCities($per_page = 30){
        $data = $this->redis::get('cities');

        if (!$data) {
            try {
                $response = file_get_contents($this->brasil_api_link);
            } catch (Exception $e) {
                return 'Não foi possível conectar com a BRASIL API!';
            }

            $data = json_decode($response);

            $this->redis::set('cities', $response);
            $this->redis::expire('cities', 1800);
        } else {
            $data = json_decode($data);
        }

        $cities = collect($data);

        $current_page = Paginator::resolveCurrentPage('page');

        $paged_data = $cities->slice(($current_page - 1) * $per_page, $per_page)->all();

        $paginated_cities = new LengthAwarePaginator(
            $paged_data,
            $cities->count(),
            $per_page,
            $current_page,
            ['path' => Paginator::resolveCurrentPath()]
        );

        return $paginated_cities;
    }

    public function cityDetails($city_id)
    {
        $data = $this->redis::get('city_details_' . $city_id);

        if (!$data) {
            try{
                $response = file_get_contents($this->ibge_api_link . $city_id . '?view=nivelado');
            } catch (Exception $e){
                return 'Não foi possível conectar com a API IBGE!';
            }
            $data = json_decode($response);

            $this->redis::set('city_details_' . $city_id, $response);
            $this->redis::expire('city_details_' . $city_id, 1800);

            return $data;
        }

        $city = json_decode($data);

        return $city;
    }
}