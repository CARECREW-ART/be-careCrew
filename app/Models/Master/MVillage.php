<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MVillage extends Model
{
    use HasFactory;

    public function mPostalzip()
    {
        return $this->hasMany(MPostalzip::class, 'village_id', 'village_id');
    }
}
