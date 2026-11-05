<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Services\Telegram;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function start(Request $request)
    {
        abort_unless(setting('chat_enabled') === '1', 404);
        $data = $request->validate([
            'name' => 'nullable|string|max:100',
            'contact' => 'nullable|string|max:120',
            'token' => 'nullable|string|max:64',
        ]);

        $session = $this->find($data['token'] ?? null);
        if (! $session) {
            $user = $request->user();
            $session = ChatSession::create([
                'token' => Str::random(48),
                'user_id' => $user?->id,
                'name' => $data['name'] ?? $user?->name,
                'contact' => $data['contact'] ?? ($user ? trim($user->phone.' '.$user->email) : null),
                'ip' => $request->ip(),
                'page_url' => Str::limit((string) $request->headers->get('referer'), 250, ''),
            ]);
        } elseif (filled($data['name'] ?? null) || filled($data['contact'] ?? null)) {
            $session->update(array_filter([
                'name' => $data['name'] ?? null,
                'contact' => $data['contact'] ?? null,
            ]));
        }

        return response()->json([
            'token' => $session->token,
            'messages' => $session->messages()->get()->map->toPublic(),
        ]);
    }

    public function send(Request $request, Telegram $telegram)
    {
        abort_unless(setting('chat_enabled') === '1', 404);
        $data = $request->validate([
            'token' => 'required|string|max:64',
            'body' => 'required|string|max:2000',
        ]);
        $session = $this->find($data['token']);
        abort_unless($session, 404);

        $isFirst = ! $session->messages()->where('sender', 'visitor')->exists();

        $msg = $session->messages()->create([
            'sender' => 'visitor',
            'author' => $session->displayName(),
            'body' => trim($data['body']),
        ]);
        $session->update(['last_message_at' => now(), 'unread_admin' => true, 'status' => 'open']);

        $telegram->forwardChat($session, $msg);

        if ($isFirst) {
            $session->messages()->create([
                'sender' => 'system',
                'author' => setting('site_name'),
                'body' => __('site.chat.auto_reply'),
            ]);
        }

        return response()->json([
            'message' => $msg->toPublic(),
            'messages' => $session->messages()->where('id', '>', $msg->id)->get()->map->toPublic(),
        ]);
    }

    public function poll(Request $request)
    {
        $session = $this->find($request->query('token'));
        if (! $session) {
            return response()->json(['messages' => []]);
        }
        $after = (int) $request->query('after', 0);

        return response()->json([
            'messages' => $session->messages()->where('id', '>', $after)->get()->map->toPublic(),
        ]);
    }

    private function find(?string $token): ?ChatSession
    {
        return $token ? ChatSession::where('token', $token)->first() : null;
    }
}
