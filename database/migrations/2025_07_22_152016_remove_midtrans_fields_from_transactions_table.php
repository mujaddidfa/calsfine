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
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'midtrans_order_id',
                'midtrans_snap_token', 
                'midtrans_transaction_id',
                'midtrans_transaction_status',
                'payment_method',
                'payment_date',
                'qris_reference',
                'qris_expiry'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('midtrans_order_id')->nullable()->after('pickup_code');
            $table->string('midtrans_snap_token')->nullable()->after('midtrans_order_id');
            $table->string('midtrans_transaction_id')->nullable()->after('midtrans_snap_token');
            $table->string('midtrans_transaction_status')->nullable()->after('midtrans_transaction_id');
            $table->string('payment_method')->nullable()->after('midtrans_transaction_status');
            $table->dateTime('payment_date')->nullable();
            $table->string('qris_reference')->nullable();
            $table->dateTime('qris_expiry')->nullable();
        });
    }
};
