<?php
namespace CultureKings\ShopifyAuth\Http\Controllers;

use CultureKings\ShopifyAuth\Services\ShopifyAuthService;
use Illuminate\Http\Request;
use Oseintow\Shopify\Shopify;

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

        if (!$shopUrl) {
            abort(401, 'No shop url set, cannot authorize.');
        }

        $scope = $shopifyAppConfig['scope'];
        $redirectUrl = url($shopifyAppConfig['redirect_url']);

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
        $shopifyUser = $this->shopifyAuthService->getAccessTokenAndCreateNewUser($code, $shopUrl, $shopifyAppConfig);

        return redirect()->to($shopifyAppConfig['success_url'] . '?shop=' . $shopUrl)->with('shopUrl', $shopUrl);
    }

    public function getSuccessPage($appName)
    {
        $shopifyAppConfig = config('shopify-auth.'.$appName);

        return view($shopifyAppConfig['name'] . '.install-success');
    }

    public function disableUserOnUninstallWebhookHandle()
    {
        // @todo
    }
}
