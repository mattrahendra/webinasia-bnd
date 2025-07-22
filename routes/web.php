<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TemplateController;
use App\Models\Order;
use Illuminate\Support\Facades\Route;

// Home Route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Domain Routes
Route::prefix('domains')->name('domains.')->group(function () {
    // Halaman awal domain (public)
    Route::get('/', [DomainController::class, 'index'])->name('index');

    // Search domain (public - bisa diakses siapa saja)
    Route::get('/search', [DomainController::class, 'search'])->name('search');
    Route::post('/search', [DomainController::class, 'search']); // For AJAX

    // Domain pricing (public)
    Route::get('/pricing', [DomainController::class, 'pricing'])->name('pricing');

    // Domain suggestions (public)
    Route::get('/suggestions', [DomainController::class, 'suggestions'])->name('suggestions');

    // Protected routes - require authentication
    Route::middleware(['auth'])->group(function () {
        // Reserve domain (redirect to order)
        Route::post('/reserve', [DomainController::class, 'reserve'])->name('reserve');

        // Domain management for authenticated users
        Route::get('/my-domains', [DomainController::class, 'myDomains'])->name('my-domains');
        Route::get('/wishlist', [DomainController::class, 'wishlist'])->name('wishlist');
        Route::post('/wishlist/add', [DomainController::class, 'addToWishlist'])->name('wishlist.add');
        Route::delete('/wishlist/remove/{domain}', [DomainController::class, 'removeFromWishlist'])->name('wishlist.remove');

        // Domain notifications
        Route::post('/notify-when-available', [DomainController::class, 'notifyWhenAvailable'])->name('notify-when-available');
    });
});

// Category Routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Template Routes
Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
Route::get('/templates/{template}', [TemplateController::class, 'show'])->name('templates.show');
Route::get('/templates/{template}/preview', [TemplateController::class, 'preview'])->name('templates.preview');
Route::post('/templates/{template}/select', [TemplateController::class, 'selectTemplate'])->name('templates.select');
// Route untuk debugging (hapus setelah selesai testing)
    Route::get('/{template}/debug-preview', [TemplateController::class, 'debugPreview'])->name('debug-preview');

// Order Routes
Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
Route::post('/orders/select-domain', [OrderController::class, 'selectDomain'])->name('orders.selectDomain');
Route::post('/orders/select-template', [OrderController::class, 'selectTemplate'])->name('orders.selectTemplate');
Route::post('/orders/create-order', [OrderController::class, 'createOrder'])->name('orders.createOrder');
Route::get('/payment/process/{order}', [PaymentController::class, 'process'])->name('payment.process');
// Route untuk menyimpan order dan memproses pembayaran
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::post('/orders/index', [OrderController::class, 'showPayment'])->name('orders.index');

Route::get('/orders/{order}/payment', [OrderController::class, 'showPayment'])->name('orders.payment');
Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/error', [PaymentController::class, 'error'])->name('payment.error');
Route::get('/payment/pending', [PaymentController::class, 'pending'])->name('payment.pending');

// Payment Routes
Route::post('/payments/{order}/process', [PaymentController::class, 'process'])->name('payments.process')->middleware('role:user,admin');
Route::post('/payments/callback', [PaymentController::class, 'callback'])->name('payments.callback');

// Payment callback routes
Route::get('/payment/success', function () {
    return view('payment.success');
})->name('payment.success');

Route::get('/payment/error', function () {
    return view('payment.error');
})->name('payment.error');

Route::get('/payment/pending', function () {
    return view('payment.pending');
})->name('payment.pending');

// API routes for AJAX calls
Route::prefix('api/orders')->name('api.orders.')->group(function () {
    Route::get('/{order}/payment-status', [OrderController::class, 'checkPaymentStatus'])->name('payment-status');
});

Route::post('/orders/{order}/manual-payment', [OrderController::class, 'manualPayment'])->name('orders.manual-payment');

// Static Pages
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');
