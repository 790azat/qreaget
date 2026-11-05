<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatSession;
use App\Services\Telegram;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $q = ChatSession::with('lastMessage')->withCount('messages');
        if ($request->query('status')) {
            $q->where('status', $request->query('status'));
        }
        if ($s = $request->query('q')) {
            $q->where(fn ($w) => $w->where('name', 'like', "%$s%")->orWhere('contact', 'like', "%$s%"));
        }

        return view('admin.chats.index', [
            'items' => $q->orderByDesc('unread_admin')->latest('last_message_at')->paginate(30)->withQueryString(),
            'telegram' => app(Telegram::class)->configured(),
        ]);
    }

    public function show(ChatSession $chat)
    {
        $chat->update(['unread_admin' => false]);

        return view('admin.chats.show', ['chat' => $chat->load('messages', 'user')]);
    }

    public function poll(Request $request, ChatSession $chat)
    {
        $after = (int) $request->query('after', 0);
        if ($request->boolean('seen')) {
            $chat->update(['unread_admin' => false]);
        }

        return response()->json([
            'messages' => $chat->messages()->where('id', '>', $after)->get()->map->toPublic(),
        ]);
    }

    public function unread()
    {
        return response()->json(['count' => ChatSession::where('unread_admin', true)->count()]);
    }

    public function reply(Request $request, ChatSession $chat, Telegram $tg)
    {
        $data = $request->validate(['body' => 'required|string|max:2000']);
        $msg = $chat->messages()->create([
            'sender' => 'admin',
            'author' => auth()->user()->name,
            'body' => $data['body'],
        ]);
        $chat->update(['last_message_at' => now(), 'unread_admin' => false]);
        // Keep the Telegram group in sync
        $tg->send("↪️ <b>Chat #{$chat->id}</b> answered from admin panel by ".Telegram::e(auth()->user()->name).":\n".Telegram::e($data['body']));

        if ($request->expectsJson()) {
            return response()->json(['message' => $msg->toPublic()]);
        }

        return back();
    }

    public function toggle(ChatSession $chat)
    {
        $chat->update(['status' => $chat->status === 'open' ? 'closed' : 'open']);

        return back();
    }

    public function destroy(ChatSession $chat)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $chat->delete();

        return redirect()->route('admin.chats.index')->with('success', 'Deleted');
    }
}
