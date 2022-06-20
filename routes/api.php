<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/auth/signin', [AuthController::class, 'signIn']);
Route::post('/auth/signup', [AuthController::class, 'signUp']);
Route::get('/auth/verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware(['signed'])->name('verification.verify');

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
	Route::get('/auth/me', [AuthController::class, 'me']);
	Route::post('/auth/signout', [AuthController::class, 'signOut']);
	Route::post('/auth/send-email', [AuthController::class, 'sendEmail'])->middleware(['throttle:6,1'])->name('verification.send');

	Route::get('/profile', [ProfileController::class, 'show']);
	Route::put('/profile', [ProfileController::class, 'update']);

	Route::get('/users', [UserController::class, 'index'])->middleware('role:owner');
	Route::get('/users/{id}', [UserController::class, 'show'])->middleware('role:owner');
	Route::post('/users', [UserController::class, 'store'])->middleware('role:owner');
	Route::put('/users/{id}', [UserController::class, 'update'])->middleware('role:owner');
	Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('role:owner');

	Route::get('/settings', [SettingController::class, 'show']);
	Route::put('/settings', [SettingController::class, 'update']);

	Route::post('/images/upload', [ImageController::class, 'upload']);
});
