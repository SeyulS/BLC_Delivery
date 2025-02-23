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
        Schema::create('purchase_raw_item_history', function (Blueprint $table) {
            $table->id();
            $table->string('room_id');
            $table->integer('day');
            $table->string('player_username');
            $table->json('raw_items');
            $table->integer('total_price');
            $table->double('revenue_before');
            $table->double('revenue_after');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_raw_item_history');
    }
};
