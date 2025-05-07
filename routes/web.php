<?php

use App\Http\Controllers\AdminHomeController;
use App\Http\Controllers\CreateRoomController;
use App\Http\Controllers\DeckController;
use App\Http\Controllers\DemandController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LobbyRoomController;
use App\Http\Controllers\LoginAdminController;
use App\Http\Controllers\LoginPlayerController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\ManageDataController;
use App\Http\Controllers\PlayerHomeController;
use App\Http\Controllers\PlayerPurchaseController;
use App\Http\Controllers\PlayerScoreController;
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
Route::get('/logoutAdmin',[LoginAdminController::class,'logout']);

// Home Admin
Route::get('/homeAdmin',[AdminHomeController::class,'index'])->middleware('auth.administrator');
Route::get('/manageData',[ManageDataController::class, 'index'])->middleware('auth.administrator');
Route::get('/manageAccount',[RegistPlayerController::class,'index'])->middleware('auth.administrator');
Route::get('/manageAdmin',[RegistAdminController::class,'index'])->middleware('auth.administrator');
Route::post('/deletePlayer', [RegistPlayerController::class, 'destroy'])->middleware('auth.administrator');
Route::post('/deleteAdmin', [RegistAdminController::class, 'destroy'])->middleware('auth.administrator');


// CRUD Raw Item
Route::get('raw-items', [RawItemController::class, 'index'])->middleware('auth.administrator');
Route::get('raw-items/data', [RawItemController::class, 'getData'])->middleware('auth.administrator');
Route::post('raw-items', [RawItemController::class, 'store'])->middleware('auth.administrator');
Route::get('raw-items/{id}/edit', [RawItemController::class, 'edit'])->middleware('auth.administrator');
Route::put('raw-items/{id}', [RawItemController::class, 'update'])->middleware('auth.administrator');
Route::get('raw-items/delete/{id}', [RawItemController::class, 'destroy'])->middleware('auth.administrator');


// CRUD Items
Route::get('items', [ItemController::class, 'index'])->middleware('auth.administrator');
Route::post('/createItem', [ItemController::class, 'create']);

// CRUD Machines
Route::get('machine', [MachineController::class, 'index'])->middleware('auth.administrator');
Route::post('/createMachine', [MachineController::class, 'create']);

Route::get('/api/players/{room_id}', [RoomControllerAdmin::class, 'getPlayers']);

// Post Method
Route::post('/lobby/kick-player', [RoomControllerAdmin::class, 'kickPlayer'])->name('kick.player');
Route::post('/joinRoom', [RoomControllerPlayer::class,'join'])->middleware('auth.player');
Route::post('/createRoom', [CreateRoomController::class,'createRoom'])->middleware('auth.administrator');
Route::delete('/deleteRoom/{room}', [CreateRoomController::class, 'destroy']);
Route::post('/startSimulation', [UtilityRoomController::class, 'start']);
Route::post('/pauseSimulation', [UtilityRoomController::class, 'pause']);
Route::post('/resumeSimulation', [UtilityRoomController::class, 'resume']);
Route::post('/endSimulation', [UtilityRoomController::class, 'end']);
Route::post('/nextDaySimulation', [UtilityRoomController::class, 'nextDay']);

// Fitur Room Admin
Route::get('/lobby/{room_id}', [RoomControllerAdmin::class,'index'])->middleware('auth.administrator');
Route::get('/lobby/{room_id}/settingPengirimanLCL',[SettingPengirimanController::class,'indexLCL'])->middleware('auth.administrator');
Route::get('/lobby/{room_id}/settingPengirimanFCL', [SettingPengirimanController::class,'indexFCL'])->middleware('auth.administrator');
Route::get('/lobby/{room_id}/settingPengirimanUdara', [SettingPengirimanController::class,'indexUdara'])->middleware('auth.administrator');
Route::get('/lobby/{room_id}/settingPinjaman', [SettingPinjamanController::class,'index'])->middleware('auth.administrator');
Route::get('/lobby/{room_id}/settingBahanBaku', [SettingBahanBaku::class,'index'])->middleware('auth.administrator');
Route::get('/lobby/{room_id}/demandInformation', [DemandController::class,'demandDeliveredInformation'])->middleware('auth.administrator');
Route::get('/lobby/{room_id}/playerScore', [PlayerScoreController::class,'index'])->middleware('auth.administrator');
Route::get('/lobby/{room_id}/player_transaction', [RoomControllerAdmin::class,'playerTransaction'])->middleware('auth.administrator');
Route::post('/setting_bahan_baku', [SettingBahanBaku::class,'setting']);

Route::post('/setPinjaman', [SettingPinjamanController::class, 'settingPinjaman']);
Route::get('/lobby/{room_id}/utilityRoom', [UtilityRoomController::class,'index'])->middleware('auth.administrator');
Route::post('/kirimLCL',[SettingPengirimanController::class,'setLCL']);
Route::post('/kirimFCL',[SettingPengirimanController::class,'setFCL']);
Route::post('/kirimUdara',[SettingPengirimanController::class,'setUdara']);


// Fitur Room Player
Route::get('/player-lobby/{roomCode}', [RoomControllerPlayer::class, 'index'])->name('player.lobby')->middleware('auth.player');
Route::get('/player-lobby/{room_id}/calendar', [RoomControllerPlayer::class,'calendar'])->middleware('auth.player');
Route::get('/player-lobby/{roomCode}/playerProfile', [RoomControllerPlayer::class, 'profile'])->middleware('auth.player');
Route::get('/player-lobby/{roomCode}/warehouseMachine', [RoomControllerPlayer::class,'warehouseMachine'])->middleware('auth.player');
Route::get('/player-lobby/{room_id}/production', [RoomControllerPlayer::class,'production'])->middleware('auth.player');
Route::get('/player-lobby/{room_id}/listOfDemands', [RoomControllerPlayer::class,'showDemand'])->middleware('auth.player');
Route::get('/player-lobby/{room_id}/marketIntelligence', [RoomControllerPlayer::class,'marketIntelligence'])->middleware('auth.player');
Route::get('/player-lobby/{room_id}/payingOffDebt',[RoomControllerPlayer::class,'payingOffDebt'])->middleware('auth.player');
Route::get('/player-lobby/{room_id}/deliveredDemand',[RoomControllerPlayer::class,'historyDemand'])->middleware('auth.player');
Route::get('/player-lobby/{room_id}/purchasedRawItems',[RoomControllerPlayer::class,'purchasedRawItems'])->middleware('auth.player');
Route::get('/player-lobby/{room_id}/transactionReceipt',[RoomControllerPlayer::class,'transactionReceipt'])->middleware('auth.player');

Route::post('/updateRevenue',[RoomControllerPlayer::class,'updateRevenue']);
Route::post('/purchaseWarehouse',[PlayerPurchaseController::class,'purchaseWarehouse']);
Route::post('/purchaseMachine',[PlayerPurchaseController::class,'purchaseMachine']);
Route::post('/updateWarehouse',[PlayerPurchaseController::class,'updateWarehouse']);
Route::post('/produceItem',[ProductionController::class,'produce']);
Route::post('/getDemands', [DemandController::class, 'getDemands']);
Route::post('/getDemandsFCL', [DemandController::class, 'getDemandsFCL']);
Route::post('/take-demand', [DemandController::class,'takeDemand']);
Route::post('/payDebt', [SettingPinjamanController::class,'payDebt']);


Route::post('/cobaGenerate',[CreateRoomController::class,'coba']);
