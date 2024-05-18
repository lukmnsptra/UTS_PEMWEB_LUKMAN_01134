<?php

use App\Http\Controllers\AuthCTRL;
use Illuminate\Support\Facades\Route;

Route::get('', function () {
    return response()->view('welcome');
});

Route::prefix('/api/oauth')->group(function () {
    Route::get('/register', [AuthCTRL::class, 'oAuthRedirect']);
    Route::get('/callback', [AuthCTRL::class, 'oAuthCallback']);
});
