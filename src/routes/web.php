<?php

// Install & Auth
Route::group(['namespace' => 'CultureKings\ShopifyAuth\Http\Controllers'], function () {
    Route::get('shopify-auth/{appName}/install', 'AuthController@installShop');
    Route::get('shopify-auth/{appName}/auth/callback', 'AuthController@processOAuthResultRedirect');
});