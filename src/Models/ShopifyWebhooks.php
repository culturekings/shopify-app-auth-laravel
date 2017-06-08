<?php

namespace CultureKings\ShopifyAuth\Models;

use Illuminate\Database\Eloquent\Model;

class ShopifyWebhooks extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'shopify_webhooks';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function shopifyUser()
    {
        return $this->belongsTo(ShopifyUser::class);
    }
}
