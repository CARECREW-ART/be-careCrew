<?php

namespace App\Services\Master\MasterGender;

use App\Models\Master\MGender;

class GenderService
{
    public function getGender()
    {
        $data = MGender::select('gender_bit', "gender_value")->get();

        return $data;
    }
}
