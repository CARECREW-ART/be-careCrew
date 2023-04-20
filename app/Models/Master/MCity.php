<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MCity extends Model
{
    use HasFactory;

    public function mDistrict()
    {
        return $this->hasMany(MDistrict::class, 'city_id', 'city_id');
    }
}
