<?php

namespace App\Models\Assistant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MAssistantPicture extends Model
{
    use HasFactory;

    protected $fillable = [
        'assistant_id',
        'picture_filename',
        'picture_imagename',
        'picture_mime',
        'picture_path'
    ];
}
