<?php

use App\Http\Controllers\TokenController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\DiaryController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\BotController;
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
Route::get('/diary', [DiaryController::class, 'index'])->middleware(['auth'])->name('diary');

Route::group(['prefix' => 'backup', 'middleware' => 'auth', 'as'=> 'backup.'], function () {
    Route::get('/download', [BackupController::class, 'download'])->middleware(['auth'])->name('download');
    Route::get('/upload', [BackupController::class, 'upload'])->middleware(['auth'])->name('upload');
    Route::post('/restore', [BackupController::class, 'restore'])->middleware(['auth'])->name('restore');
});

Route::group(['prefix' => 'token', 'middleware' => 'auth', 'as'=> 'token.'], function () {
    Route::get('/create', [TokenController::class, 'create'])->name('create');
    Route::post('/store', [TokenController::class, 'store'])->name('store');
    Route::get('/{token}', [TokenController::class, 'show'])->name('show');
    Route::get('/{token}/edit', [TokenController::class, 'edit'])->name('edit');
    Route::post('/{token}/edit', [TokenController::class, 'update'])->name('update');
    Route::get('/{token}/delete', [TokenController::class, 'destroy'])->name('delete');
    Route::get('/{token}/buy', [TransactionController::class, 'buy'])->name('buy');
    Route::get('/{token}/sell', [TransactionController::class, 'sell'])->name('sell');
});

Route::group(['prefix' => 'transaction', 'middleware' => 'auth', 'as'=> 'transaction.'], function () {
    Route::post('/store', [TransactionController::class, 'store'])->name('store');
    Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
    Route::get('/{transaction}/delete', [TransactionController::class, 'destroy'])->name('delete');
    Route::get('/{transaction}/edit', [TransactionController::class, 'edit'])->name('edit');
    Route::post('/{transaction}/edit', [TransactionController::class, 'update'])->name('update');
});

Route::group(['prefix' => 'bot', 'middleware' => 'auth', 'as'=> 'bot.'], function () {
    Route::get('/', [BotController::class, 'index'])->name('index');
    Route::get('/create', [BotController::class, 'create'])->name('create');
    Route::post('/store', [BotController::class, 'store'])->name('store');
    Route::get('/{bot}', [BotController::class, 'show'])->name('show');
});

require __DIR__.'/auth.php';
