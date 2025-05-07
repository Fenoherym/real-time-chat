<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

Route::get('/user', function (Request $request) {
    return User::all();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')
    ->get('/chat-message/{$receiver_id}', [ChatController::class, 'index'])
    ->name('api.chat-messages');