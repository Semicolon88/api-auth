<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoConttroller;
use Illuminate\Http\router;
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

Route::group(['middleware' => 'api','namespace' => 'App\Http\Controllers'], function($router){
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});
Route::group(['middleware' => 'jwt', 'prefix' => 'auth', 'namespace' => 'App\Http\Controllers'], function($router){
    Route::resource('todos', TodoController::class);
});
