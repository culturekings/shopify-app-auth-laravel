<?php

namespace CultureKings\ShopifyAuth\Models;

use Illuminate\Database\Eloquent\Model;

class ShopifyApp extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'shopify_apps';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function shopifyApp()
    {
        return $this->hasMany(ShopifyAppUsers::class, 'id', 'shopify_users_id');
    }
}
