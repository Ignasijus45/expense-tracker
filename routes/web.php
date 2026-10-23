<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/expenses/export/{format}', [ExpenseController::class, 'export']);
    Route::get('/dashboard', [ExpenseController::class, 'index']);
    Route::post('/expenses', [ExpenseController::class, 'store']);
    Route::view('/stats', 'stats');
});
