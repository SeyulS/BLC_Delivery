<?php

namespace App\Http\Controllers;

use App\Events\UpdateRevenue;
use App\Models\AirplaneDelivery;
use Illuminate\Http\Request;

use App\Models\Player;
use App\Models\Demand;
use App\Models\FCLDelivery;
use App\Models\Items;
use App\Models\LCLDelivery;
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
            'mks' => $mks
        ]);
    }

    public function indexFCL($room_id)
    {
        $room = Room::where('room_id', $room_id)->first();

        return view('Admin.fitur.setting_pengiriman_fcl', [
            'room' => $room,
            'players' => Player::where('room_id', $room_id)->get(),
            'destination' => ['Manado', 'Banjarmasin', 'Makassar'],
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
            'mks' => $mks
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
                'message' => 'Item Player tidak mencukupi !'
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
            $earlyPrice = $earlyDayTotal * $room->early_delivery_charge;
        }

        // Cek Keterlambatan
        if ($needDay > $demand->need_day){
            $lateDayTotal = $needDay - $demand->need_day;
            $latePrice = $lateDayTotal * $room->late_delivery_charge;
        }

        // Cek Saldo
        if ($player->revenue < $deliveryPrice + $latePrice || $player->revenue < $deliveryPrice + $earlyPrice){
            return response()->json([
                'status' => 'fail', 
                'message' => 'Saldo Player tidak mencukupi !'
            ]);
        }

        $playerItems[$index] = $playerItems[$index] - $demand->quantity;
        $player->items = json_encode($playerItems);
        $player->revenue = $player->revenue + $demand->revenue - $deliveryPrice - $latePrice;
        $player->save();

        $lcl = LCLDelivery::where('room_id', $request->input('room_id'))
            ->where('destination', $demand->tujuan_pengiriman)->first();
        
        $lcl->current_volume_capacity = $lcl->current_volume_capacity + $volume;
        $lcl->current_weight_capacity = $lcl->current_weight_capacity + $weight;

        $lcl->save();
        
        $demand->delete();

        UpdateRevenue::dispatch();

        return response()->json([
            'status' => 'success', 
            'message' => 'Pengiriman Berhasil'
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
                    'message' => 'Item Player Tidak Mencukupi'
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
        
        $price = $fcl->price;
        
        $max_volume_capacity = $fcl->max_volume_capacity;
        $max_weight_capacity = $fcl->max_weight_capacity;

        if($currentVolume > $max_volume_capacity || $currentWeight > $max_weight_capacity){
            return response()->json([
                'status' => 'fail', 
                'message' => 'Over Kapasitas !'
            ]);
        }

        $lateDeliveryCost = 0;
        $earlyDeliveryCost = 0;
        foreach($demand as $d){
            $needDay = $room->recent_day + $fcl->pengiriman_duration;
            if( $needDay < $d->need_day){
                $earlyDeliveryCost = $earlyDeliveryCost + (($d->need_day - $needDay)*$room->early_delivery_charge);
            }
            if($needDay > $d->need_day){
                $lateDeliveryCost = $lateDeliveryCost + (($needDay - $d->need_day)*$room->late_delivery_charge);
            }
        }

        if($player->revenue < ($price + $lateDeliveryCost + $earlyDeliveryCost)){
            return response()->json([
                'status' => 'fail', 
                'message' => 'Saldo tidak mencukupi !'
            ]);
        }

        for($i = 0; $i < count($itemIndex); $i++){
            $playerItems[$i] = $playerItems[$i] - $itemNeeded[$i];
        }
        $player->revenue = $player->revenue - ($price+$lateDeliveryCost);
        $player->items = json_encode($playerItems);
        $player->save();

        // Nanti kalau mau update history disini
        foreach($demand as $d){
            $d->delete();
        }

        UpdateRevenue::dispatch();
        
        return response()->json([
            'status' => 'success', 
            'message' => 'Pengiriman Berhasil !'
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
                'message' => 'Item Player tidak mencukupi !'
            ]);
        }

        // Cek Saldo ( melibatkan apakah ada keterlambatan pengiriman )
        return response($demand->item_index);

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
        $lcl = AirplaneDelivery::where('room_id', $request->input('room_id'))
            ->where('destination', $demand->tujuan_pengiriman)->first();
        $price = $lcl->price;
        
        $deliveryPrice = $used * $price;

        $needDay = $room->recent_day + $lcl->pengiriman_duration;

        $latePrice = 0; // Default e 0
        $earlyPrice = 0;

        // Jika terlalu cepat
        if ($needDay < $demand->need_day){
            $earlyDayTotal = $demand->need_day - $needDay;
            $earlyPrice = $earlyDayTotal * $room->early_delivery_charge;
        }

        // Cek Keterlambatan
        if ($needDay > $demand->need_day){
            $lateDayTotal = $needDay - $demand->need_day;
            $latePrice = $lateDayTotal * $room->late_delivery_charge;
        }

        // Cek Saldo
        if ($player->revenue < $deliveryPrice + $latePrice || $player->revenue < $deliveryPrice + $earlyPrice){
            return response()->json([
                'status' => 'fail', 
                'message' => 'Saldo Player tidak mencukupi !'
            ]);
        }

        $playerItems[$index] = $playerItems[$index] - $demand->quantity;
        $player->items = json_encode($playerItems);
        $player->revenue = $player->revenue + $demand->revenue - $deliveryPrice - $latePrice;
        $player->save();

        $lcl = LCLDelivery::where('room_id', $request->input('room_id'))
            ->where('destination', $demand->tujuan_pengiriman)->first();
        
        $lcl->current_volume_capacity = $lcl->current_volume_capacity + $volume;
        $lcl->current_weight_capacity = $lcl->current_weight_capacity + $weight;

        $lcl->save();
        $demand->delete();

        UpdateRevenue::dispatch();

        return response()->json([
            'status' => 'success', 
            'message' => 'Pengiriman Berhasil'
        ]);

    }


}
