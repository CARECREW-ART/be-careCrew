<?php

use App\Models\Master\MProvince;
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
        Schema::create('m_cities', function (Blueprint $table) {
            $table->id("city_id");
            $table->foreignIdFor(MProvince::class,"province_id")->constrained("m_provinces","province_id");
            $table->text("city_name");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_cities');
    }
};
