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
            'room_id' => 123
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
            'raw_item_id' => 'KAYU',
            'raw_item_name' => "Kayu",
            'raw_item_price' => 70,
        ]);

        Raw_item::create([
            'raw_item_id' => 'BESI',
            'raw_item_name' => "Besi",
            'raw_item_price' => 50,
        ]);

        Raw_item::create([
            'raw_item_id' => 'BETON',
            'raw_item_name' => "Beton",
            'raw_item_price' => 40,
        ]);

        Raw_item::create([
            'raw_item_id' => 'ALUMINIUM',
            'raw_item_name' => "Aluminium",
            'raw_item_price' => 60,
        ]);

        Items::create([
            'item_id' => 'CERMIN',
            'item_name' => "Cermin",
            'item_price' => 200,
            'raw_item_needed' => "ALUMINIUM",
            'raw_quantity_needed' => 3,
            'item_size' => 2
        ]);

        Items::create([
            'item_id' => 'LEMARI',
            'item_name' => "Lemari",
            'item_price' => 300,
            'raw_item_needed' => "KAYU",
            'raw_quantity_needed' => 2,
            'item_size' => 4
        ]);

        Items::create([
            'item_id' => 'PAGAR',
            'item_name' => "Pagar",
            'item_price' => 500,
            'raw_item_needed' => "BESI",
            'raw_quantity_needed' => 2,
            'item_size' => 4
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

        Demand::factory(20)->create();


    }
}
