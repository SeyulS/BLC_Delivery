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
        Schema::create('demand', function (Blueprint $table) {
            $table->id();
            $table->string('room_id');
            $table->string('player_username')->nullable();
            $table->string('demand_id');
            $table->string('tujuan_pengiriman')->nullable();
            $table->string('day');
            $table->integer('need_day')->nullable();
            $table->integer('item_index');
            $table->integer('quantity');
            $table->double('revenue');
            $table->double('cost');
            $table->double('profit');
            $table->boolean('is_delivered')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demand');
    }
};
