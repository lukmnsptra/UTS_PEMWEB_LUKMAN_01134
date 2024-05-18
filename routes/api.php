<?php

use App\Http\Controllers\Api\AuthCTRL;
use App\Http\Controllers\Api\CategoryCTRL;
use App\Http\Controllers\Api\ProductCTRL;
use App\Http\Controllers\Api\UserCTRL;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthCTRL::class, 'register']);
Route::post('/login', [AuthCTRL::class, 'login']);

Route::middleware(['userRoleOnly:admin'])->group(function () {
    Route::prefix('/categories')->group(function () {
        Route::get('', [CategoryCTRL::class, 'getAll']);
        Route::post('', [CategoryCTRL::class, 'create']);

        Route::prefix('/{id}')->where(['id' => '[0-9]+'])->group(function () {
            Route::delete('', [CategoryCTRL::class, 'delete']);
            Route::put('', [CategoryCTRL::class, 'update']);
            Route::get('', [CategoryCTRL::class, 'get']);
        });
    });

    Route::prefix('/products')->group(function () {
        Route::get('', [ProductCTRL::class, 'getAll']);
        Route::post('', [ProductCTRL::class, 'create']);

        Route::prefix('/{id}')->where(['id' => '[0-9]+'])->group(function () {
            Route::delete('', [ProductCTRL::class, 'delete']);
            Route::put('', [ProductCTRL::class, 'update']);
            Route::get('', [ProductCTRL::class, 'get']);
        });
    });
});

Route::middleware(['userRoleOnly:admin,user'])->group(function () {
    Route::get('/users/current', [UserCTRL::class, 'current']);
});
