<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // $table->id();
            // $table->string('player_username')->unique();
            // $table->string('password');
            // $table->foreignId('room_id')->nullable()->change();
            // $table->foreignId('pinjaman_id')->nullable()->change();
            // $table->integer('inventory')->nullable();
            // $table->json('raw_items')->nullable();
            // $table->json('items')->nullable();
            // $table->double('revenue')->nullable();
            // $table->timestamps();
            'player_username' => $this->faker->userName(5),
            'password' => bcrypt('12345'),
            'room_id' => 123,
            'jatuh_tempo' => 0,
            'debt' => 0,
            'revenue' => 0,
            'produce' => 0

        ];
    }
}
