<?php

use App\Http\Controllers\api\AddressController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CartItemController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\FavoritesItemController;
use App\Http\Controllers\api\ImageController;
use App\Http\Controllers\api\MessageController;
use App\Http\Controllers\api\NotificationController;
use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\PromoCodeController;
use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/new', [ProductController::class, 'new']);
    Route::get('/search/{value}', [ProductController::class, 'search']);
    Route::get('/bestsellers', [ProductController::class, 'bestsellers']);
    Route::get('/{id}', [ProductController::class, 'show']);
});

Route::prefix('categories')->group(function () {
   Route::get('/', [CategoryController::class, 'index']);
   Route::get('/{slug}', [CategoryController::class, 'show']);
   Route::get('/{slug}/filters', [CategoryController::class, 'getFilters']);
});

Route::prefix('cart')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [CartItemController::class, 'index']);
    Route::post('/add', [CartItemController::class, 'store']);
    Route::delete('/remove/{id}', [CartItemController::class, 'destroy']);
    Route::patch('/update', [CartItemController::class, 'update']);
});


Route::prefix('promo-codes')->middleware(['auth:sanctum'])->group(function () {
    Route::post('/apply', [PromoCodeController::class, 'applyPromoCode']);
    Route::delete('/remove', [PromoCodeController::class, 'removePromoCode']);
});


Route::prefix('favorites')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [FavoritesItemController::class, 'index']);
    Route::post('/add', [FavoritesItemController::class, 'store']);
    Route::delete('/remove/{id}', [FavoritesItemController::class, 'destroy']);
});


Route::prefix('user')->middleware([EnsureFrontendRequestsAreStateful::class, 'auth:sanctum'])->group(function () {
    Route::patch('/update', [AuthController::class, 'update']);
    Route::get('/', [AuthController::class, 'index']);
    Route::put('/update/{id}', [AuthController::class, 'update']);

    Route::prefix('/address')->group(function () {
        Route::get('/', [AddressController::class, 'index']);
        Route::put('/', [AddressController::class, 'update']);
    });
});

Route::prefix('orders')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::post('/', [OrderController::class, 'store']);
});

Route::prefix('email')->group(function () {
    Route::put('/', [EmailController::class, 'update']);
    Route::post('/verification-notification', [EmailController::class, 'verificationNotification'])->middleware('auth:sanctum');
    Route::get('/verify/{id}/{hash}', [EmailController::class, 'verifyEmail'])->middleware('signed')->name('verification.verify');;
});

Route::prefix('notifications')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::patch('/{notification}', [NotificationController::class, 'markAsRead']);
});

Route::post('/message', [MessageController::class, 'store']);
Route::get('/images/{folder}/{filename}', [ImageController::class, 'getImage']);


Route::get('/favorites/{product}', [FavoritesItemController::class, 'show']);
