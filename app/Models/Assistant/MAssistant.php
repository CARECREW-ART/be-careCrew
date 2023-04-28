<?php

namespace App\Models\Assistant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MAssistant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assistant_fullname',
        'assistant_nickname',
        'assistant_username',
        'assistant_email',
        'assistant_telp',
        'assistant_gender',
        'assistant_birthdate',
        'assistant_salary',
        'assistant_experience',
        'assistant_password',
        'assistant_isactive'
    ];

    protected $primaryKey = 'assistant_id';
}
