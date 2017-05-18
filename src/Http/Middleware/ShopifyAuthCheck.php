<?php

namespace CultureKings\ShopifyAuth\Http\Middleware;

use CultureKings\ShopifyAuth\Models\ShopifyUser;
use Closure;
use CultureKings\ShopifyAuth\ShopifyApi;

class ShopifyAuthCheck
{
    protected $shopify;

    public function __construct(ShopifyApi $shopify)
    {
        $this->shopify = $shopify;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // If no shop key or session, return 401
        if (!$request->has('shop') && !$request->session()->has('shopifyapp')) {
            return abort(401, 'Shop key missing and no active session!');
        }

        $reSetSession = false;

        // recheck session if set
        if (null !== ($request->get('shop')) && $request->session()->has('shopifyapp')) {
            $shopifyUser = ShopifyUser::where('shop_url', $request->get('shop'))->first();

            if ($shopifyUser->access_token !== $request->session()->get('shopifyapp')['access_token']) {
                $reSetSession = true;
            }
        }

        // If no session, get user & set one
        if (!$request->session()->has('shopifyapp') || $reSetSession) {
            $shopifyUser = ShopifyUser::where('shop_url', $request->get('shop'))->first();

            if (!$shopifyUser) {
                return abort(403, 'No shopify user found and no active sessions');
            }

            $request->session()->put('shopifyapp', [
                'shop_url' => $shopifyUser->shop_url,
                'access_token' => $shopifyUser->access_token,
                'app_name' => $shopifyUser->app_name,
            ]);

            \Log::info('hmac', [
               'hmac' => $request->query('hmac'),
                'verify' => $this->shopify->verifyRequest($request->query->all(), $request->getQueryString()),
                'query-all' => $request->query->all(),
                'query-str' => $request->getQueryString(),
            ]);

            // set secret for hmac check
            $appConfig = config('shopify-auth.' . $shopifyUser->app_name);
            $this->shopify->setSecret($appConfig['secret']);

            // check hmac
            if (null !== $request->query('hmac') && !$this->shopify->verifyRequest($request->query->all(), $request->getQueryString())) {
                return response('Verification of HMAC Failed. Unauthorised.', 401);
            }
        }

        return $next($request);
    }
}
