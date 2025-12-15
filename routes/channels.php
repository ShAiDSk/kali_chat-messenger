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

// 1. PRESENCE CHANNEL (The "Online" Green Dots)
// Allows any logged-in user to see who else is online.
Broadcast::channel('chat', function ($user) {
    return ['id' => $user->id, 'name' => $user->name];
});

// 2. PRIVATE CHANNEL (The 1-on-1 Messages)
// This is the CRITICAL part for "Communicating".
// It checks: Is the user trying to listen to THEIR OWN channel?
Broadcast::channel('chat.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});