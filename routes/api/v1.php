<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Products\IndexController;

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
        action: IndexController::class
    )->name('show');
});
