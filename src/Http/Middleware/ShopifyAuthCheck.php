<?php

namespace CultureKings\ShopifyAuth\Http\Middleware;

use CultureKings\ShopifyAuth\Models\ShopifyUser;
use Closure;
use CultureKings\ShopifyAuth\ShopifyAuth;

class ShopifyAuthCheck
{
    protected $shopify;

    public function __construct(ShopifyAuth $shopify)
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

        // If no session, get user & set one
        if (!$request->session()->has('shopifyapp')) {
            $shopifyUser = ShopifyUser::where('shop_url', $request->get('shop'))->first();

            if (!$shopifyUser) {
                // if launch countdown app and not install page, redirect to install
                if ($request->segment(1) === 'launch-countdown' && $request->segment(2) !== 'install') {
                    return redirect('/launch-countdown/install?' . $request->getQueryString());
                }

                return abort(401, 'No shopify user found and no active sessions');
            }

            $request->session()->put('shopifyapp', [
                'shop_url' => $shopifyUser->shop_url,
                'access_token' => $shopifyUser->access_token,
                'app_name' => $shopifyUser->app_name,
            ]);
        }

        dd($request);

        if (!config('app.debug') && !$this->shopify->verifyRequest($request->getQueryString())) {
            return response('Verification Failed. Unauthorised.', 401);
        }

        return $next($request);
    }
}
