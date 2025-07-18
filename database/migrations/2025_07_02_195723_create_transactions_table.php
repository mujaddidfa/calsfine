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
        if (!Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->string('customer_name', 100);
                $table->string('wa_number', 20);
                $table->text('note')->nullable();
                $table->dateTime('order_date');
                $table->dateTime('pick_up_date');
                $table->foreignId('location_id')->nullable()->constrained('locations')->cascadeOnUpdate()->nullOnDelete();
                $table->double('total_price');
                $table->enum('status', ['pending', 'paid', 'completed', 'cancelled', 'wasted'])->default('pending');
                $table->dateTime('payment_date')->nullable();
                $table->string('qris_reference')->nullable();
                $table->dateTime('qris_expiry')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
