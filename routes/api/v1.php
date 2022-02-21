<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Carts\IndexController;
use App\Http\Controllers\Api\V1\Carts\CreateController;
use App\Http\Controllers\Api\V1\Products\ShowController;
use App\Http\Controllers\Api\V1\Carts\Products\StoreController;
use App\Http\Controllers\Api\V1\Carts\Products\DeleteController;
use App\Http\Controllers\Api\V1\Carts\Products\UpdateController;
use App\Http\Controllers\Api\V1\Products\IndexController as ProductsIndexController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
})->name('auth:me');

/**
 * Product Routes
 */
Route::prefix('products')->as('products:')->group(function () {
    /**
     * Show all products
     */
    Route::get(
        uri:'/',
        action: ProductsIndexController::class
    )->name('index');

    Route::get(
        uri:'/{key}',
        action: ShowController::class
    )->name('show');
});

/**
 * Cart Routes
 */
Route::prefix('carts')->as('carts:')->group(function () {
    /**
     * Get the user's cart
    */
    Route::get('/', IndexController::class)->name('index');

    /**
     * Create a new Cart
    */
    Route::post('/', CreateController::class)->name('store');

    /**
     * Add a product to the cart
    */
    Route::post('{cart:uuid}/products', StoreController::class)->name('products:store');

    /**
     * Update Quantity
    */
    Route::patch('{cart:uuid}/products/{item:uuid}', UpdateController::class)->name('products:update');

    /**
     * Delete Product
    */
    Route::delete('{cart:uuid}/products/{item:uuid}', DeleteController::class)->name('products:delete');
});
