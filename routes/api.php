<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DomainController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\TemplateController;
use App\Http\Controllers\Api\TemplateImageController;
use App\Http\Controllers\Api\TemplateProjectController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public read-only routes
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);
Route::get('/templates', [TemplateController::class, 'index']);
Route::get('/templates/{template}', [TemplateController::class, 'show']);
Route::get('/templates/{template}/images', [TemplateImageController::class, 'index']);
Route::get('/templates/{template}/projects', [TemplateProjectController::class, 'index']);

// Public domain routes
Route::get('/domains/search', [DomainController::class, 'search']);
Route::get('/domains/pricing', [DomainController::class, 'pricing']);

// Payment webhook (public - for payment gateway callbacks)
Route::post('/payment/callback', [PaymentController::class, 'callback']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Categories (Admin only - add middleware later)
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    // Templates (Admin only - add middleware later)
    Route::post('/templates', [TemplateController::class, 'store']);
    Route::put('/templates/{template}', [TemplateController::class, 'update']);
    Route::delete('/templates/{template}', [TemplateController::class, 'destroy']);

    // Template Images (Admin only)
    Route::post('/template-images', [TemplateImageController::class, 'store']);
    Route::get('/template-images/{templateImage}', [TemplateImageController::class, 'show']);
    Route::post('/template-images/{templateImage}', [TemplateImageController::class, 'update']);
    Route::delete('/template-images/{templateImage}', [TemplateImageController::class, 'destroy']);

    // Template Projects (Admin only)
    Route::post('/template-projects', [TemplateProjectController::class, 'store']);
    Route::get('/template-projects/{templateProject}', [TemplateProjectController::class, 'show']);
    Route::post('/template-projects/{templateProject}', [TemplateProjectController::class, 'update']);
    Route::delete('/template-projects/{templateProject}', [TemplateProjectController::class, 'destroy']);

    // Domain management
    Route::post('/domains/reserve', [DomainController::class, 'reserve']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);

    // Payments
    Route::post('/orders/{order}/payment', [PaymentController::class, 'process']);
});
