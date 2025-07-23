<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiAuthController; // <-- Use ApiAuthController for Sanctum

Route::post('register', [ApiAuthController::class, 'register']);
Route::post('login', [ApiAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [ApiAuthController::class, 'logout']);
    Route::get('me', [ApiAuthController::class, 'me']); // This is your protected route
});

Route::get('/sanctum/csrf-cookie', function (Request $request) {
    return response('')->withCookie(cookie('XSRF-TOKEN', csrf_token()));
});
