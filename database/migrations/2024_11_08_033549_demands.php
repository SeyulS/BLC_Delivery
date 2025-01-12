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
            $table->string('demand_id')->unique();
            $table->string('tujuan_pengiriman');
            $table->integer('need_day')->nullable();
            $table->integer('item_index');
            $table->integer('quantity');
            $table->double('revenue');
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
