<?php

namespace CultureKings\ShopifyAuth\Models;

use Illuminate\Database\Eloquent\Model;

class ShopifyAppUsers extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'shopify_apps_users';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function shopifyUser()
    {
        return $this->belongsTo(ShopifyUser::class, 'shopify_users_id', 'id');
    }

    public function shopifyApp()
    {
        return $this->belongsTo(ShopifyApp::class, 'shopify_app_id', 'id');
    }
}
