<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopifyUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('app_name');
            $table->string('shop_url')->unique();
            $table->text('shop_name')->nullable();
            $table->text('shop_domain')->nullable();
            $table->text('email')->nullable();
            $table->text('plan_type')->nullable();
            $table->string('scope')->nullable();
            $table->string('charge_id')->nullable();
            $table->string('access_token');
            $table->boolean('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shopify_users');
    }
}
