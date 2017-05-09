<?php
namespace CultureKings\ShopifyAuth;

use Illuminate\Support\ServiceProvider;

class ShopifyAuthServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        $this->publishes([
            __DIR__.'/config/shopify-auth.php' => config_path('shopify-auth.php'),
        ]);

        $this->loadMigrationsFrom(__DIR__.'/Migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        /*
	     * Register the service provider for the dependency.
	     */
        // $this->app->register(ShoppingcartServiceProvider::class);
    }

}
