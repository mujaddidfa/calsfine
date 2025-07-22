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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->string('payment_gateway')->default('midtrans'); // midtrans, gopay, etc
            $table->string('gateway_order_id')->nullable();
            $table->string('gateway_transaction_id')->nullable();
            $table->text('snap_token')->nullable();
            $table->string('payment_method')->nullable(); // credit_card, bank_transfer, etc
            $table->string('status')->default('pending'); // pending, success, failed, expired
            $table->decimal('amount', 10, 2);
            $table->json('gateway_response')->nullable(); // Store full response
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->index(['transaction_id', 'status']);
            $table->index('gateway_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
