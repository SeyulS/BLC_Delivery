<?php


namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\Machine;
use App\Models\RawItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * Menampilkan halaman dengan data raw items
     */
    public function index()
    {
        return view('Admin.crud.crud_items', [
            'administrator' => Auth::guard('administrator')->user(),
            'rawItems' => RawItem::all(),
            'items' => Items::all()
        ]);
    }

    public function create(Request $request)
    {
        // return response($request->input('raw_item_quantity_needed'));

        $foundDuplicate = false;
        foreach ($request->input('raw_item_needed') as $key => $value) {
            $remainingArray = array_slice($request->input('raw_item_needed'), $key + 1);

            if (in_array($value, $remainingArray)) {
                $foundDuplicate = true;
                break;
            }
        }

        if ($foundDuplicate) {
            return response([
                'status' => 'fail',
                'message' => 'There are same raw items'
            ]);
        }

        $item = new Items();
        $item->item_name = $request->input('item_name');
        $item->raw_item_needed = json_encode($request->input('raw_item_needed'));
        $item->raw_quantity_needed = json_encode($request->input('raw_item_quantity_needed'));
        $item->item_length = $request->input('item_length');
        $item->item_width = $request->input('item_width');
        $item->item_height = $request->input('item_height');
        $item->item_weight = $request->input('item_weight');
        $item->item_price = $request->input('item_price');
        $item->save();

        return response([
            'status' => 'success',
            'message' => 'Item Berhasil Ditambahkan'
        ]);
    }

    public function destroy($id)
    {
        $item = Items::find($id);

        $machine =  Machine::where('machine_item_index', $id)->first();
        if ($machine) {
            return response()->json([
                'status' => 'error',
                'message' => 'Item cannot be deleted because it is used by a machine!'
            ]);
        }
        if (!$item) {
            return response()->json([
                'status' => 'error',
                'message' => 'Item not found!'
            ]);
        }

        try {
            $item->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Item deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete item. Please try again!'
            ]);
        }
    }
}
