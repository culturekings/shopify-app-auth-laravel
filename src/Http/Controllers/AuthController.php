<?php
namespace CultureKings\ShopifyAuth\Http\Controllers;

use CultureKings\ShopifyAuth\Services\ShopifyAuthService;
use Illuminate\Http\Request;

class AuthController
{
    protected $shopify;
    protected $shopifyAuthService;
    protected $shopifyAppConfig;

    public function __construct(Shopify $shopify, ShopifyAuthService $shopifyAuthService)
    {
        $this->shopify = $shopify;
        $this->shopifyAuthService = $shopifyAuthService;
    }

    public function installShop(Request $request, $appName)
    {
        $shopifyAppConfig = config('shopify-auth.'.$appName);
        $shopUrl = $request->get('shop');

        $scope = [
            "write_products",
            "write_script_tags"
        ];

        $redirectUrl = url('/launch-countdown/auth/shopify/callback');

        $shopify = $this->shopify
            ->setKey($this->shopifyAppConfig['key'])
            ->setSecret($this->shopifyAppConfig['secret'])
            ->setShopUrl($shopUrl);

        return redirect()->to($shopify->getAuthorizeUrl($scope, $redirectUrl));
    }

    public function processOAuthResultRedirect(Request $request)
    {
        $code = $request->get('code');
        $shopUrl = $request->get('shop');

        // Save into DB
        $shopifyUser = $this->shopifyAuthService->getAccessTokenAndCreateNewUser($code, $shopUrl, $this->shopifyAppConfig);

        // create shopify script tag for shop and store
        $this->createLaunchCountdownScriptTag($shopUrl, $shopifyUser->access_token, $shopifyUser);

        return redirect()->to('/launch-countdown/install/success?shop=' . $shopUrl)->with('shopUrl', $shopUrl);
    }

    public function disableUserOnUninstallWebhookHandle()
    {
        // @todo
    }
}
