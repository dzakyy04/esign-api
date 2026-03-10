<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserProxyController;
use App\Http\Controllers\V2SignProxyController;
use App\Http\Controllers\GenericProxyController;

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

// // Check status user
// Route::get('/user/status/{nik}', [UserProxyController::class, 'checkStatusUser']);
// // Get profile
// Route::get('/user/profile/{nik}', [UserProxyController::class, 'getProfile']);
// // Get expired certificate
// Route::get('/entity/cert/expired', [UserProxyController::class, 'getExpiredCertificate']);
// // Get certificate chain
// Route::get('/user/certificate/chain/{id}', [UserProxyController::class, 'getCertificateChain']);

// // Sign PDF (Email + Passphrase  /  NIK + Passphrase)
// Route::post('/v2/sign/pdf', [V2SignProxyController::class, 'signPdf']);

// -----------------------------------------------------------------------
// Generic wildcard proxy — forward semua request ke external API
// HARUS diletakkan PALING BAWAH agar tidak menimpa route di atas
// -----------------------------------------------------------------------
Route::any('/{path}', [GenericProxyController::class, 'proxy'])
    ->where('path', '.*');
