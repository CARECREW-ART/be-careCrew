<?php

namespace App\Services\Master\MasterDistrict;

use App\Models\Master\MCity;

class DistrictService
{
    public function getDistrictByIdCity($cityId)
    {
        $data = MCity::where('city_id', $cityId)->with([
            'mDistrict' => function ($district) {
                $district->select('city_id', 'district_id', 'district_name');
            }
        ])->select('city_id', 'city_name')->get();

        return $data;
    }
}
