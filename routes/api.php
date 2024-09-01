<?php

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

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

Route::group(['middleware' => ["SetLang"]], function () {

    Route::group(['middleware' => ["auth:sanctum"]], function () {

        Route::group(['middleware' => ["IsApiAdmin"]], function () {
            //Categories
            Route::resource('/categories',CategoryController::class)->only(['store', 'update', 'destroy']);
            //Products
            Route::resource('/products',ProductController::class)->only(['store', 'update', 'destroy']);
        });
         //Categories
        Route::resource('/categories',CategoryController::class)->only(['index', 'show']);
         //Products
        Route::resource('/products',ProductController::class)->only(['index', 'show']);
        //Filter Product
        Route::get('/products/filter/{id}',[ProductController::class,'filter']);

        //Logout
        Route::post('/logout', [AuthController::class, 'logout']);

    });

     //Registeration
    Route::post('/register',[AuthController::class,'Register']);
     //Login
    Route::post('/login',[AuthController::class,'Login']);
});
