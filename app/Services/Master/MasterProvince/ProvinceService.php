<?php

namespace App\Services\Master\MasterProvince;

use App\Models\Master\MProvince;

class ProvinceService
{
    public function getProvince()
    {
        $dataProvince = MProvince::select()->get();
        return $dataProvince;
    }
}
