<?php

use App\Http\Controllers\API\{
    AuthController,
    ProductController
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

Route::post("/register", [AuthController::class, 'UserRegister']);
Route::post('/login', [AuthController::class, 'UserLogin'])->name('login');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'Logout']);
    Route::post('/logout-from-all-devices', [AuthController::class, 'LogoutFromAllDevices']);
    Route::post('/add-product', [ProductController::class, 'AddProduct']);
    Route::post('/get-user-product', [ProductController::class, 'GetUserProducts']);
});
Route::get('/get-all-product', [ProductController::class, 'GetAllProducts']);

