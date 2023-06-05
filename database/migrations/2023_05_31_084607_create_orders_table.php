<?php

use App\Models\Assistant\MAssistant;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id', 20)->unique();
            $table->foreignIdFor(MAssistant::class, 'assistant_id')->constrained('m_assistants', 'assistant_id');
            $table->foreignIdFor(MCustomer::class, 'customer_id')->constrained('m_customers', 'customer_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_price', 12, 2);
            $table->enum('payment_status', ['Created', 'Waiting For Payment', 'Success', 'Expired', 'Canceled']);
            $table->string('payment_type', 30)->nullable();
            $table->string('snap_token')->nullable();
            $table->boolean('bit_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
