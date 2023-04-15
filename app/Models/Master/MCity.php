<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MCity extends Model
{
    use HasFactory;

    

    public function distric()
    {
        return $this->hasMany(MDistrict::class);
    }
}
