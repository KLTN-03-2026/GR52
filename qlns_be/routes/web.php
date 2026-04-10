<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\AuthUngVienController;

Route::post('/ung-vien/register', [AuthUngVienController::class, 'register']);
Route::post('/ung-vien/login', [AuthUngVienController::class, 'login']);
Route::post('/ung-vien/logout', [AuthUngVienController::class, 'logout']);
