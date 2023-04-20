<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MProvince extends Model
{
    use HasFactory;

    protected $fillable = [
        "province_id",
        "province_name"
    ];

    public function mCity()
    {
        return $this->hasMany(MCity::class, 'province_id', 'province_id');
    }
}
