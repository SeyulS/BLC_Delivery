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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name')->nullable();
            $table->double('item_price')->nullable();
            $table->json('raw_item_needed')->nullable();
            $table->json('raw_quantity_needed')->nullable();
            $table->integer('item_length')->nullable();
            $table->integer('item_width')->nullable();
            $table->integer('item_height')->nullable();
            $table->double('item_weight')->nullable();
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
