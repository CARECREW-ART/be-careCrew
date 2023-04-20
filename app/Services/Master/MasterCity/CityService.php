<?php

namespace App\Services\Master\MasterCity;

use App\Models\Master\MProvince;

class CityService
{
    public function getCityByIdProvince($provinceId)
    {
        $data = MProvince::where('province_id', $provinceId)->with([
            'mCity' => function ($city) {
                $city->select('province_id', 'city_id', 'city_name');
            }
        ])->select('province_id', 'province_name')->get();

        return $data;
    }
}
