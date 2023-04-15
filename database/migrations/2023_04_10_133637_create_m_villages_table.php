<?php

use App\Models\Master\MDistrict;
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
        Schema::create('m_villages', function (Blueprint $table) {
            $table->id("village_id");
            $table->foreignIdFor(MDistrict::class,"district_id")->constrained("m_districts","district_id");
            $table->text("village_name");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_villages');
    }
};
