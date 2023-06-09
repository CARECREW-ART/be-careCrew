<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MDistrict extends Model
{
    use HasFactory;

    protected $fillable = [
        "district_id",
        "village_id",
        "village_name"
    ];

    public function mVillage()
    {
        return $this->hasMany(MVillage::class, 'district_id', 'district_id');
    }
}
