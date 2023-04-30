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
        Schema::create('m_assistants', function (Blueprint $table) {
            $table->id("assistant_id");
            $table->foreignIdFor(User::class, 'user_id')->constrained('users', 'user_id');
            $table->text("assistant_fullname");
            $table->string("assistant_nickname", 50);
            $table->string("assistant_username", 20)->unique();
            $table->string("assistant_telp", 16)->unique();
            $table->boolean("assistant_gender");
            $table->date("assistant_birthdate");
            $table->decimal("assistant_salary", 12, 2);
            $table->longText("assistant_experience");
            $table->longText("assistant_skills");
            $table->boolean("assistant_isactive");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_assistants');
    }
};
