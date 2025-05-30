<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;


Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/users', [UserController::class, 'store']);

// Galería pública
Route::get('/gallery', [GalleryController::class, 'index']);
Route::get('/gallery/{id}', [GalleryController::class, 'show']);
Route::put('/gallery/{id}', [GalleryController::class, 'update']);

// Productos públicos
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/index', [CartItemController::class, 'index']);
// ✅ Rutas protegidas (con cookies y CSRF válidas)
Route::middleware(['auth:sanctum'])->group(function () {
    // Usuario autenticado
    Route::get('/me', function (Request $request) {
         $user = $request->user();

        return $user;
    });
    
    // Pedidos
    Route::get('/orders', [OrderController::class, 'index']);

    // Administración de usuarios
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::get('/admin/users', [UserController::class, 'adminUsers']); 
    // Carrito de compras
    Route::get('/my-cart', [CartItemController::class, 'myCart']);
    Route::post('/cart-items', [CartItemController::class, 'store']);
    Route::put('/cart-items/{id}', [CartItemController::class, 'update']);
    Route::delete('/cart-items/{id}', [CartItemController::class, 'destroy']);

    // Checkout
    Route::post('/checkout', [CartItemController::class, 'checkout']);
     Route::post('/pay', [PaymentController::class, 'pay']); // 👈 ESTA LÍNEA

    Route::post('/stripe/checkout', [PaymentController::class, 'createStripeSession']);

    Route::post('/usersAdmmin', [UserController::class, 'storeAdmin']);


});
