<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlertasController;


Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/alertas', [AlertasController::class, 'index']);
Route::post('/filtro-alertas', [AlertasController::class, 'filtrar']);
