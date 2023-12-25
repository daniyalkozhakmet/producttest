<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('api')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/cart/add/{productId}', [CartController::class, 'add_to_cart']);
    Route::get('/cart', [CartController::class, 'get_my_cart']);
});


Route::get('/products', [ProductController::class, 'get_all_products']);
Route::get('/products/{id}', [ProductController::class, 'get_product_by_id']);
Route::get('/products/search/{searchString}', [ProductController::class, 'filter_products']);
Route::get('/products/categories/{id}', [CategoryController::class, 'get_products_by_category']);
