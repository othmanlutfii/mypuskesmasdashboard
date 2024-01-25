<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Fakta_penjualanController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\doktercontroller;
use App\Http\Controllers\ruangcontroller;




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

// Route::get('/', function () {
//     return view('dashboard');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// });

Route::get('/', [Fakta_penjualanController::class,'index']);
Route::get('/dokter', [doktercontroller::class,'index']);
Route::get('/ruang', [ruangcontroller::class,'index']);


