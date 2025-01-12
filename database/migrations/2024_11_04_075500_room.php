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
        Schema::create('room', function (Blueprint $table) {
            $table->string('room_id')->unique();
            $table->text('room_name');
            $table->integer('recent_day')->nullable();
            $table->json('special_day')->nullable();
            $table->integer('max_day')->nullable();
            $table->json('item_chosen')->nullable();
            $table->json('machine_chosen')->nullable();
            $table->integer('warehouse_size')->nullable();
            $table->integer('warehouse_price')->nullable();
            $table->foreignId('deck_id');
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
