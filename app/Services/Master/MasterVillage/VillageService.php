<?php

namespace App\Services\Master\MasterVillage;

use App\Models\Master\MDistrict;

class VillageService
{
    public function getVillageByIdDistrict($districtId)
    {
        $data = MDistrict::where('district_id', $districtId)->with([
            'mVillage' => function ($village) {
                $village->select('village_id', 'district_id', 'village_name');
            }
        ])->select('district_id', 'district_name')->get();

        return $data;
    }
}
