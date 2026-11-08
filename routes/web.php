<?php

use App\Http\Controllers\InstallController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/lang/{locale}', [PageController::class, 'lang'])->name('lang');

// TODO: separate pages
Route::view('/services', 'site.soon')->name('services');
Route::view('/services/{slug}', 'site.soon')->name('service');
Route::view('/contact', 'site.soon')->name('contact');

Route::get('/install/{key}', InstallController::class)->name('install');
