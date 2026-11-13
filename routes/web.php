<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/services/{slug}', [PageController::class, 'service'])->name('service');
Route::get('/articles', [PageController::class, 'articles'])->name('articles');
Route::get('/articles/{slug}', [PageController::class, 'article'])->name('article');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/lang/{locale}', [PageController::class, 'lang'])->name('lang');
Route::post('/request', [RequestController::class, 'store'])->middleware('throttle:forms')->name('request.store');

Route::get('/install/{key}', InstallController::class)->name('install');

// ---------- Auth ----------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:forms');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
