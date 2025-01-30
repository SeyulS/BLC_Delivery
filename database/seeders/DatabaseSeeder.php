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

        Pengiriman::create([
            'tujuan_pengiriman' => 'BPP',
            'jalur_pengiriman' => 'Laut',
            'jenis_pengiriman' => 'LCL',
            'pengiriman_volume_capacity' => 50,
            'pengiriman_weight_capacity' => 50,
            'current_volume' => 0,
            'current_weight' => 0,
            'LCL_price' => 2000,
            'FCL_price' => null,
            'udara_price' => null,
            'pengiriman_duration' => 3
        ]);

        Pengiriman::create([
            'tujuan_pengiriman' => 'MKS',
            'jalur_pengiriman' => 'Laut',
            'jenis_pengiriman' => 'LCL',
            'pengiriman_volume_capacity' => 50,
            'pengiriman_weight_capacity' => 50,
            'current_volume' => 0,
            'current_weight' => 0,
            'LCL_price' => 3000,
            'FCL_price' => null,
            'udara_price' => null,
            'pengiriman_duration' => 4
        ]);

        Pengiriman::create([
            'tujuan_pengiriman' => 'MND',
            'jalur_pengiriman' => 'Laut',
            'jenis_pengiriman' => 'LCL',
            'pengiriman_volume_capacity' => 50,
            'pengiriman_weight_capacity' => 50,
            'current_volume' => 0,
            'current_weight' => 0,
            'LCL_price' => 4000,
            'FCL_price' => null,
            'udara_price' => null,
            'pengiriman_duration' => 4
        ]);

        Pengiriman::create([
            'tujuan_pengiriman' => 'BPP',
            'jalur_pengiriman' => 'Laut',
            'jenis_pengiriman' => 'FCL',
            'pengiriman_volume_capacity' => 50,
            'pengiriman_weight_capacity' => 50,
            'current_volume' => 0,
            'current_weight' => 0,
            'LCL_price' => null,
            'FCL_price' => 10000,
            'udara_price' => null,
            'pengiriman_duration' => 3
        ]);

        Pengiriman::create([
            'tujuan_pengiriman' => 'MKS',
            'jalur_pengiriman' => 'Laut',
            'jenis_pengiriman' => 'LCL',
            'pengiriman_volume_capacity' => 50,
            'pengiriman_weight_capacity' => 50,
            'current_volume' => 0,
            'current_weight' => 0,
            'LCL_price' => null,
            'FCL_price' => 15000,
            'udara_price' => null,
            'pengiriman_duration' => 4
        ]);

        Pengiriman::create([
            'tujuan_pengiriman' => 'MND',
            'jalur_pengiriman' => 'Laut',
            'jenis_pengiriman' => 'LCL',
            'pengiriman_volume_capacity' => 50,
            'pengiriman_weight_capacity' => 50,
            'current_volume' => 0,
            'current_weight' => 0,
            'LCL_price' => null,
            'FCL_price' => 20000,
            'udara_price' => null,
            'pengiriman_duration' => 4
        ]);

        Pengiriman::create([
            'tujuan_pengiriman' => 'BPP',
            'jalur_pengiriman' => 'Udara',
            'jenis_pengiriman' => null,
            'pengiriman_volume_capacity' => 50,
            'pengiriman_weight_capacity' => 50,
            'current_volume' => 0,
            'current_weight' => 0,
            'LCL_price' => null,
            'FCL_price' => null,
            'udara_price' => 4000,
            'pengiriman_duration' => 2
        ]);

        Pengiriman::create([
            'tujuan_pengiriman' => 'MKS',
            'jalur_pengiriman' => 'Udara',
            'jenis_pengiriman' => null,
            'pengiriman_volume_capacity' => 50,
            'pengiriman_weight_capacity' => 50,
            'current_volume' => 0,
            'current_weight' => 0,
            'LCL_price' => null,
            'FCL_price' => null,
            'udara_price' => 6000,
            'pengiriman_duration' => 3
        ]);

        Pengiriman::create([
            'tujuan_pengiriman' => 'MND',
            'jalur_pengiriman' => 'Udara',
            'jenis_pengiriman' => null,
            'pengiriman_volume_capacity' => 50,
            'pengiriman_weight_capacity' => 50,
            'current_volume' => 0,
            'current_weight' => 0,
            'LCL_price' => null,
            'FCL_price' => null,
            'udara_price' => 8000,
            'pengiriman_duration' => 3
        ]);

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
            'raw_item_price' => 60,
        ]);

        
        Raw_item::create([
            'raw_item_name' => "Engsel",
            'raw_item_price' => 60,
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
            'item_price' => 200,
            'raw_item_needed' => json_encode(["1","2","4"]),
            'raw_quantity_needed' => json_encode(["4","4","2"]),
            'item_length' => 2,
            'item_width' => 2,
            'item_height' => 2,
            'item_weight' => 2.5
        ]);

        Items::create([
            'item_name' => "Lemari",
            'item_price' => 200,
            'raw_item_needed' => json_encode(["1","2","5"]),
            'raw_quantity_needed' => json_encode(["4","4","2"]),
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

        Demand::factory(40)->create();

    }
}
