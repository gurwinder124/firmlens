<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\ForgotController;
use App\Http\Controllers\Admin\AdminController;

use App\Http\Controllers\User\CompanyController;


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
// USER ROUTE
Route::prefix('v1')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('registerCompany', [LoginController::class, 'registerCompany']);
    Route::middleware('auth:api')->group(function () {
        Route::post('create-sub-user', [LoginController::class, 'createSubUser']);
        Route::post('employee-list', [CompanyController::class, 'employeeListById']);

    });
});
//ADMIN ROUTE
Route::get('login', [AdminLoginController::class, 'login'])->name('login');

Route::prefix('admin')->group(function (){
    Route::post('login', [AdminLoginController::class, 'login']);
    Route::post('register', [AdminLoginController::class, 'register']);
    Route::middleware(['auth:admin-api'])->group(function () {
        Route::get('pending-list', [AdminController::class, 'companyPendingList']);
        Route::post('update-company-status', [AdminController::class, 'updateCompanyStatus']);
        Route::get('comapny-approved-list', [AdminController::class, 'comapnyApprovedList']);
        Route::post('company-list', [AdminController::class, 'companyList']);
        Route::post('forget_password', [ForgotController::class, 'forget_password']);
        Route::post('reset_password', [ForgotController::class, 'reset_password']);
        Route::post('update-new-password', [ForgotController::class,'updateNewPassword']);
   });
});
