<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;


// Public routes
Route::get('/test', function () {
    return response()->json([
        'message' => 'API is working!',
        'timestamp' => now()
    ]);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Categories
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{category:slug}', [CategoryController::class, 'show']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::get('/{category:slug}/products', [CategoryController::class, 'products']);
});

// Products
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{product:slug}', [ProductController::class, 'show']);
    Route::get('/search/{query}', [ProductController::class, 'search']);
    Route::get('/skus/browse', [ProductController::class, 'browse']);
});

// ==================== CART ROUTES ====================

// Public cart routes (for guests)
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/add', [CartController::class, 'addItem']);
    Route::put('/update/{sku}', [CartController::class, 'updateQuantity']);
    Route::delete('/remove/{sku}', [CartController::class, 'removeItem']);
    Route::post('/clear', [CartController::class, 'clear']);
});

// ==================== AUTHENTICATED ROUTES ====================

Route::middleware('auth:sanctum')->group(function () {
    // User profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/profile/password', [AuthController::class, 'changePassword']);

    // Orders (authenticated only)
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/user-orders', [OrderController::class, 'userOrders']);
        Route::get('/{order:order_code}', [OrderController::class, 'show']);
        Route::post('/', [OrderController::class, 'store']);
    });
});
Route::post('/orders', [OrderController::class, 'store']);