<?php

use App\Http\Controllers\InstallController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/services/{slug}', [PageController::class, 'service'])->name('service');
Route::get('/lang/{locale}', [PageController::class, 'lang'])->name('lang');
Route::post('/request', [RequestController::class, 'store'])->middleware('throttle:forms')->name('request.store');

// TODO: contact page
Route::view('/contact', 'site.soon')->name('contact');

Route::get('/install/{key}', InstallController::class)->name('install');
