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
        Schema::create('m_customers', function (Blueprint $table) {
            $table->id("customer_id");
            $table->char("customer_fullname",50);
            $table->char("customer_nickname",50);
            $table->char("customer_username",20);
            $table->char("customer_email",50);
            $table->char("customer_telp",50);
            $table->boolean("customer_gender");
            $table->text("customer_password");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_customers');
    }
};
