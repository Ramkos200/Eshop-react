<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SkuController;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('dashboard');


// Route::get('/dashboard', function () {
//     // Check if user is authenticated and is an admin
//     if (!Auth::check() || Auth::user()->role !== 'admin') {
//         return redirect('/');
//     }
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/dashboard/clear-cache', [DashboardController::class, 'clearCache'])
    ->middleware(['auth', 'admin'])
    ->name('dashboard.clear-cache');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
Route::middleware('auth')->group(function () {

    //aditional routes
    Route::put('/products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::patch('/orders/{order}/address', [OrderController::class, 'updateAddress'])->name('orders.updateAddress');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::patch('/orders/{order}/updateuser', [OrderController::class, 'updateUser'])->name('orders.updateUser');
    Route::get('/orders/{order?}/addProducts', [OrderController::class, 'addProducts'])->name('orders.addProducts');
    Route::post('/orders/{order}/addProduct/{sku}', [OrderController::class, 'addProduct'])->name('orders.addProduct');
    Route::post('/orders/{order}/decreaseQuantity/{sku}', [OrderController::class, 'decreaseQuantity'])->name('orders.decreaseQuantity');
    Route::post('/orders/{order}/removeProduct/{sku}', [OrderController::class, 'removeProduct'])->name('orders.removeProduct');
    Route::post('/orders/{order}/clearSelection', [OrderController::class, 'clearSelection'])->name('orders.clearSelection');
    Route::post('/orders/{order}/finalize', [OrderController::class, 'finalizeOrder'])->name('orders.finalize');
    Route::get('/skus/create/{product:slug}', [SkuController::class, 'create'])->name('sku.create');
    Route::post('/skus/store/{product:slug}', [SkuController::class, 'store'])->name('skus.store');
    Route::get('/products/browse', [OrderController::class, 'addProducts'])->name('products.browse');

    //the remaining of resources routes
    Route::resource('/categories', CategoryController::class);
    Route::resource('/products', ProductController::class);
    Route::resource('/skus', SkuController::class)->except('create', 'store');
    Route::resource('/orders', OrderController::class);
    Route::resource('/orderItem', OrderItemController::class);
});