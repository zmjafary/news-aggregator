<?php

use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PreferenceController;

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/personalized', [ArticleController::class, 'personalizedFeed']);
    Route::get('/articles/{id}', [ArticleController::class, 'show']);
    Route::post('/preferences/update', [PreferenceController::class, 'updatePreference']);
    Route::post('/logout', [UserController::class, 'logout']);
});

// Public routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
