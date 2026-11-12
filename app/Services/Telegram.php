<?php

namespace App\Services;

use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Telegram
{
    public function token(): ?string
    {
        return setting('telegram_bot_token');
    }

    public function chatId(): ?string
    {
        return setting('telegram_chat_id');
    }

    public function configured(): bool
    {
        return filled($this->token()) && filled($this->chatId());
    }

    public function call(string $method, array $params = []): ?array
    {
        if (! filled($this->token())) {
            return null;
        }
        try {
            $res = Http::timeout(8)->asJson()
                ->post("https://api.telegram.org/bot{$this->token()}/{$method}", $params);

            return $res->json();
        } catch (\Throwable $e) {
            Log::warning('Telegram error: '.$e->getMessage());

            return null;
        }
    }

    public function send(string $html, array $extra = [], ?string $chatId = null): ?int
    {
        if (! $this->configured() && ! $chatId) {
            return null;
        }
        $res = $this->call('sendMessage', array_merge([
            'chat_id' => $chatId ?: $this->chatId(),
            'text' => mb_substr($html, 0, 4000),
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => true,
        ], $extra));

        return $res['result']['message_id'] ?? null;
    }

    public static function e(?string $s): string
    {
        return htmlspecialchars((string) $s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public function notifyRequest(ServiceRequest $r): void
    {
        if (setting('notify_requests') !== '1') {
            return;
        }
        $lines = [
            "📝 <b>New request {$r->number}</b>",
            '👤 '.self::e($r->name),
            '📞 '.self::e($r->phone),
        ];
        if ($r->email) {
            $lines[] = '✉️ '.self::e($r->email);
        }
        if ($r->department) {
            $lines[] = '🗂 '.self::e($r->department->name_hy).' / '.self::e($r->department->name_ru);
        }
        $lines[] = '';
        $lines[] = self::e(mb_substr($r->message, 0, 1500));
        $this->send(implode("\n", $lines));
    }
}
