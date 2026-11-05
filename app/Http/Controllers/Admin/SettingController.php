<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use App\Services\Telegram;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public const KEYS = [
        'site_name', 'phone_1', 'phone_2', 'email',
        'address_hy', 'address_ru', 'address_en', 'hours_hy', 'hours_ru', 'hours_en',
        'instagram', 'facebook', 'telegram_link', 'whatsapp', 'map_query',
        'hero_title_hy', 'hero_title_ru', 'hero_title_en', 'hero_text_hy', 'hero_text_ru', 'hero_text_en',
        'about_hy', 'about_ru', 'about_en',
        'telegram_bot_token', 'telegram_chat_id',
        'meta_description', 'analytics_code',
    ];

    public const FLAGS = ['chat_enabled', 'registration_enabled', 'notify_requests'];

    public function edit(Telegram $tg)
    {
        return view('admin.settings', [
            'webhookUrl' => $tg->webhookUrl(),
            'telegramInfo' => $tg->token() ? $tg->call('getWebhookInfo') : null,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate(['email' => 'nullable|email', 'instagram' => 'nullable|url', 'facebook' => 'nullable|url']);
        foreach (self::KEYS as $k) {
            if ($request->has($k)) {
                Setting::put($k, $request->input($k));
            }
        }
        foreach (self::FLAGS as $k) {
            Setting::put($k, $request->boolean($k) ? '1' : '0');
        }
        ActivityLog::record('settings.update');

        return back()->with('success', 'Saved');
    }

    public function webhook(Telegram $tg)
    {
        $res = $tg->setWebhook();

        return back()->with(($res['ok'] ?? false) ? 'success' : 'error', 'Telegram: '.json_encode($res, JSON_UNESCAPED_UNICODE));
    }

    public function test(Telegram $tg)
    {
        $id = $tg->send('✅ <b>'.Telegram::e(setting('site_name')).'</b>: test message from the website admin panel.');

        return back()->with($id ? 'success' : 'error', $id ? 'Telegram OK' : 'Telegram: message not sent. Check token and chat id.');
    }
}
