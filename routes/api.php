<?php

use App\Http\Controllers\api\AuthController;

use App\Http\Controllers\api\CartItemController;
use App\Http\Controllers\api\ContainerCandleController;
use App\Http\Controllers\api\ImageController;
use App\Http\Controllers\api\MoldedCandleController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\SetController;
use App\Http\Controllers\api\StandController;
use App\Http\Controllers\api\StatuetteController;
use App\Http\Controllers\api\VaseController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::prefix('products')->group(function () {
    Route::get('/new', [ProductController::class, 'new']);
    Route::get('/search/{value}', [ProductController::class, 'search']);
    Route::get('/molded_candles', [MoldedCandleController::class, 'index']);
    Route::get('/container_candles', [ContainerCandleController::class, 'index']);
    Route::get('/sets', [SetController::class, 'index']);
    Route::get('/stands', [StandController::class, 'index']);
    Route::get('/statuettes', [StatuetteController::class, 'index']);
    Route::get('/vases', [VaseController::class, 'index']);
    Route::get('/bestsellers', [ProductController::class, 'bestsellers']);
    Route::get('/{id}', [ProductController::class, 'show']);
});

Route::prefix('cart')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [CartItemController::class, 'index']);
    Route::post('/add', [CartItemController::class, 'store']);
    Route::delete('/remove/{id}', [CartItemController::class, 'destroy']);
    Route::patch('/update', [CartItemController::class, 'update']);
});

Route::prefix('user')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [AuthController::class, 'index']);
    Route::get('/orders', [AuthController::class, 'getOrders']);

});

Route::get('/images/{folder}/{filename}', [ImageController::class, 'getImage']);
