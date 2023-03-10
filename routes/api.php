<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|-------------------------------]
-------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [AuthController::class, 'login'])->name('login');

Route::post('users', [UserController::class, 'store'])->name('users.store');

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('', 'index')->name('users.index');
        Route::put('{user}', 'update')->name('users.update');
        Route::delete('{user}', 'destroy')->name('users.destroy');
    });

    Route::post('transaction', [TransactionController::class, 'store'])->name('transactions.store');

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
