<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\UserController;
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

Route::post('/auth/signin', [AuthController::class, 'signin']);
Route::post('/auth/signup', [AuthController::class, 'signup']);

Route::group(['middleware' => ['auth:sanctum']], function () {
	Route::get('/auth/me', [AuthController::class, 'me']);
	Route::post('/auth/signout', [AuthController::class, 'signout']);

	Route::get('/users', [UserController::class, 'index'])->middleware('role:owner');
	Route::get('/users/{id}', [UserController::class, 'show'])->middleware('role:owner');
	Route::post('/users', [UserController::class, 'store'])->middleware('role:owner');
	Route::put('/users/{id}', [UserController::class, 'update'])->middleware('role:owner');
	Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('role:owner');

	Route::post('/images/upload', [ImageController::class, 'upload']);
});
