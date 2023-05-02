<?php

namespace App\Models\Assistant;

use App\Models\Master\MGender;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MAssistant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assistant_fullname',
        'assistant_nickname',
        'assistant_username',
        'assistant_telp',
        'assistant_gender',
        'assistant_birthdate',
        'assistant_salary',
        'assistant_experience',
        'assistant_skills',
        'assistant_isactive'
    ];

    protected $primaryKey = 'assistant_id';

    public function mAssistantPicture()
    {
        return $this->hasOne(MAssistantPicture::class, 'assistant_id', 'assistant_id');
    }

    public function assistantGender()
    {
        return $this->hasOne(MGender::class, 'gender_bit', 'assistant_gender');
    }

    public function mAssistantAddress()
    {
        return $this->hasOne(MAssistantAddress::class, 'assistant_id', 'assistant_id');
    }

    public function mAssistantSkill()
    {
        return $this->hasMany(MAssistantSkill::class, 'assistant_id', 'assistant_id');
    }

    public function mAssistantCity($assistantId)
    {
        $data = DB::table('m_assistants')->leftJoin(
            'm_assistant_addresses',
            'm_assistants.assistant_id',
            '=',
            'm_assistant_addresses.assistant_id'
        )->leftJoin(
            'm_cities',
            'm_assistant_addresses.city_id',
            '=',
            'm_cities.city_id'
        )->where(
            'm_assistant_addresses.assistant_id',
            '=',
            $assistantId
        )->first();

        return $data;
    }

    public function mAssistantAccbank()
    {
        return $this->hasOne(MAssistantAccbank::class, 'assistant_id', 'assistant_id');
    }

    public function emailUser()
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }
}
