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
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/profile', [AuthController::class, 'updateProfile']);
});




// Categories
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']); //done
    Route::get('/{category:slug}', [CategoryController::class, 'show']); //done
    Route::post('/', [CategoryController::class, 'store']); //////////////////////////////////this is not needed for costumer
});

// Products
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']); //done
    Route::get('/{product:slug}', [ProductController::class, 'show']); //done
    Route::get('/search/{query}', [ProductController::class, 'search']); //not tested yet
    Route::get('/skus/browse', [ProductController::class, 'browse']); //done all products with skus only, products without sku won't be displayed
});

// Orders
Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']); //done
    Route::get('/{order:order_code}', [OrderController::class, 'show']); //done
});


//skus
// Route::prefix('skus')->group(function () {
//     Route::get('/', [ProductController::class, 'index']);
//     Route::get('/{product:slug}', [ProductController::class, 'show']);
//     Route::get('/search/{query}', [ProductController::class, 'search']); //not tested yet
// });

// Cart
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/add', [CartController::class, 'addItem']);
    Route::put('/update/{sku}', [CartController::class, 'updateQuantity']);
    Route::delete('/remove/{sku}', [CartController::class, 'removeItem']);
    Route::post('/clear', [CartController::class, 'clear']);
});


// // Admin-only API routes (if needed)
// Route::middleware(['auth:sanctum', 'admin'])->group(function () {
//     //
// });