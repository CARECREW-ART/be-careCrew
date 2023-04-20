<?php

use App\Models\User;
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
            $table->foreignIdFor(User::class, 'user_id')->constrained('users', 'user_id');
            $table->char("customer_fullname", 50);
            $table->char("customer_nickname", 50);
            $table->char("customer_username", 20)->unique();
            $table->char("customer_telp", 16)->unique();
            $table->boolean("customer_gender");
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
