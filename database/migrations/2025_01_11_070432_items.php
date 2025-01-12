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
            $table->string('item_id')->unique();
            $table->string('item_name')->nullable();
            $table->double('item_price')->nullable();
            $table->string('raw_item_needed')->nullable();
            $table->integer('raw_quantity_needed')->nullable();
            $table->integer('item_size')->nullable();

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
