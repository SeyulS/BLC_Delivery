<?php

namespace App\Http\Controllers;

use App\Events\UpdateRevenue;
use App\Models\AirHistory;
use App\Models\AirplaneDelivery;
use Illuminate\Http\Request;

use App\Models\Player;
use App\Models\Demand;
use App\Models\FCLDelivery;
use App\Models\FCLHistory;
use App\Models\Items;
use App\Models\LCLDelivery;
use App\Models\LCLHistory;
use App\Models\RevenueHistory;
use App\Models\Room;

class SettingPengirimanController extends Controller
{
    public function indexLCL($room_id)
    {
        $room = Room::where('room_id', $room_id)->first();

        $mnd = LCLDelivery::where('room_id', $room_id)
            ->where('destination', 'Manado')->first();
        $bjm = LCLDelivery::where('room_id', $room_id)
            ->where('destination', 'Banjarmasin')->first();
        $mks = LCLDelivery::where('room_id', $room_id)
            ->where('destination', 'Makassar')->first();

        return view('Admin.fitur.setting_pengiriman_lcl', [
            'room' => $room,
            'players' => Player::where('room_id', $room_id)->get(),
            'mnd' => $mnd,
            'bjm' => $bjm,
            'mks' => $mks,
            'history' => LCLHistory::where('room_id', $room->room_id)->get()
        ]);
    }

    public function indexFCL($room_id)
    {
        $room = Room::where('room_id', $room_id)->first();

        $mnd = LCLDelivery::where('room_id', $room_id)
            ->where('destination', 'Manado')->first();
        $bjm = LCLDelivery::where('room_id', $room_id)
            ->where('destination', 'Banjarmasin')->first();
        $mks = LCLDelivery::where('room_id', $room_id)
            ->where('destination', 'Makassar')->first();

        return view('Admin.fitur.setting_pengiriman_fcl', [
            'room' => $room,
            'players' => Player::where('room_id', $room_id)->get(),
            'destination' => ['Manado', 'Banjarmasin', 'Makassar'],
            'history' => FCLHistory::where('room_id', $room->room_id)->get(),
            'mnd' => $mnd,
            'bjm' => $bjm, 
            'mks' => $mks,
        ]);
    }

    public function indexUdara($room_id){
        $room = Room::where('room_id', $room_id)->first();

        $mnd = AirplaneDelivery::where('room_id', $room_id)
            ->where('destination', 'Manado')->first();
        $bjm = AirplaneDelivery::where('room_id', $room_id)
            ->where('destination', 'Banjarmasin')->first();
        $mks = AirplaneDelivery::where('room_id', $room_id)
            ->where('destination', 'Makassar')->first();

        return view('Admin.fitur.setting_pengiriman_udara', [
            'room' => $room,
            'players' => Player::where('room_id', $room_id)->get(),
            'mnd' => $mnd,
            'bjm' => $bjm,
            'mks' => $mks,
            'history' => AirHistory::where('room_id', $room->room_id)->get()
        ]);

    }

    public function setLCL(Request $request)
    {
        // Tarik data dari demand
        $demand = Demand::where('room_id', $request->input('room_id'))
            ->where('demand_id', $request->input('demand_id'))->first();
        
        // Cari index item
        $room = Room::where('room_id', $request->input('room_id'))->first();
        $roomItems = json_decode($room->item_chosen, true);

        $index = 0;
        foreach ($roomItems as $item){
            if($item == $demand->item_index){
                break;
            }
            $index = $index + 1;
        }

        // Cek Apakah Player Punya Barangnya
        $player = Player::where('player_username', $demand->player_username)->first();
        $playerItems = json_decode($player->items, true);
        $quantity = $playerItems[$index];

        if ($quantity < $demand->quantity) {
            return response()->json([
                'status' => 'fail', 
                'message' => 'Player doesnt have enough items !'
            ]);
        }


        // Volume atau Berat yang Hit Duluan
        $item = Items::where('id', $demand->item_index)->first();
        $volume = $item->item_length * $item->item_width * $item->item_height * $demand->quantity;
        $weight = $item->item_weight * $demand->quantity;

        if ($volume > $weight){
            $used = $volume;
        }
        else{
            $used = $weight;
        }
        $lcl = LCLDelivery::where('room_id', $request->input('room_id'))
            ->where('destination', $demand->tujuan_pengiriman)->first();
        $price = $lcl->price;
        
        $deliveryPrice = $used * $price;

        $needDay = $room->recent_day + $lcl->pengiriman_duration;

        $latePrice = 0; // Default e 0
        $earlyPrice = 0;

        // Jika terlalu cepat
        if ($needDay < $demand->need_day){
            $earlyDayTotal = $demand->need_day - $needDay;
            $earlyPrice = $earlyDayTotal * $demand->quantity * $room->early_delivery_charge;
        }

        // Cek Keterlambatan
        if ($needDay > $demand->need_day){
            $lateDayTotal = $needDay - $demand->need_day;
            $latePrice = $lateDayTotal * $demand->quantity * $room->late_delivery_charge;
        }

        // Cek Saldo
        if ($player->revenue < $deliveryPrice + $latePrice || $player->revenue < $deliveryPrice + $earlyPrice){
            return response()->json([
                'status' => 'fail', 
                'message' => 'Player doesnt have enough money !'
            ]);
        }

        if($lcl->current_volume_capacity + $volume > $lcl->max_volume_capacity || $lcl->current_weight_capacity + $weight > $lcl->max_weight_capacity){
            return response()->json([
                'status' => 'fail', 
                'message' => 'Over Capacity !'
            ]);
        }

        $revenueBefore = $player->revenue;
        $late_early_charge = $latePrice + $earlyPrice;

        $playerItems[$index] = $playerItems[$index] - $demand->quantity;
        $player->items = json_encode($playerItems);
        $player->revenue = $player->revenue + $demand->revenue - $deliveryPrice - $late_early_charge;
        $player->save();

        $lcl = LCLDelivery::where('room_id', $request->input('room_id'))
            ->where('destination', $demand->tujuan_pengiriman)->first();
        $lcl->current_volume_capacity = $lcl->current_volume_capacity + $volume;
        $lcl->current_weight_capacity = $lcl->current_weight_capacity + $weight;
        $lcl->save();
        
        // History
        $history = new LCLHistory();
        $history->room_id = $room->room_id;
        $history->player_username = $player->player_username;
        $history->day = $room->recent_day;
        $history->destination = $demand->tujuan_pengiriman;
        $history->demand_id = $demand->demand_id;
        $history->delivery_cost = $deliveryPrice;
        $history->revenue = $demand->revenue;
        $history->late_early_charge = $late_early_charge;
        $history->save();

        $demand->is_delivered = true;
        $demand->save();

        $revenueHistory = new RevenueHistory();
        $revenueHistory->room_id = $room->room_id;
        $revenueHistory->player_username = $player->player_username;
        $revenueHistory->day = $room->recent_day;
        $revenueHistory->transaction_description = 'LCL Delivery';
        $revenueHistory->revenue_before = $revenueBefore;
        $revenueHistory->revenue_after = $player->revenue;
        $revenueHistory->value = $demand->revenue-$deliveryPrice-$late_early_charge;
        $revenueHistory->save();

        UpdateRevenue::dispatch($request->input('player_username'), $request->input('room_id'));

        return response()->json([
            'status' => 'success', 
            'message' => 'Delivery Success'
        ]);

    }

    public function setFCL(Request $request){
        $demand = Demand::whereIn('demand_id', $request->input('demands'))
                ->where('room_id', $request->input('room_id'))
                ->where('player_username', $request->input('player_username'))
                ->get();
        
        $room = Room::where('room_id', $request->input('room_id'))->first();
        $itemIndex = json_decode($room->item_chosen);
        $itemNeeded = [0,0,0];

        foreach($demand as $d){
            for($i = 0; $i < count($itemIndex); $i++){
                if($itemIndex[$i] == $d->item_index){
                    $itemNeeded[$i] = $itemNeeded[$i] + $d->quantity;
                }
            }
        }

        // Cek Apakah Player Punya Barangnya
        $player = Player::where('player_username', $request->input('player_username'))->first();
        $playerItems = json_decode($player->items, true);

        for($i = 0; $i < count($itemIndex); $i++){
            if($playerItems[$i] < $itemNeeded[$i]){
                return response()->json([
                    'status' => 'fail', 
                    'message' => 'Player doesnt have enough items'
                ]);
            }
        }

        $currentWeight = 0;
        $currentVolume = 0;
        for($i = 0; $i < count($itemNeeded); $i++){
            $item = Items::where('id', $itemIndex[$i])->first();
            $currentWeight = $currentWeight + ($item->item_weight * $itemNeeded[$i]);
            $currentVolume = $currentVolume + ($item->item_length * $item->item_width * $item->item_height * $itemNeeded[$i]);
        }

        $fcl = FCLDelivery::where('room_id', $request->input('room_id'))
            ->where('destination', $request->input('destination'))->first();
        
        $deliveryCost = $fcl->price;
        
        $max_volume_capacity = $fcl->max_volume_capacity;
        $max_weight_capacity = $fcl->max_weight_capacity;

        if($currentVolume > $max_volume_capacity || $currentWeight > $max_weight_capacity){
            return response()->json([
                'status' => 'fail', 
                'message' => 'Over Capacity !'
            ]);
        }

        $lateDeliveryCost = 0;
        $earlyDeliveryCost = 0;
        $revenue = 0;
        foreach($demand as $d){
            $needDay = $room->recent_day + $fcl->pengiriman_duration;
            if( $needDay < $d->need_day){
                $earlyDeliveryCost = $earlyDeliveryCost + (($d->need_day - $needDay) * $d->quantity * $room->early_delivery_charge);
            }
            if($needDay > $d->need_day){
                $lateDeliveryCost = $lateDeliveryCost + (($needDay - $d->need_day) * $d->quantity * $room->late_delivery_charge);
            }
            $revenue = $revenue + $d->revenue;
        }

        if($player->revenue < ($deliveryCost + $lateDeliveryCost + $earlyDeliveryCost)){
            return response()->json([
                'status' => 'fail', 
                'message' => 'Player doesnt have enough money !'
            ]);
        }

        foreach($demand as $d){
            $d->is_delivered = true;
            $d->save();
        }

        for($i = 0; $i < count($itemIndex); $i++){
            $playerItems[$i] = $playerItems[$i] - $itemNeeded[$i];
        }
        $revenueBefore = $player->revenue;
        $player->revenue = $player->revenue + $revenue - ($deliveryCost + $lateDeliveryCost + $earlyDeliveryCost);
        $player->items = json_encode($playerItems);
        $player->save();

        // Nanti kalau mau update history disini
        $history = new FCLHistory();
        $history->room_id = $room->room_id;
        $history->player_username = $request->input('player_username');
        $history->day = $room->recent_day;
        $history->destination = $request->input('destination');
        $history->list_of_demands = json_encode($request->input('demands'));
        $history->delivery_cost = $deliveryCost;
        $history->revenue = $revenue;
        $history->late_early_charge = $lateDeliveryCost + $earlyDeliveryCost;
        $history->save();

        $historyRevenue = new RevenueHistory();
        $historyRevenue->room_id = $room->room_id;
        $historyRevenue->player_username = $request->input('player_username');
        $historyRevenue->day = $room->recent_day;
        $historyRevenue->transaction_description = 'FCL Delivery';
        $historyRevenue->revenue_before = $revenueBefore;
        $historyRevenue->revenue_after = $player->revenue;
        $historyRevenue->value = $revenue - ($deliveryCost + $lateDeliveryCost + $earlyDeliveryCost);
        $historyRevenue->save();

        UpdateRevenue::dispatch($request->input('player_username'), $request->input('room_id'));
        
        return response()->json([
            'status' => 'success', 
            'message' => 'Delivery Success !'
        ]);
    }

    public function setUdara(Request $request)
    {
        // Tarik data dari demand
        $demand = Demand::where('room_id', $request->input('room_id'))
            ->where('demand_id', $request->input('demand_id'))->first();
        
        // Cari index item
        $room = Room::where('room_id', $request->input('room_id'))->first();
        $roomItems = json_decode($room->item_chosen, true);

        $index = 0;
        foreach ($roomItems as $item){
            if($item == $demand->item_index){
                break;
            }
            $index = $index + 1;
        }

        // Cek Apakah Player Punya Barangnya
        $player = Player::where('player_username', $demand->player_username)->first();
        $playerItems = json_decode($player->items, true);
        $quantity = $playerItems[$index];

        if ($quantity < $demand->quantity) {
            return response()->json([
                'status' => 'fail', 
                'message' => 'Player doesnt have enough items !'
            ]);
        }


        // Volume atau Berat yang Hit Duluan
        $item = Items::where('id', $demand->item_index)->first();
        $volume = $item->item_length * $item->item_width * $item->item_height * $demand->quantity;
        $weight = $item->item_weight * $demand->quantity;

        if ($volume > $weight){
            $used = $volume;
        }
        else{
            $used = $weight;
        }
        $air = AirplaneDelivery::where('room_id', $request->input('room_id'))
            ->where('destination', $demand->tujuan_pengiriman)->first();
        $price = $air->price;
        
        $deliveryPrice = $used * $price;

        $needDay = $room->recent_day + $air->pengiriman_duration;

        $latePrice = 0; // Default e 0
        $earlyPrice = 0;

        // Jika terlalu cepat
                // Cek Saldo ( melibatkan apakah ada keterlambatan pengiriman )
        if ($needDay < $demand->need_day){
            $earlyDayTotal = $demand->need_day - $needDay;
            $earlyPrice = $earlyDayTotal * $demand->quantity * $room->early_delivery_charge;
        }

        // Cek Keterlambatan
        if ($needDay > $demand->need_day){
            $lateDayTotal = $needDay - $demand->need_day;
            $latePrice = $lateDayTotal * $demand->quantity * $room->late_delivery_charge;
        }

        // Cek Saldo
        if ($player->revenue < $deliveryPrice + $latePrice || $player->revenue < $deliveryPrice + $earlyPrice){
            return response()->json([
                'status' => 'fail', 
                'message' => 'Player doesnt have enought money !'
            ]);
        }

        if($air->current_volume_capacity + $volume > $air->max_volume_capacity || $air->current_weight_capacity + $weight > $air->max_weight_capacity){
            return response()->json([
                'status' => 'fail', 
                'message' => 'Over Capacity !'
            ]);
        }
        $revenueBefore = $player->revenue;
        $late_early_charge = $latePrice + $earlyPrice;

        $playerItems[$index] = $playerItems[$index] - $demand->quantity;
        $player->items = json_encode($playerItems);
        $player->revenue = $player->revenue + $demand->revenue - $deliveryPrice - $latePrice;
        $player->save();

        $air = AirplaneDelivery::where('room_id', $request->input('room_id'))
            ->where('destination', $demand->tujuan_pengiriman)->first();
        
        $air->current_volume_capacity = $air->current_volume_capacity + $volume;
        $air->current_weight_capacity = $air->current_weight_capacity + $weight;

        $air->save();

        // History
        $history = new AirHistory();
        $history->room_id = $room->room_id;
        $history->player_username = $player->player_username;
        $history->day = $room->recent_day;
        $history->destination = $demand->tujuan_pengiriman;
        $history->demand_id = $demand->demand_id;
        $history->delivery_cost = $deliveryPrice;
        $history->revenue = $demand->revenue;
        $history->late_early_charge = $late_early_charge;
        $history->save();

        $revenueHistory = new RevenueHistory();
        $revenueHistory->room_id = $room->room_id;
        $revenueHistory->player_username = $player->player_username;
        $revenueHistory->day = $room->recent_day;
        $revenueHistory->transaction_description = 'Air Delivery';
        $revenueHistory->revenue_before = $revenueBefore;
        $revenueHistory->revenue_after = $player->revenue;
        $revenueHistory->value = $demand->revenue-$deliveryPrice-$late_early_charge;
        $revenueHistory->save();

        $demand->is_delivered = true;
        $demand->save();

        UpdateRevenue::dispatch($request->input('player_username'), $request->input('room_id'));

        return response()->json([
            'status' => 'success', 
            'message' => 'Delivery Success !'
        ]);
    }

}
