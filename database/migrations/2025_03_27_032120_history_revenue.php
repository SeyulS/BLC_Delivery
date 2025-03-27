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
        Schema::create('history_revenue', function (Blueprint $table) {
            $table->id();
            $table->string('room_id');
            $table->integer('day');
            $table->string('player_username');
            $table->string('transaction_description');
            $table->double('revenue_before');
            $table->double('revenue_after');
            $table->double('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_revenue');
    }
};
