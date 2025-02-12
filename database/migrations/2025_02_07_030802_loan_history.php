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
        Schema::create('loan_history', function (Blueprint $table) {
            $table->id();
            $table->string('room_id');
            $table->integer('day');
            $table->string('player_username');
            $table->double('loan_value');
            $table->double('loan_interest');
            $table->integer('loan_due');
            $table->double('before_loan');
            $table->double('after_loan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_history');
    }
};
