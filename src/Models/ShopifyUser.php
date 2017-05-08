<?php

namespace CultureKings\ShopifyAuth\Models;

use Illuminate\Database\Eloquent\Model;

class ShopifyUser extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'shopify_users';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function scriptTags()
    {
        return $this->hasMany(ShopifyScriptTag::class, 'shopify_users_id', 'id');
    }
}
