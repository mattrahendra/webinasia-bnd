<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TemplateController;
use Illuminate\Support\Facades\Route;

// Home Route
Route::get('/', function () {
    return view('home');
})->name('home');

// Authentication Routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Domain Routes
Route::get('/domains/search', [DomainController::class, 'search'])->name('domains.search');
Route::get('/domains/pricing', [DomainController::class, 'pricing'])->name('domains.pricing');
Route::post('/domains/reserve', [DomainController::class, 'reserve'])->name('domains.reserve')->middleware('role:user,admin');

// Category Routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Template Routes
Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
Route::get('/templates/{template}', [TemplateController::class, 'show'])->name('templates.show');

// Order Routes
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index')->middleware('role:user,admin');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show')->middleware('role:user,admin');
Route::post('/orders/purchase', [OrderController::class, 'purchase'])->name('orders.purchase')->middleware('role:user,admin');

// Payment Routes
Route::post('/payments/{order}/process', [PaymentController::class, 'process'])->name('payments.process')->middleware('role:user,admin');
Route::post('/payments/callback', [PaymentController::class, 'callback'])->name('payments.callback');

// Static Pages
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');
