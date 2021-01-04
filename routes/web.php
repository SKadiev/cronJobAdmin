<?php

use App\Http\Controllers\DomainsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DevicesController;
use Illuminate\Support\Facades\App;
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



Auth::routes();

Route::resource('domain', DomainsController::class)->middleware('auth');
Route::resource('page', PagesController::class)->middleware('auth');
Route::get('device',[ DevicesController::class , 'index'])->middleware('auth');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('users', [UsersController::class, 'index']);
