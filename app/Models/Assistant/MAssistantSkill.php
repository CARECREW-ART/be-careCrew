<?php

namespace App\Models\Assistant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MAssistantSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'assistant_id',
        'skill_name'
    ];
}
