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
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id();
            $table->string('tujuan_pengiriman');
            $table->string('jalur_pengiriman');
            $table->string('jenis_pengiriman')->nullable();
            $table->double('pengiriman_volume_capacity');
            $table->double('pengiriman_weight_capacity');
            $table->double('current_volume');
            $table->double('current_weight');
            $table->double('LCL_price')->nullable();
            $table->double('FCL_price')->nullable();
            $table->double('udara_price')->nullable();
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
