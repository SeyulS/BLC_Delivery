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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('player_username')->unique();
            $table->string('password');
            $table->foreignId('room_id')->nullable();
            $table->string('pinjaman_id')->nullable();
            $table->integer('inventory')->nullable();
            $table->json('raw_items')->nullable();
            $table->json('items')->nullable();
            $table->json('machine_capacity')->nullable();
            $table->double('revenue')->nullable();
            $table->integer('jatuh_tempo')->nullable();
            $table->double('debt')->nullable();
            $table->integer('produce');
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
