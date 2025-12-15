<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Events\MessageSent;
use App\Events\PrivateMessageSent;
use App\Events\MessageRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ChatController extends Controller
{
    /**
     * Render the Chat Interface.
     */
    public function index()
    {
        return Inertia::render('Chat/Index');
    }

    /**
     * Fetch users with optimized unread count.
     * (Fixes the N+1 performance issue in your original code)
     */
    public function getUsers()
    {
        $authId = auth()->id();

        // Optimized Query: Fetches users and unread counts in a single database query
        $users = User::where('id', '!=', $authId)
            ->withCount(['messages as unread' => function ($query) use ($authId) {
                $query->where('receiver_id', $authId)
                      ->whereNull('read_at');
            }])
            ->get();

        return response()->json($users);
    }

    /**
     * Fetch conversation history.
     */
    public function getMessages(User $user)
    {
        $authId = auth()->id();

        return Message::where(function ($query) use ($authId, $user) {
                $query->where('user_id', $authId)
                      ->where('receiver_id', $user->id);
            })
            ->orWhere(function ($query) use ($authId, $user) {
                $query->where('user_id', $user->id)
                      ->where('receiver_id', $authId);
            })
            ->with('user') // Eager load sender details
            ->latest()
            ->take(50)     // Limit to last 50 for performance
            ->get()
            ->reverse()
            ->values();
    }

    /**
     * Handle message sending (Text + Images).
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'content' => 'nullable|string',
            'image' => 'nullable|image|max:10240', // 10MB Max
            'receiver_id' => 'required|exists:users,id'
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('chat_images', 'public');
        }

        $message = Message::create([
            'user_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
            'image_path' => $path,
        ]);

        // Broadcast to WebSocket Channel
        broadcast(new PrivateMessageSent($message))->toOthers();

        return $message->load('user');
    }

    /**
     * Mark messages as read.
     */
    public function markAsRead(Request $request)
    {
        $request->validate(['sender_id' => 'required|exists:users,id']);

        $authId = auth()->id();

        // Find unread messages from this sender
        $unreadMessages = Message::where('user_id', $request->sender_id)
            ->where('receiver_id', $authId)
            ->whereNull('read_at')
            ->get();

        if ($unreadMessages->count() > 0) {
            // Update Database
            Message::whereIn('id', $unreadMessages->pluck('id'))->update(['read_at' => now()]);
            
            // Broadcast "Double Tick" event
            broadcast(new MessageRead($unreadMessages->pluck('id'), $request->sender_id));
        }

        return response()->json(['status' => 'success']);
    }
}