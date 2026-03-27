<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index']);
