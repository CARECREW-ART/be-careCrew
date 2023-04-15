<?php

use App\Models\Assistant\MAssistant;
use App\Models\Master\MBank;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('m_assistant_accbanks', function (Blueprint $table) {
            $table->id("accbank_id");
            $table->foreignIdFor(MAssistant::class, "assistant_id")->constrained('m_assistants', 'assistant_id');
            $table->foreignIdFor(MBank::class, "bank_id")->constrained('m_banks', 'bank_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_assistant_accbanks');
    }
};
