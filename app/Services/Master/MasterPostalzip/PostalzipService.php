<?php

namespace App\Services\Master\MasterPostalzip;

use App\Models\Master\MVillage;

class PostalzipService
{
    public function getPostalzipByIdVillage($villageId)
    {
        $data = MVillage::where('village_id', $villageId)->with([
            'mPostalzip' => function ($postalZip) {
                $postalZip->select('postalzip_id', 'village_id',  'postalzip_value');
            }
        ])->select('village_id', 'village_name')->get();

        return $data;
    }
}
