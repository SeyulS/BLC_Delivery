<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('join-room', function(){
    return true;
});

Broadcast::channel('player-remove', function(){
    return true;
});

Broadcast::channel('start-simulation', function(){
    return true;
});

Broadcast::channel('end-simulation', function(){
    return true;
});

Broadcast::channel('update-warehouse', function(){
    return true;
});

Broadcast::channel('update-revenue', function(){
    return true;
});
