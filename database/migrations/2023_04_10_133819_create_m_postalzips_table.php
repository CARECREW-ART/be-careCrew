<?php

use App\Models\Master\MVillage;
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
        Schema::create('m_postalzips', function (Blueprint $table) {
            $table->id("postalzip_id");
            $table->foreignIdFor(MVillage::class,"village_id")->constrained("m_villages","village_id");
            $table->text("postalzip_value");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_postalzips');
    }
};
