<?php

use App\Models\Assistant\MAssistant;
use App\Models\Master\MCity;
use App\Models\Master\MDistrict;
use App\Models\Master\MPostalzip;
use App\Models\Master\MProvince;
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
        Schema::create('m_assistant_addresses', function (Blueprint $table) {
            $table->id("address_id");
            $table->foreignIdFor(MAssistant::class, 'assistant_id')->constrained('m_assistants', 'assistant_id');
            $table->foreignIdFor(MProvince::class, 'province_id')->constrained('m_provinces', 'province_id');
            $table->foreignIdFor(MCity::class, 'city_id')->constrained('m_cities', 'city_id');
            $table->foreignIdFor(MDistrict::class, 'district_id')->constrained('m_districts', 'district_id');
            $table->foreignIdFor(MVillage::class, 'village_id')->constrained('m_villages', 'village_id');
            $table->foreignIdFor(MPostalzip::class, 'postalzip_id')->nullable()->constrained('m_postalzips', 'postalzip_id');
            $table->text('address_street');
            $table->text('address_other');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_assistant_addresses');
    }
};
