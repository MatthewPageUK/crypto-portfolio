<?php

use App\Http\Controllers\CryptoTokenController;
use App\Http\Controllers\CryptoTransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WelcomeController;
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

Route::get('/', [WelcomeController::class, 'index'])->middleware('guest')->name('welcome');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::group(['prefix' => 'token', 'middleware' => 'auth', 'as'=> 'token.'], function () {
    Route::get('/create', [CryptoTokenController::class, 'create'])->name('create');
    Route::post('/store', [CryptoTokenController::class, 'store'])->name('store');
    Route::get('/{token}', [CryptoTokenController::class, 'show'])->name('show');
    Route::get('/{token}/edit', [CryptoTokenController::class, 'edit'])->name('edit');
    Route::get('/{token}/delete', [CryptoTokenController::class, 'destroy'])->name('delete');
    Route::get('/{token}/buy', [CryptoTransactionController::class, 'buy'])->name('buy');
    Route::get('/{token}/sell', [CryptoTransactionController::class, 'sell'])->name('sell');
});

Route::post('/transaction/add/{token}', [CryptoTransactionController::class, 'store'])->middleware('auth')->name('storetransaction');
Route::get('/transaction/{cryptoTransaction}/delete', [CryptoTransactionController::class, 'destroy'])->middleware('auth')->name('deletetransaction');

require __DIR__.'/auth.php';
