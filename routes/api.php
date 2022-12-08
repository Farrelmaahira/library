<?php

use App\Http\Controllers\v1\api\auth\AuthController;
use App\Http\Controllers\v1\api\BookController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function (){
    Route::post('/v1/auth/register', 'register');
    Route::post('/v1/auth/login', 'login');
});
Route::resource('/v1/book', BookController::class); 
Route::post('/v1/logout', [AuthController::class, 'logout']);

 