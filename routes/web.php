<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AprioriController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('menus', MenuController::class);
Route::get('/select-menu', [MenuController::class, 'selectMenu']);
Route::post('/validate-menu', [MenuController::class, 'validateMenu']);
Route::post('/submit-order', [MenuController::class, 'submitOrder']);
Route::post('/confirm-order', [MenuController::class, 'confirmOrder'])->name('confirm.order');

Route::resource('transactions', TransactionController::class);

Route::get('/apriori', [AprioriController::class, 'index']);
Route::post('/apriori', [AprioriController::class, 'process']);