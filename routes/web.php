<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContainerCandlesController;
use App\Http\Controllers\MoldedCandlesController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RawController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SetController;
use App\Http\Controllers\StandController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\StatuetteController;
use App\Http\Controllers\VaseController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['guest']], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
});

Route::group(['middleware' => ['auth']], function () {
    Route::redirect('/', 'products/molded_candles');
    Route::post('/products/{id}/update-stock', [ProductController::class, 'updateStock']);
    Route::patch('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('/search', [ProductController::class, 'search'])->name('products.search');
    Route::prefix('products')->name('products.')->group(function () {
        Route::resource('molded_candles', MoldedCandlesController::class);
        Route::resource('container_candles', ContainerCandlesController::class);
        Route::resource('stands', StandController::class);
        Route::resource('vases', VaseController::class);
        Route::resource('statuettes', StatuetteController::class);
        Route::resource('sets', SetController::class);
    });
    Route::resource('sales', SaleController::class);

    Route::resource('products', ProductController::class);

    Route::resource('statistics', StatisticController::class);

    Route::resource('orders', OrderController::class);

    Route::resource('raws', RawController::class);

});





