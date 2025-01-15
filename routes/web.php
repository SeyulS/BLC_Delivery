<?php

use App\Http\Controllers\AdminHomeController;
use App\Http\Controllers\CreateRoomController;
use App\Http\Controllers\DeckController;
use App\Http\Controllers\LobbyRoomController;
use App\Http\Controllers\LoginAdminController;
use App\Http\Controllers\LoginPlayerController;
use App\Http\Controllers\PlayerHomeController;
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


Route::get('/api/players/{room_id}', [RoomControllerAdmin::class, 'getPlayers']);

// Post Method
Route::post('/kick-player', [RoomControllerAdmin::class, 'kickPlayer']);
Route::post('/joinRoom', [RoomControllerPlayer::class,'join'])->middleware('auth.player');
Route::post('/createRoom', [CreateRoomController::class,'createRoom'])->middleware('auth.administrator');
Route::post('/set_pinjaman', [SettingPinjamanController::class, 'settingPinjaman']);
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
Route::post('/updateRevenue',[RoomControllerPlayer::class,'updateRevenue']);
Route::get('/getDemand/{username}', [SettingPengirimanController::class, 'getDemand']);


