<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{id}', function ($user, $id) {
    \Log::debug('Channel authorization attempt', [
        'user_id' => $user->id,
        'channel_id' => $id
    ]);
    return true; // Temporairement autorisez tout pour tester
});
Broadcast::channel('test', function ($user, $id) {
    return true;
});