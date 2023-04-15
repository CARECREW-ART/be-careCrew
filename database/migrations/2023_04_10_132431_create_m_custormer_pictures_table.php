<?php

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
        Schema::create('m_custormer_pictures', function (Blueprint $table) {
            $table->id("picture_id");
            $table->unsignedBigInteger("customer_id");
            $table->text("picture_filename");
            $table->text("picture_imagename");
            $table->text("picture_type");
            $table->text("picture_path");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_custormer_pictures');
    }
};
