<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


Route::prefix('product')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/export-excel', [ProductController::class, 'export']);
        Route::get('/{id}', [ProductController::class, 'edit']);
        Route::post('/', [ProductController::class, 'store']);
        Route::post('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });
});

Route::prefix('cart')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/', [CartController::class, 'getCart']);
        Route::post('/', [CartController::class, 'store']);
    });
});
Route::prefix('order')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/', [OrderController::class, 'getOrderList']);
        Route::post('/', [OrderController::class, 'store']);
    });
});