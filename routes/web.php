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

Route::get('/add', [CryptoTokenController::class, 'create'])->middleware('auth')->name('addtoken');
Route::post('/add', [CryptoTokenController::class, 'store'])->middleware('auth')->name('storetoken');

Route::get('/token/{token}', [CryptoTokenController::class, 'show'])->middleware('auth')->name('token');

Route::get('/buy/{token}', [CryptoTransactionController::class, 'buy'])->middleware('auth')->name('buy');
Route::get('/sell/{token}', [CryptoTransactionController::class, 'sell'])->middleware('auth')->name('sell');

Route::post('/buy/{token}', [CryptoTransactionController::class, 'store'])->middleware('auth')->name('storetransaction');

Route::get('/transaction/{cryptoTransaction}/delete', [CryptoTransactionController::class, 'destroy'])->middleware('auth')->name('deletetransaction');

require __DIR__.'/auth.php';
