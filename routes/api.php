<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserProxyController;

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

// Proxy endpoint: GET user status by NIK (via route param)
Route::get('/user/status/{nik}', [UserProxyController::class, 'getUserStatus']);

// Proxy endpoint: POST check status user by NIK (via JSON body)
Route::post('/v2/user/check/status', [UserProxyController::class, 'checkStatusByNik']);
