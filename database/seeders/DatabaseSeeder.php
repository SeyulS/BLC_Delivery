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
            'produce' => 0
        ]);
        
        Player::factory(20)->create();

        Pengiriman::create([
            'tujuan_pengiriman' => 'BPP',
            'jalur_pengiriman' => 'Laut',
            'jenis_pengiriman' => 'LCL',
            'kapasitas_pengiriman' => 50,
            'biaya_LCL' => 2000,
            'biaya_FCL' => null,
            'biaya_udara' => null,
            'lama_pengiriman' => 3
        ]);

        Pengiriman::create([
            'tujuan_pengiriman' => 'MKS',
            'jalur_pengiriman' => 'Laut',
            'jenis_pengiriman' => 'LCL',
            'kapasitas_pengiriman' => 50,
            'biaya_LCL' => 3000,
            'biaya_FCL' => null,
            'biaya_udara' => null,
            'lama_pengiriman' => 4
        ]);

        Pengiriman::create([
            'tujuan_pengiriman' => 'MND',
            'jalur_pengiriman' => 'Laut',
            'jenis_pengiriman' => 'LCL',
            'kapasitas_pengiriman' => 50,
            'biaya_LCL' => 4000,
            'biaya_FCL' => null,
            'biaya_udara' => null,
            'lama_pengiriman' => 4
        ]);

        Pengiriman::create([
            'tujuan_pengiriman' => 'BPP',
            'jalur_pengiriman' => 'Laut',
            'jenis_pengiriman' => 'FCL',
            'kapasitas_pengiriman' => 25,
            'biaya_LCL' => null,
            'biaya_FCL' => 10000,
            'biaya_udara' => null,
            'lama_pengiriman' => 3
        ]);

        Pengiriman::create([
            'tujuan_pengiriman' => 'MKS',
            'jalur_pengiriman' => 'Laut',
            'jenis_pengiriman' => 'LCL',
            'kapasitas_pengiriman' => 25,
            'biaya_LCL' => null,
            'biaya_FCL' => 15000,
            'biaya_udara' => null,
            'lama_pengiriman' => 4
        ]);

        Pengiriman::create([
            'tujuan_pengiriman' => 'MND',
            'jalur_pengiriman' => 'Laut',
            'jenis_pengiriman' => 'LCL',
            'kapasitas_pengiriman' => 25,
            'biaya_LCL' => null,
            'biaya_FCL' => 20000,
            'biaya_udara' => null,
            'lama_pengiriman' => 4
        ]);

        Pengiriman::create([
            'tujuan_pengiriman' => 'BPP',
            'jalur_pengiriman' => 'Udara',
            'jenis_pengiriman' => null,
            'kapasitas_pengiriman' => 50,
            'biaya_LCL' => null,
            'biaya_FCL' => null,
            'biaya_udara' => 4000,
            'lama_pengiriman' => 2
        ]);

        Pengiriman::create([
            'tujuan_pengiriman' => 'MKS',
            'jalur_pengiriman' => 'Udara',
            'jenis_pengiriman' => null,
            'kapasitas_pengiriman' => 50,
            'biaya_LCL' => null,
            'biaya_FCL' => null,
            'biaya_udara' => 6000,
            'lama_pengiriman' => 3
        ]);

        Pengiriman::create([
            'tujuan_pengiriman' => 'MND',
            'jalur_pengiriman' => 'Udara',
            'jenis_pengiriman' => null,
            'kapasitas_pengiriman' => 50,
            'biaya_LCL' => null,
            'biaya_FCL' => null,
            'biaya_udara' => 8000,
            'lama_pengiriman' => 3
        ]);

        Pinjaman::create([
            'pinjaman_id' => 'type_1',
            'pinjaman_value' => 50000,
            'pinjaman_length' => 5,
            'bunga_pinjaman' => 0.1,
        ]);

        Pinjaman::create([
            'pinjaman_id' => 'type_2',
            'pinjaman_value' => 70000,
            'pinjaman_length' => 8,
            'bunga_pinjaman' => 0.2,
        ]);

        Pinjaman::create([
            'pinjaman_id' => 'type_3',
            'pinjaman_value' => 60000,
            'pinjaman_length' => 7,
            'bunga_pinjaman' => 0.3,
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
            'item_size' => 2
        ]);

        Items::create([
            'item_name' => "Kursi",
            'item_price' => 200,
            'raw_item_needed' => json_encode(["1","2","4"]),
            'raw_quantity_needed' => json_encode(["4","4","2"]),
            'item_size' => 2
        ]);

        Items::create([
            'item_name' => "Lemari",
            'item_price' => 200,
            'raw_item_needed' => json_encode(["1","2","5"]),
            'raw_quantity_needed' => json_encode(["4","4","2"]),
            'item_size' => 2
        ]);

        Decks::create([
            'deck_id' => '1',
            'deck_name' => 'deck_lomba'
        ]);

        DeckDemand::create([
            'deck_id' => '1',
            'demand_id' => '101'
        ]);

        DeckDemand::create([
            'deck_id' => '1',
            'demand_id' => '102'
        ]);

        DeckDemand::create([
            'deck_id' => '1',
            'demand_id' => '103'
        ]);

        DeckDemand::create([
            'deck_id' => 1,
            'demand_id' => 104
        ]);

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

        Demand::factory(20)->create();


    }
}
