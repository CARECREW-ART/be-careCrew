<?php

use App\Models\Assistant\MAssistant;
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
        Schema::create('m_assistant_pictures', function (Blueprint $table) {
            $table->id("picture_id");
            $table->foreignIdFor(MAssistant::class, 'assistant_id')->constrained('m_assistants', 'assistant_id');
            $table->string("picture_filename");
            $table->string("picture_imagename");
            $table->string("picture_mime", 8);
            $table->string("picture_path");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_assistant_pictures');
    }
};
