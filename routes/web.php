<?php

use App\Http\Controllers\DomainsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DevicesController;
use App\Http\Controllers\VideosController;
use App\Http\Controllers\YoutubeChannelsController;
use App\Http\Controllers\RulesController;
use App\Http\Controllers\JobsController;

use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Jobs\SomeJob;

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
Route::resource('device', DevicesController::class)->middleware('auth');
Route::resource('user', UsersController::class)->middleware('auth');
Route::post('channel/importChannels', [YoutubeChannelsController::class, 'importChannels'])->name('channel');
Route::resource('channel', YoutubeChannelsController::class)->middleware('auth');
Route::resource('rule', RulesController::class)->middleware('auth');
Route::resource('job', JobsController::class)->middleware('auth');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/video', [VideosController::class, 'index'])->name('video');

Route::get('jobs/{jobsNumber}/{user}', function ($jobsNumber, $user) {
    
    $user = User::find($user);

    for ($i=0; $i < $jobsNumber ; $i++) { 
        
        SomeJob::dispatch($user);
    }

});
