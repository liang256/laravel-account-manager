<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;

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

Route::get('/dashboard', function () {
    // return view('dashboard');
    return redirect()->route('dashboard');
})->middleware(['auth']);

Route::middleware(['auth'])->group(function () {
    Route::get('/accounts', [AccountController::class, 'index'])->name('dashboard');
    Route::get('/accounts/{userId}', [AccountController::class, 'show'])->name('accounts.show');

    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions/store', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/edit/{trans}', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/update/{trans}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/delete/{trans}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
});

require __DIR__.'/auth.php';
