<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopifyAppsRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_apps_users', function (Blueprint $table) {
            $table->integer('shopify_users_id')->unsigned();
            $table->foreign('shopify_users_id')->references('id')->on('shopify_users');

            $table->integer('shopify_app_id')->unsigned();
            $table->foreign('shopify_app_id')->references('id')->on('shopify_apps');

            $table->string('shopify_app_name');

            $table->text('plan_type')->nullable();
            $table->string('scope')->nullable();
            $table->string('charge_id')->nullable();
            $table->string('access_token');

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
        Schema::dropIfExists('shopify_apps_users');
    }
}
