<?php

use App\Http\Controllers\CountryController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/map', function () {
    return view('map');
})->name('map');

Route::get('/currency', function () {
    return view('currency');
})->name('currency');

Route::get('/compare', function () {
    return view('compare');
})->name('compare');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// API Endpoints
Route::prefix('api')->group(function () {
    Route::get('/countries', [CountryController::class, 'index']);
    Route::get('/countries/{iso2}', [CountryController::class, 'show']);
    Route::get('/countries/{iso2}/risk-history', [CountryController::class, 'getRiskHistory']);

    Route::get('/ports', [PortController::class, 'index']);
    Route::get('/ports/search', [PortController::class, 'search']);

    Route::get('/watchlist', [WatchlistController::class, 'index']);
    Route::post('/watchlist', [WatchlistController::class, 'store']);
    Route::delete('/watchlist/{countryId}', [WatchlistController::class, 'destroy']);

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::post('/admin/ports', [AdminController::class, 'storePort']);
    Route::delete('/admin/ports/{id}', [AdminController::class, 'destroyPort']);
    Route::post('/admin/articles', [AdminController::class, 'storeArticle']);
    Route::delete('/admin/articles/{id}', [AdminController::class, 'destroyArticle']);
});


