<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MarketplaceController;
use Illuminate\Support\Facades\Route;

Route::get('/categories', [MarketplaceController::class, 'categories']);
Route::get('/shops', [MarketplaceController::class, 'shops']);
Route::get('/stats', [MarketplaceController::class, 'stats']);
Route::get('/products', [MarketplaceController::class, 'products']);
Route::get('/products/{product}', [MarketplaceController::class, 'showProduct']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/orders', [MarketplaceController::class, 'storeOrder']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/orders', [MarketplaceController::class, 'orders']);
    Route::patch('/orders/{order}/status', [MarketplaceController::class, 'updateOrderStatus']);
    Route::post('/products', [MarketplaceController::class, 'storeProduct']);
    Route::patch('/products/{product}', [MarketplaceController::class, 'updateProductNumbers']);
    Route::patch('/products/{product}/visibility', [MarketplaceController::class, 'updateVisibility']);
    Route::delete('/products/{product}', [MarketplaceController::class, 'destroyProduct']);
});
