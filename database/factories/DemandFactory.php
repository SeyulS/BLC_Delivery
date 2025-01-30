<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Demand;

class DemandFactory extends Factory
{
    protected static $counter = 100;

    protected $model = Demand::class;

    public function definition(): array
    {
        $tujuan_pengiriman = ['BPP', 'MDN', 'SUB'];
        return [
            'demand_id' => self::$counter++,
            'tujuan_pengiriman' => $tujuan_pengiriman[array_rand($tujuan_pengiriman)],
            'day' => mt_rand(1,2),
            'need_day' => mt_rand(4, 5),
            'item_index' => $this->faker->numberBetween(0, 2),
            'quantity' => $this->faker->numberBetween(1, 5),
            'revenue' => $this->faker->randomFloat(2, 100, 10000),
        ];
    }
}
