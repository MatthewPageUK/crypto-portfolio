<?php

use App\Http\Controllers\CryptoTokenController;
use App\Http\Controllers\CryptoTransactionController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');
Route::get('/token/add', [CryptoTokenController::class, 'create'])->middleware('auth')->name('addtoken');
Route::get('/token/{token}/transactions/add', [CryptoTransactionController::class, 'create'])->middleware('auth')->name('addtransaction');

require __DIR__.'/auth.php';
