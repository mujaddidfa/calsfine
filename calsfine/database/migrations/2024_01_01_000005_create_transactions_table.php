<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('email');
            $table->string('wa_number')->nullable();
            $table->text('note')->nullable();
            $table->dateTime('order_date');
            $table->dateTime('pick_up_date');
            $table->foreignId('id_location')->constrained('locations')->onDelete('cascade');
            $table->decimal('total_price', 10, 2);
            $table->string('status');
            $table->dateTime('payment_date')->nullable();
            $table->string('qris_reference')->nullable();
            $table->dateTime('qris_expiry')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}