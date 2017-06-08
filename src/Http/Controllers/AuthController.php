<?php
namespace CultureKings\ShopifyAuth\Http\Controllers;

use CultureKings\ShopifyAuth\Services\ShopifyAuthService;
use CultureKings\ShopifyAuth\ShopifyApi;
use CultureKings\ShopifyAuth\Models\ShopifyUser;
use CultureKings\ShopifyAuth\Models\ShopifyAppUsers;
use CultureKings\ShopifyAuth\Models\ShopifyWebhooks;
use Illuminate\Http\Request;

class AuthController
{
    protected $shopify;
    protected $shopifyAuthService;
    protected $shopifyAppConfig;

    public function __construct(ShopifyApi $shopify, ShopifyAuthService $shopifyAuthService)
    {
        $this->shopify = $shopify;
        $this->shopifyAuthService = $shopifyAuthService;
    }

    public function installShop(Request $request, $appName)
    {
        $shopifyAppConfig = config('shopify-auth.'.$appName);
        $shopUrl = $request->get('shop');

        if (!$shopUrl) {
            abort(401, 'No shop url set, cannot authorize.');
        }

        $scope = $shopifyAppConfig['scope'];
        $redirectUrl = url($shopifyAppConfig['redirect_url']);

        $user = ShopifyUser::where('shop_url', $shopUrl)->get()->first();

        // if existing user for this app, send to dashboard
        if ($user !== null && $user->shopifyAppUsers->count()) {
            return redirect()->to($shopifyAppConfig['dashboard_url']);
        }

        $shopify = $this->shopify
            ->setKey($shopifyAppConfig['key'])
            ->setSecret($shopifyAppConfig['secret'])
            ->setShopUrl($shopUrl);

        return redirect()->to($shopify->getAuthorizeUrl($scope, $redirectUrl));
    }

    public function processOAuthResultRedirect(Request $request, $appName)
    {
        $shopifyAppConfig = config('shopify-auth.'.$appName);
        $code = $request->get('code');
        $shopUrl = $request->get('shop');

        // Save into DB
        $createUser = $this->shopifyAuthService->getAccessTokenAndCreateNewUser($code, $shopUrl, $shopifyAppConfig);

        // Create webhook to handle uninstallation
        $this->shopifyAuthService->checkAndAddWebhookForUninstall($shopUrl, $createUser['access_token'], $createUser['user'], $shopifyAppConfig);

        // Build query string
        $queryString = [
            'shop' => $shopUrl,
            'appName' => $appName,
        ];
        $queryString = http_build_query($queryString) . "\n";

        return redirect()->to($shopifyAppConfig['success_url'] . '?' . $queryString)->with('shopUrl', $shopUrl);
    }

    public function getSuccessPage($appName)
    {
        $shopifyAppConfig = config('shopify-auth.'.$appName);

        return view($shopifyAppConfig['view_install_success_path']);
    }

    public function handleAppUninstallation(Request $request, $appName)
    {
        $shopUrl = $request->get('shop');

        \Log::info('handle uninstall webhook');

        $userApps = ShopifyAppUsers::where([
            'shopify_app_name' => $appName,
            'shop_url'         => $shopUrl,
        ])->get();
        foreach ($userApps as $app) {
            $app->delete();
        }

        $hooks = ShopifyWebhooks::where([
            'shopify_app' => $appName,
            'shop_url'    => $shopUrl,
        ])->get();
        foreach ($hooks as $hook) {
            $hook->delete();
        }
    }
}
