<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

use App\Http\Controllers\DisplayController;

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

/*
Route::get('/', function () {
    return view('welcome');
});*/

//

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Route::get('/', [HomeController::class, 'index']);

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [RegistrationController::class, 'index']);
    Route::post('/post', [RegistrationController::class, 'create_post']);
    Route::get('/post/{id}', [RegistrationController::class, 'show'])->name('post.show');
    Route::post('/comments', [RegistrationController::class, 'create_comment'])->name('comments.store');
    Route::patch('/comments/{id}', [RegistrationController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{id}', [RegistrationController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/restore', [RegistrationController::class, 'restore']);
    Route::get('/profile', [RegistrationController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile/{id}', [RegistrationController::class, 'name_update'])->name('profile.update');
    Route::match(['get', 'post'], '/search', [RegistrationController::class, 'search'])->name('search');
    Route::delete('/users/{id}', [RegistrationController::class, 'user_destroy'])->name('users.destroy');
    Route::put('/users/{id}/restore', [RegistrationController::class, 'user_restore'])->name('users.restore');
    Route::get('/posts/category/{categoryId}', [DisplayController::class, 'getPostsByCategory']);
});

// パスワードリセットのルート
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::put('/profile/update-password/{id}', [RegistrationController::class, 'password_update'])->name('profile.password.update');
