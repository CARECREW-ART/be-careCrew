<?php

use App\Models\Master\MCity;
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
        Schema::create('m_districts', function (Blueprint $table) {
            $table->id("district_id");
            $table->foreignIdFor(MCity::class,"city_id")->constrained("m_cities","city_id");
            $table->text("district_name");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_districts');
    }
};
