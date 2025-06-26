<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
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

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Categories
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    // Templates
    Route::post('/templates', [TemplateController::class, 'store']);
    Route::put('/templates/{template}', [TemplateController::class, 'update']);
    Route::delete('/templates/{template}', [TemplateController::class, 'destroy']);

    // Template Images
    Route::post('/template-images', [TemplateImageController::class, 'store']);
    Route::get('/template-images/{templateImage}', [TemplateImageController::class, 'show']);
    Route::post('/template-images/{templateImage}', [TemplateImageController::class, 'update']);
    Route::delete('/template-images/{templateImage}', [TemplateImageController::class, 'destroy']);

    // Template Projects
    Route::post('/template-projects', [TemplateProjectController::class, 'store']);
    Route::get('/template-projects/{templateProject}', [TemplateProjectController::class, 'show']);
    Route::post('/template-projects/{templateProject}', [TemplateProjectController::class, 'update']);
    Route::delete('/template-projects/{templateProject}', [TemplateProjectController::class, 'destroy']);
});
