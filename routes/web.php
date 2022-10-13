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

    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/create', [TransactionController::class, 'create'])->name('create');
        Route::post('/store', [TransactionController::class, 'store'])->name('store');
        Route::get('/edit/{trans}', [TransactionController::class, 'edit'])->name('edit');
        Route::put('/update/{trans}', [TransactionController::class, 'update'])->name('update');
        Route::delete('/delete/{trans}', [TransactionController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__ . '/auth.php';
