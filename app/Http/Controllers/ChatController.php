<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Events\MessageSent;
use App\Models\ChatMessage;
use Illuminate\Http\Request;


class ChatController extends Controller
{
    public function index(int $id)
    {
        return view('chat.index', [
            "current_user" => auth()->user(),
            "receiver_id" => $id,
        ]);
    }
    
    public function get(int $receiver_id, Request $request)
    {     
        
        $messages = ChatMessage::query()
            ->where(function ($query) use ($receiver_id) {
                $query->where('sender_id', auth()->id())
                      ->where('receiver_id', $receiver_id);
            })
            ->orWhere(function ($query) use ($receiver_id) {
                $query->where('sender_id', $receiver_id)
                      ->where('receiver_id', auth()->id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        
        return response()->json([
            "messages" => $messages,
        ]);
    }

    public function store(Request $request)
    {
        \Log::debug('Received message request', $request->all());

        $message = ChatMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'status' => 'sent'
        ]);

        \Log::debug('Message created', ['message' => $message->toArray()]);

        try {
            broadcast(new MessageSent($message));
            \Log::debug('Broadcast initiated');
        } catch (\Exception $e) {
            \Log::error('Broadcast failed', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'message' => $message
        ]);
    }

}
