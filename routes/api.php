<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('v1')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [LoginController::class, 'register']);

    Route::middleware('auth:api')->group(function () {
        Route::resource('posts', PostController::class);
    });

    
});
Route::get('login', [AdminLoginController::class, 'login']);

Route::prefix('admin')->group(function () {
    Route::post('login', [AdminLoginController::class, 'login']);
    Route::post('register', [AdminLoginController::class, 'register']);

    Route::middleware('auth:admin-api')->group(function () {
        Route::get('users-list', [AdminController::class,'index']);
    });
});
