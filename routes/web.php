<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AprioriController;
use App\Http\Controllers\MenuController;
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

Route::get('/apriori', [AprioriController::class, 'index']);
Route::post('/apriori', [AprioriController::class, 'process']);

Route::resource('menus', MenuController::class);
Route::resource('transactions', TransactionController::class);
