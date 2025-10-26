<?php

use App\Http\Controllers\AromaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromoCodeController;
use App\Http\Controllers\RawController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SetController;
use App\Http\Controllers\StatisticController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['guest']], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
});

Route::group(['middleware' => ['role:admin']], function () {
    Route::redirect('/', 'categories/');

    Route::post('/products/{id}/update-stock', [ProductController::class, 'updateStock']);
    Route::patch('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('/search', [ProductController::class, 'search'])->name('products.search');
    Route::prefix('products')->name('products.')->group(function () {
        Route::resource('sets', SetController::class);
    });
    Route::get('categories/create/{id?}', [CategoryController::class, 'create'])
        ->name('categories.create.custom');
    Route::get('/categories/{category}/attributes', [CategoryController::class, 'getAttributes'])
        ->name('categories.attributes');
    Route::resource('categories', CategoryController::class);

    Route::resource('products', ProductController::class);

    Route::put('/photos/set-primary', [PhotoController::class, 'setPrimary']);
    Route::delete('/photos/{id}', [PhotoController::class, 'destroy']);


    Route::resource('sales', SaleController::class);

    Route::resource('statistics', StatisticController::class);

    Route::resource('orders', OrderController::class);

    Route::resource('raws', RawController::class);

    Route::resource('aromas', AromaController::class);

    Route::resource('promo-codes', PromoCodeController::class);

    Route::resource('messages', MessageController::class);
});






