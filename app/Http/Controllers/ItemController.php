<?php


namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\Raw_Item;  // Ubah ke Raw_Item sesuai dengan penamaan Anda
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Menampilkan halaman dengan data raw items
     */
    public function index()
    {
        return view('admin.crud.crud_items',[
            'rawItems' => Raw_Item::all()
        ]);
    }

    
}

