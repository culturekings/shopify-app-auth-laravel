<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebhooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_webhooks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shopify_users_id')->unsigned();
            $table->foreign('shopify_users_id')->references('id')->on('shopify_users');
            $table->string('shop_url')->nullable();
            $table->string('shopify_app')->nullable();
            $table->string('webhook_id')->nullable();
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
        Schema::dropIfExists('shopify_webhooks');
    }
}
