<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MBank extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_code',
        'bank_name'
    ];
}
