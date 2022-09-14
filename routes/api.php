<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\ForgotController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\User\CompanyController;
use App\Http\Controllers\User\BlogsController;
use App\Http\Controllers\User\QuestionController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\User\ChatController;
use App\Http\Controllers\User\UserForgotController;


/*BlogsController
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
    Route::post('forget_password', [UserForgotController::class, 'forget_password']);
    Route::get('reset_password', [UserForgotController::class, 'reset_password']);
    Route::post('update-new-password', [UserForgotController::class, 'updateNewPassword']);
    //PROTDECTED ROUTE
    Route::middleware('auth:api')->group(function (){
        Route::post('create-sub-user', [LoginController::class, 'createSubUser']);
        Route::post('employee-list', [CompanyController::class, 'employeeListById']);
        Route::post('user-logout', [LoginController::class, 'userLogout']);
        // blogsroute
        Route::post('add-blogs', [BlogsController::class, 'addBlogs']);
        Route::post('update-blogs', [BlogsController::class, 'updateBlogs']);
        Route::post('delete-blogs', [BlogsController::class, 'deleteBlogs']);
        Route::get('blogs-list', [BlogsController::class, 'blogsList']);
        //questionroute
        Route::post('add-question', [QuestionController::class, 'addQuestion']);
        Route::post('update-question', [QuestionController::class, 'updateQuestions']);
        Route::post('delete-question', [QuestionController::class, 'deleteQuestion']);
        Route::get('question-list', [QuestionController::class, 'questionList']);
        //edit sub user
        Route::post('update-sub-user', [UserController::class, 'updateSubUser']);
        //delete sub user
        Route::post('delete-sub-user', [UserController::class, 'deleteSubUser']);
        // review user route
        Route::post('add-review', [ReviewController::class, 'addReview']);
        Route::post('update-review', [ReviewController::class, 'updateReview']);
        Route::get('review-list', [ReviewController::class, 'ReviewList']);
        // company list
        Route::get('company-list-byuser', [CompanyController::class, 'companyListByUser']);
        //chating route
        Route::post('chating', [ChatController::class, 'Chating']);
        Route::get('show-chating', [ChatController::class, 'showChating']);
    });
});
//ADMIN ROUTE
Route::get('login', [AdminLoginController::class, 'login'])->name('login');
Route::prefix('admin')->group(function (){
    Route::post('login', [AdminLoginController::class, 'login']);
    Route::post('register', [AdminLoginController::class, 'register']);
    Route::post('forget_password', [ForgotController::class, 'forget_password']);
    Route::get('reset_password', [ForgotController::class, 'reset_password']);
    Route::post('update-new-password', [ForgotController::class, 'updateNewPassword']);
    //protected route
    Route::middleware(['auth:admin-api'])->group(function () {
        Route::get('pending-list', [AdminController::class, 'companyPendingList']);
        Route::post('update-company-status', [AdminController::class, 'updateCompanyStatus']);
        Route::get('comapny-approved-list', [AdminController::class, 'comapnyApprovedList']);
        Route::post('company-list', [AdminController::class, 'companyList']);
        Route::get('designation-list', [AdminLoginController::class, 'designationList']);
        Route::get('get-comp-status', [AdminController::class, 'getCompStatus']);
        Route::post('designation-add', [AdminLoginController::class, 'designationAdd']);
        Route::post('admin-logout', [AdminLoginController::class, 'adminLogout']);
   });
});
