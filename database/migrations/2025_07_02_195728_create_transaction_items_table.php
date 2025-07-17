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
        if (!Schema::hasTable('transaction_items')) {
            Schema::create('transaction_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('transaction_id')->nullable()->constrained('transactions')->cascadeOnUpdate()->cascadeOnDelete();
                $table->foreignId('menu_id')->nullable()->constrained('menus')->cascadeOnUpdate();
                $table->integer('qty');
                $table->double('price_per_item');
                $table->double('total_price');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
