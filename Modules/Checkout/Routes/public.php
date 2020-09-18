<?php

use Illuminate\Support\Facades\Route;

Route::get('checkout', 'CheckoutController@create')->name('checkout.create');
Route::post('checkout', 'CheckoutController@store')->name('checkout.store');

Route::get('checkout/{orderId}/complete', 'CheckoutCompleteController@store')->name('checkout.complete.store');
Route::get('checkout/complete', 'CheckoutCompleteController@show')->name('checkout.complete.show');

Route::get('checkout/{orderId}/payment-canceled', 'PaymentCanceledController@store')->name('checkout.payment_canceled.store');

// ONGKIR
Route::get('/ongkir', 'OngkirController@getCost')->name('ongkir.show');
Route::get('/ongkir/get/province', 'OngkirController@getProvince')->name('ongkir.show.province');
Route::get('/ongkir/get/city', 'OngkirController@getCity')->name('ongkir.show.city');
