<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Front\CategoryController;
use App\Http\Controllers\Api\Front\OrderController;
use App\Http\Controllers\Api\Front\ProductController;
use App\Http\Controllers\Api\Front\SectionController;
use App\Http\Controllers\Api\Front\HomeController;
use App\Http\Controllers\Api\front\TypeController;
use App\Http\Controllers\Api\PaymentMethodController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Front\UserController;
use App\Http\Controllers\Api\Front\VendorController;

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

Route::post('/search', [HomeController::class, 'search_site'])->name('search');
Route::post('/filter', [HomeController::class, 'filter'])->name('filter');

Route::middleware('auth:sanctum')->group( function () {
    Route::get('/logout', [AuthController::class, 'logout']);

    ########products########
    Route::group(['prefix'=>'product'], function(){
        Route::get('/index', [ProductController::class, 'index']);
        Route::post('/create', [ProductController::class, 'create']);
        Route::get('/edit', [ProductController::class, 'edit']);
        Route::post('/update', [ProductController::class, 'update']);
        Route::post('/delete', [ProductController::class, 'delete']);

    });
    ########End products########

    ########Sections########
    Route::group(['prefix'=>'section'], function(){
        Route::get('/', [SectionController::class, 'index'])->name('front.section.index');
        Route::post('/create', [SectionController::class, 'create'])->name('front.section.create');
        Route::post('/show', [SectionController::class, 'show'])->name('front.section.show');
        Route::post('/update', [SectionController::class, 'update'])->name('front.section.update');
        Route::post('/destroy', [SectionController::class, 'destroy'])->name('front.section.destroy');
    });
    ########End Sections########

    ########Categories########
    Route::group(['prefix'=>'category'], function(){
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/create', [CategoryController::class, 'create']);
        Route::post('/show', [CategoryController::class, 'show']);
        Route::post('/update', [CategoryController::class, 'update']);
        Route::post('/destroy', [CategoryController::class, 'destroy']);
        Route::get('/vendor-section', [CategoryController::class, 'listCategoriesOfVendorSection']);
    });

    ########Types########
    Route::group(['prefix'=>'type'], function(){
        Route::get('/index', [TypeController::class, 'index']);
        Route::post('/create', [TypeController::class, 'create']);
        Route::post('/edit', [TypeController::class, 'edit']);
        Route::post('/update', [TypeController::class, 'update']);
        Route::post('/delete', [TypeController::class, 'delete']);

    });
        ########End Types########
########Start User########
        Route::group(['prefix' => 'user'], function () {
              Route::get('/', [UserController::class, 'index']);
            Route::post('/create', [UserController::class, 'create']);
            Route::post('/update', [UserController::class, 'update']);
            Route::post('/show', [userController::class, 'show']);
           Route::post('/delete', [UserController::class, 'delete']);
    });
    ########End User########
    ########Start Vendor########
         Route::group(['prefix' => 'vendor'], function () {
            Route::get('/', [VendorController::class, 'index']);
            Route::post('/create', [VendorController::class, 'create']);
            Route::post('/show', [VendorController::class, 'show']);
            Route::post('/update', [VendorController::class, 'update']);
           Route::post('/delete', [VendorController::class, 'delete']);
           Route::post('/blocked', [VendorController::class, 'isblocked']);
           Route::post('/unblocked', [VendorController::class, 'isnotblocked']);
    });
    ########End Vendor########

    ########Start Order########
    Route::group(['prefix' => 'checkout'], function () {
        Route::get('/get-address', [OrderController::class, 'getUserAddress']);
        Route::post('/create', [OrderController::class, 'create']);
        Route::post('/show-order', [OrderController::class, 'show']);
        Route::post('/update-status', [OrderController::class, 'updateStatus']);

    });
    ########End Order########

    ########Start Vendor########
    Route::group(['prefix' => 'payment-method'], function () {
        Route::get('/', [PaymentMethodController::class, 'index']);
        Route::post('/create', [PaymentMethodController::class, 'create']);
        Route::post('/update', [PaymentMethodController::class, 'update']);
        Route::post('/delete', [PaymentMethodController::class, 'delete']);
    });
    ########End Vendor########

    });
Route::group(['prefix' => 'checkout'], function () {
Route::post('/get-cart-data', [OrderController::class, 'getCartData']);
Route::post('/add-to-cart', [OrderController::class, 'AddToCart']);
Route::post('/remove-from-cart', [OrderController::class, 'removeFromCart']);
});
