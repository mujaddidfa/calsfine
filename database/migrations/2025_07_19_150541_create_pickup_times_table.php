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
        Schema::create('pickup_times', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_id');
            $table->time('pickup_time'); // Hanya menyimpan jam (HH:MM:SS)
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            
            // Index untuk performa query
            $table->index(['location_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pickup_times');
    }
};
