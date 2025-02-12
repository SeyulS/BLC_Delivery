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
        Schema::create('LCL_delivery', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id');
            $table->string('destination');
            $table->double('max_volume_capacity');
            $table->double('max_weight_capacity');
            $table->double('current_volume_capacity');
            $table->double('current_weight_capacity');
            $table->double('price')->nullable();
            $table->string('pengiriman_duration');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
