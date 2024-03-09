<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/get-sub-categories/{category_id}', [\App\Http\Controllers\Api\CategoryController::class, 'getSubCategories']);

Route::get('/cart/products/{id}', [\App\Http\Controllers\Api\ProductController::class, 'getCartSingleProduct']);

Route::get('/store/{id}/shipping-providers', [\App\Http\Controllers\Api\StoreController::class, 'getStoreShippingPreferences']);

Route::get('/store/{id}/shipping-providers/{spId}', [\App\Http\Controllers\Api\StoreController::class, 'getStoreSingleShippingPreference']);

Route::post('/country/stores', [\App\Http\Controllers\Api\StoreController::class, 'getCountryStores']);

Route::get('/checkIfUserIsBlocked/{productId}', [\App\Http\Controllers\Api\ProductController::class, 'checkIfUserIsBlocked']);


Route::group(['prefix'=>'paypal'], function(){
    Route::post('/order/create',[\App\Http\Controllers\PaypalPaymentController::class,'create']);
    Route::post('/order/capture',[\App\Http\Controllers\PaypalPaymentController::class,'capture']);
});
Route::group(['prefix'=>'stripe'], function(){
    Route::post('/process-payment',[\App\Http\Controllers\StripePaymentController::class,'processPayment']);

});



