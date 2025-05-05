<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    
    public function index(int $receiver_id, Request $request)
    {
        
        $users = User::onWhere('id', '!=', $receiver_id)
            ->where('id', '!=', $request->user()->id)
            ->get();
        
        return response()->json([
            'users' => $users,
        ]);
    }

}
