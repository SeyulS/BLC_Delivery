<?php

namespace App\Http\Controllers;

use App\Models\AirplaneDelivery;
use App\Models\DeckDemand;
use App\Models\Decks;
use App\Models\Demand;
use App\Models\FCLDelivery;
use App\Models\Items;
use App\Models\LCLDelivery;
use App\Models\Loan;
use App\Models\Machine;
use App\Models\Player;
use App\Models\RawItem;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;
use Illuminate\Http\Request;

class CreateRoomController extends Controller
{
    public function createRoom(Request $request)
    {
        // dd($request->all());
        // dd($request->input('mks.kota'));
        // dd($request->input("mnd[LCL_price]"));
        $validatedData = $request->validate([
            'roomCode' => 'required|max:3',
            'roomDescription' => 'required|string|max:255',
            'numDays' => 'required|integer|min:1',
            'specialDays' => 'array',
            'specialDays.*' => 'integer|min:1',
            'item1' => 'nullable|exists:items,id',
            'item2' => 'nullable|exists:items,id',
            'item3' => 'nullable|exists:items,id',
            'warehouseSize' => 'required|integer',
            'warehousePrice' => 'required|numeric',
            'inventoryCost' => 'required|numeric',
            'lateDeliveryCost' => 'required|numeric',
            'earlyDeliveryCost' => 'required|numeric',
        ]);


        $room = new Room();
        $room->room_id = $validatedData['roomCode'];
        $room->room_name = $validatedData['roomDescription'];
        $room->max_day = $validatedData['numDays'];
        $room->special_day = json_encode($validatedData['specialDays'] ?? []);
        $room->item_chosen = json_encode([
            $validatedData['item1'],
            $validatedData['item2'],
            $validatedData['item3'],
        ]);
        $room->warehouse_size = $validatedData['warehouseSize'];
        $room->warehouse_price = $validatedData['warehousePrice'];
        $room->late_delivery_charge = $validatedData['lateDeliveryCost'];
        $room->early_delivery_charge = $validatedData['earlyDeliveryCost'];
        $room->inventory_cost = $validatedData['inventoryCost'];
        $room->status = 0;

        $machine1 = Machine::where('machine_item_index', $validatedData['item1'])->first();
        $machine2 = Machine::where('machine_item_index', $validatedData['item2'])->first();
        $machine3 = Machine::where('machine_item_index', $validatedData['item3'])->first();

        $machineIndex = [
            $machine1->id,
            $machine2->id,
            $machine3->id
        ];
        $room->machine_chosen = json_encode($machineIndex);
        $room->start = 0;
        $room->finished = 0;
        $room->first_capital = $request->input('playerCapital');
        $room->save();

        // Manado
        $lcl = new LCLDelivery();
        $lcl->room_id = $validatedData['roomCode'];
        $lcl->destination = 'Manado';
        $lcl->max_volume_capacity = $request->input('mnd.LCL_volume_capacity');
        $lcl->max_weight_capacity = $request->input('mnd.LCL_weight_capacity');
        $lcl->current_volume_capacity = 0;
        $lcl->current_weight_capacity = 0;
        $lcl->price = $request->input('mnd.LCL_price');
        $lcl->pengiriman_duration = $request->input('mnd.LCL_duration');
        $lcl->save();

        $fcld = new FCLDelivery();
        $fcld->room_id = $validatedData['roomCode'];
        $fcld->destination = 'Manado';
        $fcld->max_volume_capacity = $request->input('mnd.FCL_volume_capacity');
        $fcld->max_weight_capacity = $request->input('mnd.FCL_weight_capacity');
        $fcld->price = $request->input('mnd.FCL_price');
        $fcld->pengiriman_duration = $request->input('mnd.FCL_duration');
        $fcld->save();

        $udara = new AirplaneDelivery();
        $udara->room_id = $validatedData['roomCode'];
        $udara->destination = 'Manado';
        $udara->max_volume_capacity = $request->input('mnd.udara_volume_capacity');
        $udara->max_weight_capacity = $request->input('mnd.udara_weight_capacity');
        $udara->current_volume_capacity = 0;
        $udara->current_weight_capacity = 0;
        $udara->price = $request->input('mnd.udara_price');
        $udara->pengiriman_duration = $request->input('mnd.udara_duration');
        $udara->save();


        // Banjar
        $lcl = new LCLDelivery();
        $lcl->room_id = $validatedData['roomCode'];
        $lcl->destination = 'Banjarmasin';
        $lcl->max_volume_capacity = $request->input('banjar.LCL_volume_capacity');
        $lcl->max_weight_capacity = $request->input('banjar.LCL_weight_capacity');
        $lcl->current_volume_capacity = 0;
        $lcl->current_weight_capacity = 0;
        $lcl->price = $request->input('banjar.LCL_price');
        $lcl->pengiriman_duration = $request->input('banjar.LCL_duration');
        $lcl->save();

        $fcld = new FCLDelivery();
        $fcld->room_id = $validatedData['roomCode'];
        $fcld->destination = 'Banjarmasin';
        $fcld->max_volume_capacity = $request->input('banjar.FCL_volume_capacity');
        $fcld->max_weight_capacity = $request->input('banjar.FCL_weight_capacity');
        $fcld->price = $request->input('banjar.FCL_price');
        $fcld->pengiriman_duration = $request->input('banjar.FCL_duration');
        $fcld->save();

        $udara = new AirplaneDelivery();
        $udara->room_id = $validatedData['roomCode'];
        $udara->destination = 'Banjarmasin';
        $udara->max_volume_capacity = $request->input('banjar.udara_volume_capacity');
        $udara->max_weight_capacity = $request->input('banjar.udara_weight_capacity');
        $udara->current_volume_capacity = 0;
        $udara->current_weight_capacity = 0;
        $udara->price = $request->input('banjar.udara_price');
        $udara->pengiriman_duration = $request->input('banjar.udara_duration');
        $udara->save();

        // Makasssar
        $lcl = new LCLDelivery();
        $lcl->room_id = $validatedData['roomCode'];
        $lcl->destination = 'Makassar';
        $lcl->max_volume_capacity = $request->input('mks.LCL_volume_capacity');
        $lcl->max_weight_capacity = $request->input('mks.LCL_weight_capacity');
        $lcl->current_volume_capacity = 0;
        $lcl->current_weight_capacity = 0;
        $lcl->price = $request->input('mks.LCL_price');
        $lcl->pengiriman_duration = $request->input('mks.LCL_duration');
        $lcl->save();

        $fcld = new FCLDelivery();
        $fcld->room_id = $validatedData['roomCode'];
        $fcld->destination = 'Makassar';
        $fcld->max_volume_capacity = $request->input('mks.FCL_volume_capacity');
        $fcld->max_weight_capacity = $request->input('mks.FCL_weight_capacity');
        $fcld->price = $request->input('mks.FCL_price');
        $fcld->pengiriman_duration = $request->input('mks.FCL_duration');
        $fcld->save();

        $udara = new AirplaneDelivery();
        $udara->room_id = $validatedData['roomCode'];
        $udara->destination = 'Makassar';
        $udara->max_volume_capacity = $request->input('mks.udara_volume_capacity');
        $udara->max_weight_capacity = $request->input('mks.udara_weight_capacity');
        $udara->current_volume_capacity = 0;
        $udara->current_weight_capacity = 0;
        $udara->price = $request->input('mks.udara_price');
        $udara->pengiriman_duration = $request->input('mks.udara_duration');
        $udara->save();

        $loan1 = new Loan();
        $loan1->room_id = $validatedData['roomCode'];
        $loan1->loan_value = $request->input('loanValue1');
        $loan1->loan_interest = $request->input('loanInterest1');
        $loan1->loan_due = $request->input('loanDue1');
        $loan1->save();

        $loan2 = new Loan();
        $loan2->room_id = $validatedData['roomCode'];
        $loan2->loan_value = $request->input('loanValue2');
        $loan2->loan_interest = $request->input('loanInterest2');
        $loan2->loan_due = $request->input('loanDue2');
        $loan2->save();

        $loan3 = new Loan();
        $loan3->room_id = $validatedData['roomCode'];
        $loan3->loan_value = $request->input('loanValue3');
        $loan3->loan_interest = $request->input('loanInterest3');
        $loan3->loan_due = $request->input('loanDue3');
        $loan3->save();

        // Generate Demand With Algortihms
        $items = [
            $validatedData['item1'],
            $validatedData['item2'],
            $validatedData['item3'],
        ];

        $specialDays = $validatedData['specialDays'] ?? [];
        $hpp = [];


        foreach ($items as $item) {
            $query = Items::where('id', $item)->first();
            $raw = json_decode($query->raw_item_needed, true);
            $harga = 0;
            foreach ($raw as $r) {
                $harga = $harga + RawItem::where('id', $r)->first()->raw_item_price;
            }
            $hpp[] = $harga;
        }

        $ekspedisi = [
            [
                'tujuan' => 'Manado',
                'lead_time' => $request->input('mnd.LCL_duration'),
                'price' => $request->input('mnd.udara_price'),
            ],
            [
                'tujuan' => 'Banjarmasin',
                'lead_time' => $request->input('banjar.LCL_duration'),
                'price' => $request->input('banjar.udara_price'),
            ],
            [
                'tujuan' => 'Makassar',
                'lead_time' => $request->input('mks.LCL_duration'),
                'price' => $request->input('mks.udara_price'),
            ],
        ];

        $this->generateDemand($validatedData['roomCode'], 1, $validatedData['numDays'], $request->input('cardPerDays'), $ekspedisi, $items, $hpp, $specialDays);

        return redirect()->back()->with('success', 'Room created successfully');
    }

    public function generateDemand($roomCode, $startDay, $numDays, $numDemandsPerDay, $ekspedisi, $items, $hpp, $specialDays)
    {
        for ($day = $startDay; $day < $startDay + $numDays; $day++) {
            $this->calculateDemand($roomCode, $day, $items, $ekspedisi, $hpp, $numDemandsPerDay, $specialDays);
        }
    }

    public function calculateDemand($roomCode, $day, $items, $ekspedisi, $hpp, $num_demands, $specialDays)
    {
        $demand_list = [];
        $cities = ['Manado', 'Banjarmasin', 'Makassar'];
        $city_count = count($cities);

        foreach ($cities as $city) {
            $city_demand_count[$city] = 0;
        }

        $city_due_dates = [];
        $city_quantities = [];

        foreach ($cities as $city) {
            $city_due_dates[$city] = [];
            $city_quantities[$city] = [];
        }

        for ($i = 0; $i < $num_demands; $i++) {
            $city  = $cities[$i % $city_count];
            $city_demand_count[$city] += 1;
            $rand = mt_rand(0,count($items)-1);
            $chosenItem = Items::where('id', $items[$rand])->first();

            if (in_array($day, $specialDays)) {
                $quantity = mt_rand(12, 15);
            } else {
                $quantity = mt_rand(3, 5);
            }

            $city_quantities[$city][] = $quantity;

            $filtered_routes = array_filter($ekspedisi, function ($r) use ($city) {
                return $r['tujuan'] === $city;
            });

            $filtered_routes = array_values($filtered_routes);

            $route = $filtered_routes ? $filtered_routes[array_rand($filtered_routes)] : null;

            if ($route && is_array($route)) {
                $lead_time = $route['lead_time'];
                $shipping_price = $route['price'];
                $randomDueDate = [-1,0,1,2];
                $due_date = $day + $lead_time + $randomDueDate[mt_rand(0,count($randomDueDate)-1)];
                $city_due_dates[$city][] = $due_date;

                $randomPercentage = [1, 0.995, 0.99, 0.985, 0.98, 0.975, 0.97, 0.965, 0.96, 0.955, 0.95];

                $used = 0;
                $volume = $chosenItem->item_width * $chosenItem->item_height * $chosenItem->item_length * $quantity;
                $weight = $chosenItem->item_weight * $quantity;
                if($volume > $weight){
                    $used = $volume;
                } else {
                    $used = $weight;
                }

                $value = ($chosenItem->item_price * $quantity * $randomPercentage[mt_rand(0,count($randomPercentage)-1)]) + ($used * $shipping_price);
                $total_shippin_cost = $used * $quantity;

                $total_hpp = $hpp[$rand] * $quantity + $total_shippin_cost;

                $profit = $value - $total_hpp;

                $demand_list[] = [
                    'demand_id' => $i,
                    'destination' => $city,
                    'item' => $chosenItem->id,
                    'quantity' => $quantity,
                    'due_date' => $due_date,
                    'value' => $value,
                    'total_hpp' => $total_hpp,
                    'profit' => $profit
                ];
            }
        }

        $shortest_due_dates_per_city = [];
        $largest_quantities_per_city = [];

        // foreach ($cities as $city) {
        //     foreach ($cities as $city) {
        //         $shortest_due_dates_per_city[$city] = !empty($city_due_dates[$city]) ? min($city_due_dates[$city]) : null;
        //         $largest_quantities_per_city[$city] = !empty($city_quantities[$city]) ? max($city_quantities[$city]) : null;
        //     }
        // }

        // foreach ($demand_list as $demand) {
        //     $city = $demand['destination'];
        //     if ($demand['due_date'] === $shortest_due_dates_per_city[$city]) {
        //         $demand['value'] = ($demand['value'] * 1.05);
        //     }
        //     if ($demand['quantity'] === $largest_quantities_per_city) {
        //         $demand['value'] = ($demand['value'] * 1.05);
        //     }
        // }

        foreach ($demand_list as $demand) {
            // dd($demand['destination']);
            $newDemand = new Demand();
            $newDemand->room_id = $roomCode;
            $newDemand->demand_id = $day . '_' . $demand['demand_id'];
            $newDemand->player_username = null;
            $newDemand->tujuan_pengiriman = $demand['destination'];
            $newDemand->day = $day;
            $newDemand->need_day = $demand['due_date'];
            $newDemand->item_index = $demand['item'];
            $newDemand->quantity = $demand['quantity'];
            $newDemand->revenue = $demand['value'];
            $newDemand->cost = $demand['total_hpp'];
            $newDemand->profit = $demand['profit'];
            $newDemand->save();
        }
    }

    public function coba(Request $request)
    {
        $items = [
            1,
            2,
            3,
        ];
        $specialDays = [3, 4, 5];
        $numDays = 13;
        $price = [];
        $hpp = [];

        foreach ($items as $item) {
            $price[] = Items::where('id', $item)->first()->item_price;
        }

        foreach ($items as $item) {
            $query = Items::where('id', $item)->first();
            $raw = json_decode($query->raw_item_needed, true);
            $harga = 0;
            foreach ($raw as $r) {
                $harga = $harga + RawItem::where('id', $r)->first()->raw_item_price;
            }
            $hpp[] = $harga;
        }

        // dd($hpp,$price);

        $ekspedisi = [
            [
                'tujuan' => "Manado",
                'price' => 10,
                'lead_time' => 3,
            ],
            [
                'tujuan' => "Banjarmasin",
                'price' => 30,
                'lead_time' => 5,
            ],
            [
                'tujuan' => "Makassar",
                'price' => 20,
                'lead_time' => 4,
            ],
        ];

        $this->generateDemand(111, 1, $numDays, 100, $ekspedisi, $items, $hpp, $price, $specialDays);
    }
}
