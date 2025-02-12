<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Http\Requests\StoreMachineRequest;
use App\Http\Requests\UpdateMachineRequest;
use App\Models\Items;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    public function index(){
        $listOfUsedItems = [];
        $machines = Machine::all();

        foreach ($machines as $machine){
            $listOfUsedItems[] = $machine->machine_item_index;
        }
        
        return view('Admin.crud.crud_machine',[
            'items' => Items::whereNotIn('id', $listOfUsedItems)->get(),
            'machines' => $machines
        ]);
    }

    public function create(Request $request){

        $machine = new Machine();
        $machine->machine_name = $request->input('machine_name');
        $machine->machine_size = $request->input('machine_size');
        $machine->production_capacity = $request->input('production_capacity');
        $machine->machine_price = $request->input('machine_price');
        $machine->machine_item_index = $request->input('item_to_produce');
        $machine->save();

        return response([
            'status' => 'success',
            'message' => 'Machine Berhasil Ditambahkan',
            'machine_name' => $request->input('machine_name'),
            'machine_size' => $request->input('machine_size'),
            'production_capacity' => $request->input('production_capacity'),
            'machine_price' => $request->input('machine_price'),
            'item_name' => Items::where('id', $request->input('item_to_produce'))->first()->item_name
        ]); 
    }

    
}
