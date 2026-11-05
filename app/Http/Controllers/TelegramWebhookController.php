<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\ServiceRequest;
use App\Services\Telegram;
use Illuminate\Http\Request;

class TelegramWebhookController extends Controller
{
    public function __invoke(Request $request, string $secret, Telegram $tg)
    {
        abort_unless(hash_equals((string) config('qreaget.telegram_webhook_secret'), $secret), 404);

        $m = $request->input('message');
        if (! is_array($m) || ! isset($m['chat']['id'])) {
            return response()->json(['ok' => true]);
        }
        $chatId = (string) $m['chat']['id'];
        $text = trim((string) ($m['text'] ?? ''));
        $from = trim(($m['from']['first_name'] ?? '').' '.($m['from']['last_name'] ?? '')) ?: 'Operator';

        // Setup helpers that work from any chat.
        if (preg_match('#^/(start|id)\b#', $text)) {
            $tg->send("Chat ID: <code>{$chatId}</code>\nPaste it into Admin → Settings → Telegram.", [], $chatId);

            return response()->json(['ok' => true]);
        }

        // Everything else only from the configured admin chat.
        if ($chatId !== (string) $tg->chatId()) {
            return response()->json(['ok' => true]);
        }

        if ($text === '' ) {
            return response()->json(['ok' => true]);
        }

        // /chats — list open chats
        if (preg_match('#^/chats\b#', $text)) {
            $list = ChatSession::where('status', 'open')->latest('last_message_at')->take(15)->get()
                ->map(fn ($s) => "#{$s->id} · ".Telegram::e($s->displayName()).' · '.optional($s->last_message_at)->format('d.m H:i'))
                ->implode("\n");
            $tg->send($list ?: 'No open chats.');

            return response()->json(['ok' => true]);
        }

        // /requests — latest new requests
        if (preg_match('#^/requests\b#', $text)) {
            $list = ServiceRequest::whereIn('status', ['new', 'review'])->latest()->take(10)->get()
                ->map(fn ($r) => "{$r->number} · ".Telegram::e($r->name).' · '.Telegram::e($r->phone))
                ->implode("\n");
            $tg->send($list ?: 'No new requests.');

            return response()->json(['ok' => true]);
        }

        $session = null;
        $body = $text;

        // /r 12 text — answer chat #12
        if (preg_match('#^/r(?:eply)?\s+\#?(\d+)\s+(.+)$#su', $text, $mm)) {
            $session = ChatSession::find((int) $mm[1]);
            $body = $mm[2];
        } elseif (isset($m['reply_to_message']['message_id'])) {
            $orig = ChatMessage::where('telegram_message_id', $m['reply_to_message']['message_id'])->first();
            $session = $orig?->session;
            if (! $session && preg_match('/Chat #(\d+)/', (string) ($m['reply_to_message']['text'] ?? ''), $mm)) {
                $session = ChatSession::find((int) $mm[1]);
            }
        }

        if (! $session) {
            $tg->send('ℹ️ Reply to a chat message, or use <code>/r ID text</code>. Commands: /chats, /requests, /id');

            return response()->json(['ok' => true]);
        }

        $session->messages()->create([
            'sender' => 'admin',
            'author' => $from,
            'body' => $body,
            'telegram_message_id' => $m['message_id'] ?? null,
        ]);
        $session->update(['last_message_at' => now(), 'unread_admin' => false]);

        return response()->json(['ok' => true]);
    }
}
