<?php

use App\Http\Controllers\ContainerCandlesController;
use App\Http\Controllers\MoldedCandlesController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StandController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\StatuetteController;
use App\Http\Controllers\VaseController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/products');

Route::prefix('products')->name('products.')->group(function () {
    Route::resource('molded_candles', MoldedCandlesController::class);
    Route::resource('container_candles', ContainerCandlesController::class);
    Route::resource('stands', StandController::class);
    Route::resource('vases', VaseController::class);
    Route::resource('statuettes', StatuetteController::class);
});
Route::resource('sales', SaleController::class);

Route::resource('products', ProductController::class);

Route::resource('statistics', StatisticController::class);

Route::resource('orders', OrderController::class);





