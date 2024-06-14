<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BoardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PasswordController;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

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
    Route::get('/', [BoardController::class, 'index']);
    Route::post('/post', [BoardController::class, 'store']);
    Route::get('/post/{id}', [BoardController::class, 'show'])->name('post.show');
    Route::post('/comments', [BoardController::class, 'store2'])->name('comments.store');
    Route::patch('/comments/{id}', [BoardController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{id}', [BoardController::class, 'destroy'])->name('comments.destroy');
    Route::get('/profile', [BoardController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile/{id}', [BoardController::class, 'name_update'])->name('profile.update');
    Route::match(['get', 'post'], '/search', [BoardController::class, 'search'])->name('search');
});

// パスワードリセットのルート
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
