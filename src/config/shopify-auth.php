<?php

return [
    'appname' => [
        'name' => 'app_name', // checked in db, so shouldn't change after launch
        'price' => 0.00,
        'redirect_url' => '/shopify-auth/app_name/auth/callback', // relative uri
        'success_url' => '/shopify-auth/app_name/install/success',
        'scope' => [
            "write_products",
            "write_script_tags"
        ],
        'key' => env("SHOPIFY_APPNAME_APIKEY"),
        'secret' => env("SHOPIFY_APPNAME_SECRET"),
    ],
];