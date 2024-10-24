<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AprioriController;
use App\Http\Controllers\MenuServiceController;
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

Route::get('/home', [HomeController::class, 'index'])->name('home.index');

Route::resource('menus', MenuController::class);

Route::get('/select-menu', [MenuServiceController::class, 'selectMenu'])->name('menu-services.index');
Route::post('/validate-menu', [MenuServiceController::class, 'validateMenu'])->name('validate.menu');
Route::post('/confirm-order', [MenuServiceController::class, 'confirmOrder'])->name('confirm.order');

Route::resource('transactions', TransactionController::class);

Route::get('/apriori', [AprioriController::class, 'index'])->name('apriori.index');
Route::post('/apriori', [AprioriController::class, 'process']);