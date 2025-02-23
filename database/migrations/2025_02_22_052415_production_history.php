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
        Schema::create('production_history', function (Blueprint $table) {
            $table->id();
            $table->string('room_id');
            $table->integer('day');
            $table->string('player_username');
            $table->json('raw_item_spended');
            $table->json('production_items');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_history');
    }
};
