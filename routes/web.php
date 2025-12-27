<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SkuController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImgController;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Route::get('/dashboard', [DashboardController::class, 'index'])
//     ->middleware(['auth', 'verified', 'admin'])
//     ->name('dashboard');


// Route::get('/dashboard', function () {
//     // Check if user is authenticated and is an admin
//     if (!Auth::check() || Auth::user()->role !== 'admin') {
//         return redirect('/');
//     }
//     return view('dashboard');
// })->middleware(['admin'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::controller(OrderController::class)
        ->prefix('orders/{order}')
        ->group(function () {
            Route::patch('address', 'updateAddress')->name('orders.updateAddress');
            Route::patch('status', 'updateStatus')->name('orders.updateStatus');
            Route::patch('updateuser', 'updateUser')->name('orders.updateUser');
            Route::get('addProducts', 'addProducts')->name('orders.addProducts');
            Route::post('addProduct/{sku}', 'addProduct')->name('orders.addProduct');
            Route::post('decreaseQuantity/{sku}', 'decreaseQuantity')->name('orders.decreaseQuantity');
            Route::post('removeProduct/{sku}', 'removeProduct')->name('orders.removeProduct');
            Route::post('clearSelection', 'clearSelection')->name('orders.clearSelection');
            Route::post('finalize', 'finalizeOrder')->name('orders.finalize');
            Route::post('upload-receipt', 'uploadReceipt')->name('orders.uploadReceipt');
            Route::delete('delete-receipt/{img}', 'deleteReceipt')->name('orders.deleteReceipt');
        });
    Route::controller(ImgController::class)
        ->prefix('img')
        ->group(function () {
            Route::post('/', 'store')->name('img.store');
            Route::put('/{img}', 'update')->name('img.update');
            Route::delete('/{img}', 'destroy')->name('img.destroy');
            Route::post('/{img}/set-main', 'setAsMain')->name('img.set-main');
        });
    //aditional routes
    Route::put('/products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::get('/skus/create/{product:slug}', [SkuController::class, 'create'])->name('sku.create');
    Route::post('/skus/store/{product:slug}', [SkuController::class, 'store'])->name('skus.store');
    Route::get('/variants/browse', [OrderController::class, 'addProducts'])->name('variants.browse');


    //the remaining of resources routes
    Route::resource('/categories', CategoryController::class);
    Route::resource('/products', ProductController::class);
    Route::resource('/skus', SkuController::class)->except('create', 'store');
    Route::resource('/orders', OrderController::class);
    Route::resource('/orderItem', OrderItemController::class);
});
