<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
class MessageController extends Controller
{
  
    public function send(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'textContent' => 'required|string'
        ]);

        $message = Message::create([
            'user_sender_id' => Auth::id(),
            'user_receiver_id' => $request->receiver_id,
            'textContent' => $request->textContent,
            'status' => 'unread',
        ]);
        Notification::sendToUser(
            $request->receiver_id,
            'new_message',
            "You have a new message from " . Auth::user()->first_name . "."
        );

        return response()->json($message, 201);
    }


    public function conversation($userId): \Illuminate\Http\JsonResponse
    {
        $authId = Auth::id();


        Message::where('user_sender_id', $userId)
            ->where('user_receiver_id', $authId)
            ->where('status', 'unread')
            ->update(['status' => 'read']);

        $messages = Message::where(function ($query) use ($authId, $userId) {
            $query->where('user_sender_id', $authId)->where('user_receiver_id', $userId);
        })->orWhere(function ($query) use ($authId, $userId) {
            $query->where('user_sender_id', $userId)->where('user_receiver_id', $authId);
        })->orderBy('created_at')->get();

        return response()->json($messages);
    }

    public function chatList(Request $request): \Illuminate\Http\JsonResponse
    {
        $authId = Auth::id();
        $search = $request->query('search');

        $contactIds = Message::where('user_sender_id', $authId)
            ->orWhere('user_receiver_id', $authId)
            ->get()
            ->map(function ($message) use ($authId) {
                return $message->user_sender_id == $authId
                    ? $message->user_receiver_id
                    : $message->user_sender_id;
            })
            ->unique()
            ->values();

        $usersQuery = User::whereIn('id', $contactIds);

        if ($search) {
            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        $users = $usersQuery->get();

        return response()->json($users);
    }
}
