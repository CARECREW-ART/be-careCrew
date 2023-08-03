<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'otp_code',
        'expire_otp_code'
    ];

    public $timestamps = false;

    protected $primaryKey = 'email';
}