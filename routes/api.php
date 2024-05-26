<?php

use App\Http\Controllers\API\{
    AuthController,
    ProductController,
    CartController,
    OrderController
};
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

Route::post("/user/register", [AuthController::class, 'UserRegister']);
Route::post('/user/login', [AuthController::class, 'UserLogin'])->name('login');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/user/logout', [AuthController::class, 'Logout']);
    Route::post('/user/logout-from-all-devices', [AuthController::class, 'LogoutFromAllDevices']);
    Route::post('/user/orders', [OrderController::class, 'GetUserOrders']);
    Route::post('/user/order/details', [OrderController::class, 'GetUserOrderDetails']);
    Route::post('product/add', [ProductController::class, 'AddProduct']);
    Route::post('/user/products', [ProductController::class, 'GetUserProducts']);
    Route::post('/cart/add', [CartController::class, 'AddToCart']);
    Route::delete('/cart/remove', [CartController::class, 'RemoveFromCart']);
    Route::post('/order/create', [OrderController::class, 'CreateOrder']);


});
Route::get('/product/all', [ProductController::class, 'GetAllProducts']);

