<?php

namespace Database\Seeders;
use App\Models\Player;
use App\Models\Room;
use App\Models\Pengiriman;
use App\Models\Demand;
use App\Models\Pinjaman;
use App\Models\Administrator;
use App\Models\DeckDemand;
use App\Models\Decks;
use App\Models\Items;
use App\Models\Machine;
use App\Models\Raw_item;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Administrator::create([
            'admin_username' => 'samuel',
            'password' => bcrypt('12345')
        ]);
        
        Player::create([
            'player_username' => 'samuelado',
            'password' => bcrypt('12345'),
            'room_id' => 123,
        ]);
        
        Player::factory(20)->create();

        Raw_item::create([
            'raw_item_name' => "Kayu",
            'raw_item_price' => 70,
        ]);

        Raw_item::create([
            'raw_item_name' => "Paku",
            'raw_item_price' => 50,
        ]);

        Raw_item::create([
            'raw_item_name' => "Lem",
            'raw_item_price' => 40,
        ]);

        Raw_item::create([
            'raw_item_name' => "Bantal",
            'raw_item_price' => 70,
        ]);

        
        Raw_item::create([
            'raw_item_name' => "Engsel",
            'raw_item_price' => 80,
        ]);

        Items::create([
            'item_name' => "Meja",
            'item_price' => 200,
            'raw_item_needed' => json_encode(["1","2","3"]),
            'raw_quantity_needed' => json_encode(["4","4","2"]),
            'item_length' => 2,
            'item_width' => 2,
            'item_height' => 2,
            'item_weight' => 2.5
        ]);

        Items::create([
            'item_name' => "Kursi",
            'item_price' => 300,
            'raw_item_needed' => json_encode(["1","2","4"]),
            'raw_quantity_needed' => json_encode(["4","4","2"]),
            'item_length' => 2,
            'item_width' => 2,
            'item_height' => 2,
            'item_weight' => 2.5
        ]);

        Items::create([
            'item_name' => "Lemari",
            'item_price' => 400,
            'raw_item_needed' => json_encode(["1","2","5"]),
            'raw_quantity_needed' => json_encode(["4","4","3"]),
            'item_length' => 2,
            'item_width' => 2,
            'item_height' => 2,
            'item_weight' => 2.5
        ]);

        Decks::create([
            'deck_name' => 'deck_lomba',
            'deck_list' => json_encode(["100","101","102","103","104","105","106","107"])
        ]);

        // DeckDemand::create([
        //     'deck_id' => '1',
        //     'demand_id' => '101'
        // ]);

        // DeckDemand::create([
        //     'deck_id' => '1',
        //     'demand_id' => '102'
        // ]);

        // DeckDemand::create([
        //     'deck_id' => '1',
        //     'demand_id' => '103'
        // ]);

        // DeckDemand::create([
        //     'deck_id' => 1,
        //     'demand_id' => 104
        // ]);

        Machine::create([
            'machine_name' => "Mesin Pembuat Meja",
            'production_capacity' => 10,
            'machine_size' => 5,
            'machine_item_index' => 1,
            'machine_price' => 100
        ]);

        Machine::create([
            'machine_name' => "Mesin Pembuat Kursi",
            'production_capacity' => 10,
            'machine_size' => 5,
            'machine_item_index' => 2,
            'machine_price' => 100

        ]);

        Machine::create([
            'machine_name' => "Mesin Pembuat Lemari",
            'production_capacity' => 10,
            'machine_size' => 5,
            'machine_item_index' => 3,
            'machine_price' => 100

        ]);

        // Demand::factory(40)->create();

    }
}
