<?php

namespace App\Models\Assistant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MAssistantAddress extends Model
{
    use HasFactory;
    protected $fillable = [
        'assistant_id',
        'province_id',
        'city_id',
        'district_id',
        'village_id',
        'postalzip_id',
        'address_street',
        'address_other'
    ];
}
