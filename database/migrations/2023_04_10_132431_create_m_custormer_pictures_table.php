<?php

use App\Models\Customer\MCustomer;
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
            $table->foreignIdFor(MCustomer::class, 'customer_id')->constrained('m_customers', 'customer_id');
            $table->text("picture_filename");
            $table->text("picture_imagename");
            $table->string("picture_mime", 8);
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
