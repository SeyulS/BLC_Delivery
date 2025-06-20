<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\RawItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RawItemController extends Controller
{
    /**
     * Menampilkan halaman dengan data raw items
     */
    public function index()
    {
        return view('Admin.crud.crud_raw_items', [
            'administrator' => Auth::guard('administrator')->user(),
        ]);
    }

    /**
     * Mengambil data raw items untuk DataTables
     */
    public function getData(Request $request)
    {
        $columns = ['id', 'raw_item_name', 'raw_item_price'];  // Kolom yang ingin ditampilkan

        // Query untuk mengambil data
        $query = RawItem::query(); // Menggunakan Raw_Item

        // Filter pencarian jika ada
        if ($search = $request->get('search')['value']) {
            $query->where('raw_item_name', 'like', '%' . $search . '%')
                ->orWhere('raw_item_price', 'like', '%' . $search . '%');
        }

        // Penerapan urutan
        if ($order = $request->get('order')[0]) {
            $column = $columns[$order['column']];
            $direction = $order['dir'];
            $query->orderBy($column, $direction);
        }

        // Ambil data dengan pagination
        $data = $query->skip($request->get('start'))->take($request->get('length'))->get();

        $totalRecords = RawItem::count();  // Menggunakan Raw_Item
        $filteredRecords = $query->count();

        $data = $data->map(function ($item) {
            $item->action = '<button class="btn btn-warning editRawItem" data-id="' . $item->id . '">Edit</button>
                         <button class="btn btn-danger deleteRawItem" data-id="' . $item->id . '">Delete</button>';
            return $item;
        });

        return response()->json([
            'draw' => $request->get('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
    }



    public function store(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'raw_item_name' => 'required|string|max:255',
            'raw_item_price' => 'required|numeric',
        ]);

        RawItem::create([
            'raw_item_name' => $request->raw_item_name,
            'raw_item_price' => $request->raw_item_price,
        ]);

        return response()->json(['message' => 'Raw Item created successfully']);
    }

    public function edit($id)
    {
        $rawItem = RawItem::findOrFail($id);  // Menggunakan Raw_Item
        return response()->json($rawItem);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'raw_item_name' => 'required|string|max:255',
            'raw_item_price' => 'required|numeric',
        ]);

        Rawitem::where('id', $id)
            ->update([
                'raw_item_name' => $request->raw_item_name,
                'raw_item_price' => $request->raw_item_price
            ]);

        return response()->json(['message' => 'Raw Item updated successfully']);
    }


    public function destroy($id)
    {
        $rawItem = RawItem::findOrFail($id);
        $rawItemName = $rawItem->raw_item_name;

        // Check if any item contains the raw_item_id in the raw_item_needed JSON column
        $itemExists = Items::whereJsonContains('raw_item_needed', $id)->exists();

        if ($itemExists) {
            return response()->json([
                'status' => 'error',
                'message' => "Cannot delete $rawItemName because it is used in one or more items."
            ]);
        }

        // Proceed with deletion if no item contains the raw_item_id
        $rawItem->delete();

        return response()->json([
            'status' => 'success',
            'message' => "$rawItemName has been deleted successfully."
        ]);
    }
}
