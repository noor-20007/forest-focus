<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FocusSessionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('forest-focus');
});




    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/sessions', [FocusSessionController::class, 'index']);
    Route::post('/sessions', [FocusSessionController::class, 'store']);
