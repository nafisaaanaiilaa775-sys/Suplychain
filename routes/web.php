<?php

use Illuminate\Support\Facades\Route;

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


