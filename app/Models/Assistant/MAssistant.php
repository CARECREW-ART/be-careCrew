<?php

namespace App\Models\Assistant;

use App\Models\Master\MBank;
use App\Models\Master\MGender;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
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
        )->select(
            'm_assistants.assistant_id',
            'm_cities.city_id',
            'm_cities.city_name'
        )->first();

        return $data;
    }

    public function mAssistantProvince($assistantId)
    {
        $data = DB::table('m_assistants')->leftJoin(
            'm_assistant_addresses',
            'm_assistants.assistant_id',
            '=',
            'm_assistant_addresses.assistant_id'
        )->leftJoin(
            'm_provinces',
            'm_assistant_addresses.province_id',
            '=',
            'm_provinces.province_id'
        )->where(
            'm_assistant_addresses.assistant_id',
            '=',
            $assistantId
        )->select(
            'm_assistants.assistant_id',
            'm_provinces.province_id',
            'm_provinces.province_name'
        )->first();

        return $data;
    }

    public function mAssistantDistrict($assistantId)
    {
        $data = DB::table('m_assistants')->leftJoin(
            'm_assistant_addresses',
            'm_assistants.assistant_id',
            '=',
            'm_assistant_addresses.assistant_id'
        )->leftJoin(
            'm_districts',
            'm_assistant_addresses.district_id',
            '=',
            'm_districts.district_id'
        )->where(
            'm_assistant_addresses.assistant_id',
            '=',
            $assistantId
        )->select(
            'm_assistants.assistant_id',
            'm_districts.district_id',
            'm_districts.district_name'
        )->first();

        return $data;
    }

    public function mAssistantVillage($assistantId)
    {
        $data = DB::table('m_assistants')->leftJoin(
            'm_assistant_addresses',
            'm_assistants.assistant_id',
            '=',
            'm_assistant_addresses.assistant_id'
        )->leftJoin(
            'm_villages',
            'm_assistant_addresses.village_id',
            '=',
            'm_villages.village_id'
        )->where(
            'm_assistant_addresses.assistant_id',
            '=',
            $assistantId
        )->select(
            'm_assistants.assistant_id',
            'm_villages.village_id',
            'm_villages.village_name'
        )->first();

        return $data;
    }

    public function mAssistantPostalZip($assistantId)
    {
        $data = DB::table('m_assistants')->leftJoin(
            'm_assistant_addresses',
            'm_assistants.assistant_id',
            '=',
            'm_assistant_addresses.assistant_id'
        )->leftJoin(
            'm_postalzips',
            'm_assistant_addresses.postalzip_id',
            '=',
            'm_postalzips.postalzip_id'
        )->where(
            'm_assistant_addresses.assistant_id',
            '=',
            $assistantId
        )->select(
            'm_assistants.assistant_id',
            'm_postalzips.postalzip_id',
            'm_postalzips.postalzip_value'
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mAssistantBankName($assistantId)
    {
        $data = DB::table('m_assistants')->leftJoin(
            'm_assistant_accbanks',
            'm_assistants.assistant_id',
            '=',
            'm_assistant_accbanks.assistant_id'
        )->leftJoin(
            'm_banks',
            'm_assistant_accbanks.bank_id',
            '=',
            'm_banks.bank_id'
        )->where(
            'm_assistant_accbanks.assistant_id',
            '=',
            $assistantId
        )->select(
            'm_assistants.assistant_id',
            'accbank_id',
            'm_banks.bank_id',
            'accbank_name',
            'accbank_value',
            'bank_name'
        )->first();

        return $data;
    }
}
