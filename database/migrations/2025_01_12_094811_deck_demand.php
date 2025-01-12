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
        Schema::create('deck_demand', function (Blueprint $table) {
            $table->string('deck_id');
            $table->string('demand_id');
            $table->string('room_id')->nullable();
            $table->string('player_username')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deck_demand');
    }
};
