<?php

use App\Http\Controllers\AdminHomeController;
use App\Http\Controllers\CreateRoomController;
use App\Http\Controllers\DeckController;
use App\Http\Controllers\DemandController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LobbyRoomController;
use App\Http\Controllers\LoginAdminController;
use App\Http\Controllers\LoginPlayerController;
use App\Http\Controllers\ManageDataController;
use App\Http\Controllers\PlayerHomeController;
use App\Http\Controllers\PlayerPurchaseController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\RawItemController;
use App\Http\Controllers\RegistAdminController;
use App\Http\Controllers\RegistPlayerController;
use App\Http\Controllers\RemovePlayerController;
use App\Http\Controllers\RoomControllerAdmin;
use App\Http\Controllers\RoomControllerPlayer;
use App\Http\Controllers\SettingBahanBaku;
use App\Http\Controllers\SettingPengirimanController;
use App\Http\Controllers\SettingPinjamanController;
use App\Http\Controllers\UtilityRoomController;
use Illuminate\Support\Facades\Route;
use App\Models\Player;

Route::get('/', function(){
    return view('Player.login');
});

// Login, Logout, Regist Player
Route::get('/loginPlayer', [LoginPlayerController::class,'index']);
Route::post('/loginPlayer', [LoginPlayerController::class,'authenticate']);
Route::get('/registPlayer', [RegistPlayerController::class,'index']);
Route::post('/registPlayer', [RegistPlayerController::class,'store']);
Route::get('/homePlayer',[PlayerHomeController::class,'index'])->middleware('auth.player');
Route::get('/logoutPlayer',[LoginPlayerController::class,'logout']);

// Login, Logout, Regist Admin
Route::get('/loginAdmin', [LoginAdminController::class,'index']);
Route::post('/loginAdmin', [LoginAdminController::class,'authenticate']);
Route::get('/registAdmin', [RegistAdminController::class,'index']);
Route::post('/registAdmin', [RegistAdminController::class,'store']);
Route::post('/logoutAdmin',[LoginPlayerController::class,'logout']);

// Home Admin
Route::get('/homeAdmin',[AdminHomeController::class,'index'])->middleware('auth.administrator');
Route::get('/manageDeck',[DeckController::class,'index'])->middleware('auth.administrator');
Route::get('/manageDeck/{deck_id}',[DeckController::class,'manage'])->middleware('auth.administrator');
Route::get('/manageData',[ManageDataController::class, 'index']);

// CRUD Raw Item
Route::get('raw-items', [RawItemController::class, 'index']); 
Route::get('raw-items/data', [RawItemController::class, 'getData']); 
Route::post('raw-items', [RawItemController::class, 'store']); 
Route::get('raw-items/{id}/edit', [RawItemController::class, 'edit']); 
Route::put('raw-items/{id}', [RawItemController::class, 'update']); 
Route::get('raw-items/delete/{id}', [RawItemController::class, 'destroy']); 

// CRUD Items
Route::get('items', [ItemController::class, 'index']); 
Route::get('items/data', [RawItemController::class, 'getData']); 
Route::post('items', [RawItemController::class, 'store']); 
Route::get('items/{id}/edit', [RawItemController::class, 'edit']); 
Route::put('items/{id}', [RawItemController::class, 'update']); 
Route::get('items/delete/{id}', [RawItemController::class, 'destroy']); 



Route::get('/api/players/{room_id}', [RoomControllerAdmin::class, 'getPlayers']);

// Post Method
Route::post('/kick-player', [RoomControllerAdmin::class, 'kickPlayer']);
Route::post('/joinRoom', [RoomControllerPlayer::class,'join'])->middleware('auth.player');
Route::post('/createRoom', [CreateRoomController::class,'createRoom'])->middleware('auth.administrator');
Route::post('/setPinjaman', [SettingPinjamanController::class, 'settingPinjaman']);
Route::post('/startSimulation', [UtilityRoomController::class, 'start']);
Route::post('/pauseSimulation', [UtilityRoomController::class, 'pause']);
Route::post('/resumeSimulation', [UtilityRoomController::class, 'resume']);
Route::post('/endSimulation', [UtilityRoomController::class, 'end']);
Route::post('/nextDaySimulation', [UtilityRoomController::class, 'nextDay']);

// Fitur Room Admin
Route::get('/lobby/{room_id}', [RoomControllerAdmin::class,'index'])->middleware('auth.administrator');
Route::get('/lobby/{room_id}/settingPengirimanLCL',[SettingPengirimanController::class,'indexLCL']);
Route::get('/lobby/{room_id}/settingPengirimanFCL', [SettingPengirimanController::class,'indexFCL']);
Route::get('/lobby/{room_id}/settingPinjaman', [SettingPinjamanController::class,'index']);
Route::get('/lobby/{room_id}/settingBahanBaku', [SettingBahanBaku::class,'index']);
Route::post('/setting_bahan_baku', [SettingBahanBaku::class,'setting'])->middleware('auth.administrator');
Route::get('/lobby/{room_id}/utilityRoom', [UtilityRoomController::class,'index']);

// Fitur Room Player
Route::get('/player-lobby/{roomCode}', [RoomControllerPlayer::class, 'index'])->name('player.lobby');
Route::get('/player-lobby/{roomCode}/playerProfile', [RoomControllerPlayer::class, 'profile']);
Route::get('/player-lobby/{roomCode}/warehouseMachine', [RoomControllerPlayer::class,'warehouseMachine']);
Route::get('/player-lobby/{room_id}/production', [RoomControllerPlayer::class,'production']);
Route::get('/player-lobby/{room_id}/listOfDemands', [RoomControllerPlayer::class,'showDemand']);
Route::post('/take-demand', [DemandController::class,'takeDemand']);


Route::post('/updateRevenue',[RoomControllerPlayer::class,'updateRevenue']);
Route::post('/purchaseWarehouse',[PlayerPurchaseController::class,'purchaseWarehouse']);
Route::post('/purchaseMachine',[PlayerPurchaseController::class,'purchaseMachine']);
Route::post('/updateWarehouse',[PlayerPurchaseController::class,'updateWarehouse']);
Route::post('/produceItem',[ProductionController::class,'produce']);
Route::post('/getDemands', [DemandController::class, 'getDemands']);


