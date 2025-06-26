<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TemplateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public template routes (untuk browsing tanpa login)
Route::get('/templates', [TemplateController::class, 'index']);
Route::get('/templates/{template}', [TemplateController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Admin routes (tambahkan middleware admin jika diperlukan)
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
    Route::apiResource('templates', TemplateController::class)->except(['index', 'show']);
});
