# shopify-app-auth-laravel
Laravel Auth Boilerplate for Shopify App

## Installation
### Install
`composer require culturekings/shopify-app-auth-laravel`

### Add to Providers
Add to Providers in config/app.php
`CultureKings\ShopifyAuth\ShopifyAuthServiceProvider::class`

### Publish
`php artisan vendor:publish`

### Configure App in config
Once published, set up your app.

You can see below that everything is setup under "appname" and then app_name is used from then on.

In the routes, you see that appName is passed as a variable in the auth url, so this is very important that url is the same as the array key of "appname". 

You can change this to be whatever you like so you can run multiple apps through a single auth flow.
```php
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
```

## Usage
All shopify calls should be made through a service and make a call similar to below:
```php
$this->shopify
    ->setKey($shopifyAppConfig['key'])
    ->setSecret($shopifyAppConfig['secret'])
    ->setShopUrl($shopUrl)
    ->setAccessToken($accessToken)
    ->post('admin/script_tags.json', $scriptTags);
```