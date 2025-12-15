<?php

use App\Http\Controllers\ProfileController;
use App\Models\Message;
use App\Events\MessageSent;
use App\Events\PrivateMessageSent;
use App\Events\MessageRead;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

// 1. Home Page (Welcome)
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('home');

// 2. Authenticated Chat Routes
Route::middleware(['auth', 'verified'])->group(function () {

    // A. Main Chat Page
    Route::get('/chat', function () {
        return Inertia::render('Chat/Index');
    })->name('chat');

    // B. Get All Users (WITH UNREAD COUNT)
    Route::get('/users', function () {
        // Get all users except yourself
        $users = App\Models\User::where('id', '!=', auth()->id())->get();

        // Count unread messages for each user manually
        // (This counts messages sent BY them, TO me, that are NOT read)
        $users->map(function ($user) {
            $user->unread = App\Models\Message::where('user_id', $user->id)
                ->where('receiver_id', auth()->id())
                ->whereNull('read_at')
                ->count();
            return $user;
        });

        return $users;
    })->name('users.index');

    // C. Get Messages for a specific conversation
    Route::get('/chat/messages/{user}', function (App\Models\User $user) {
        return Message::where(function ($query) use ($user) {
            $query->where('user_id', auth()->id())
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('receiver_id', auth()->id());
        })
            ->with('user')
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values();
    });

    // D. Send a Message
    Route::post('/chat/send', function (Request $request) {
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

        broadcast(new PrivateMessageSent($message))->toOthers();

        return $message->load('user');
    });

    // E. Mark Messages as Read (Blue Ticks)
    Route::post('/chat/read', function (Request $request) {
        $request->validate(['sender_id' => 'required|exists:users,id']);

        $unreadMessages = Message::where('user_id', $request->sender_id)
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->get();

        if ($unreadMessages->count() > 0) {
            Message::whereIn('id', $unreadMessages->pluck('id'))->update(['read_at' => now()]);
            broadcast(new MessageRead($unreadMessages->pluck('id'), $request->sender_id));
        }

        return response()->json(['status' => 'success']);
    });

    // F. Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

// 3. Profile Routes (Standard Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
