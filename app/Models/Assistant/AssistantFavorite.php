<?php

namespace App\Models\Assistant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssistantFavorite extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "assistant_id"
    ];

    public function mAssistant()
    {
        return $this->hasMany(MAssistant::class, "assistant_id", "assistant_id");
    }
}
