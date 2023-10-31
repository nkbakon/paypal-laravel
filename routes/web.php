<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaypalController;

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

Route::get('createpaypal',[PaypalController::class, 'createpaypal'])->name('createpaypal');

Route::get('processPaypal',[PaypalController::class, 'processPaypal'])->name('processPaypal');
Route::get('processSuccess',[PaypalController::class, 'processSuccess'])->name('processSuccess');
Route::get('processFaild',[PaypalController::class, 'processFaild'])->name('processFaild');