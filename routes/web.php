<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\TelegramWebhookController;
use Illuminate\Support\Facades\Route;

// ---------- Public site ----------
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/services/{slug}', [PageController::class, 'service'])->name('service');
Route::get('/articles', [PageController::class, 'articles'])->name('articles');
Route::get('/articles/{slug}', [PageController::class, 'article'])->name('article');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/lang/{locale}', [PageController::class, 'lang'])->name('lang');
Route::post('/request', [RequestController::class, 'store'])->middleware('throttle:forms')->name('request.store');

// ---------- Live chat ----------
Route::prefix('chat')->middleware('throttle:chat')->group(function () {
    Route::post('/start', [ChatController::class, 'start'])->name('chat.start');
    Route::post('/send', [ChatController::class, 'send'])->name('chat.send');
    Route::get('/poll', [ChatController::class, 'poll'])->name('chat.poll');
});
Route::post('/telegram/webhook/{secret}', TelegramWebhookController::class)->name('telegram.webhook');

// ---------- Installer (no SSH needed) ----------
Route::get('/install/{key}', InstallController::class)->name('install');

// ---------- Auth ----------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:forms');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ---------- Client account ----------
Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('index');
    Route::get('/requests/new', [AccountController::class, 'create'])->name('requests.create');
    Route::get('/requests/{serviceRequest}', [AccountController::class, 'show'])->name('requests.show');
    Route::post('/requests/{serviceRequest}/comment', [AccountController::class, 'comment'])->name('requests.comment');
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
});

// ---------- Admin panel ----------
Route::middleware(['auth', 'staff'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/requests/export', [Admin\RequestController::class, 'export'])->name('requests.export');
    Route::post('/requests/bulk', [Admin\RequestController::class, 'bulk'])->name('requests.bulk');
    Route::post('/requests/{serviceRequest}/event', [Admin\RequestController::class, 'event'])->name('requests.event');
    Route::resource('requests', Admin\RequestController::class)
        ->parameters(['requests' => 'serviceRequest'])->except(['edit']);

    Route::get('/chats', [Admin\ChatController::class, 'index'])->name('chats.index');
    Route::get('/chats/unread', [Admin\ChatController::class, 'unread'])->name('chats.unread');
    Route::get('/chats/{chat}', [Admin\ChatController::class, 'show'])->name('chats.show');
    Route::get('/chats/{chat}/poll', [Admin\ChatController::class, 'poll'])->name('chats.poll');
    Route::post('/chats/{chat}/reply', [Admin\ChatController::class, 'reply'])->name('chats.reply');
    Route::post('/chats/{chat}/toggle', [Admin\ChatController::class, 'toggle'])->name('chats.toggle');
    Route::delete('/chats/{chat}', [Admin\ChatController::class, 'destroy'])->name('chats.destroy');

    Route::middleware('staff:admin')->group(function () {
        Route::resource('users', Admin\UserController::class)->except(['show']);
        Route::resource('departments', Admin\DepartmentController::class)->except(['show']);
        Route::resource('articles', Admin\ArticleController::class)->except(['show']);
        Route::resource('faqs', Admin\FaqController::class)->except(['show']);
        Route::get('/settings', [Admin\SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [Admin\SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/telegram/webhook', [Admin\SettingController::class, 'webhook'])->name('settings.webhook');
        Route::post('/settings/telegram/test', [Admin\SettingController::class, 'test'])->name('settings.test');
        Route::get('/activity', [Admin\DashboardController::class, 'activity'])->name('activity');
    });
});
