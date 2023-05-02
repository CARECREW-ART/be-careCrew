<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MCustormerPicture extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'picture_filename',
        'picture_imagename',
        'picture_type',
        'picture_path',
    ];
}
