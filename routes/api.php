<?php

use App\Http\Controllers\AdminController;
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



Route::prefix('admin')->group(function () {
    Route::post('login', [AdminController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function (){
        Route::post('home', [AdminController::class, 'home']);
    });
});
