<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\UserController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
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

Route::group(['prefix' => 'v1'], function () {
	Route::post('/signin', [AuthController::class, 'signIn']);
	Route::post('/signup', [AuthController::class, 'signUp']);

	Route::group(['middleware' => ['auth:sanctum', 'status:active']], function () {
		Route::get('/me', [AuthController::class, 'me']);
		Route::post('/signout', [AuthController::class, 'signOut']);

		Route::get('/users', [UserController::class, 'index']);
		Route::get('/users/{id}', [UserController::class, 'show']);
		Route::post('/users', [UserController::class, 'store']);
		Route::put('/users/{id}', [UserController::class, 'update']);
		Route::delete('/users/{id}', [UserController::class, 'destroy']);
	});
});

Route::group(['middleware' => ['auth:sanctum', 'status:active']], function () {
	Route::get('/profile', [ProfileController::class, 'show']);
	Route::put('/profile', [ProfileController::class, 'update']);

	Route::get('/dashboard', [DashboardController::class, 'show']);

	Route::get('/settings', [SettingController::class, 'show']);
	Route::put('/settings', [SettingController::class, 'update']);

	Route::post('/images/upload', [ImageController::class, 'upload']);
});
