<?php

use App\Http\Controllers\API\IndexServersController;
use App\Http\Controllers\API\ShowServerController;
use App\Http\Controllers\API\ShowServerVotesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('/servers')->name('servers.')->group(function () {
    Route::get('/', IndexServersController::class)->name('index');
    Route::get('/{server}', [ShowServerController::class, 'stats'])->name('show');
    Route::get('/{server}/favicon', [ShowServerController::class, 'favicon'])->name('favicon');
    Route::get('/{server}/votes', ShowServerVotesController::class)->name('show');
});
