<?php

namespace App\Models\Assistant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MAssistantAccbank extends Model
{
    use HasFactory;

    protected $fillable = [
        'assistant_id',
        'bank_id',
        'accbank_name',
        'accbank_value'
    ];

    protected $primaryKey = 'accbank_id';
}
