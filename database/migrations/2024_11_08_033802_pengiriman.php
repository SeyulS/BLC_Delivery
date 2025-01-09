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
            $table->string('kapasitas_pengiriman');
            $table->double('biaya_pengiriman')->nullable();
            $table->double('biaya_FCL')->nullable();
            $table->string('lama_pengiriman');
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
