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
        Schema::create('lcl_history', function (Blueprint $table) {
            $table->id();
            $table->string('room_id');
            $table->string('player_username');
            $table->integer('day');
            $table->string('destination');
            $table->string('demand_id');
            $table->double('delivery_cost');
            $table->double('revenue');
            $table->double('late_early_charge');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lcl_history');
    }
};
