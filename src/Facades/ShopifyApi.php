<?php

namespace CultureKings\ShopifyAuth\Facades;

use Illuminate\Support\Facades\Facade;

class ShopifyApi extends Facade
{
    protected static function getFacadeAccessor() { return 'ShopifyApi'; }
}