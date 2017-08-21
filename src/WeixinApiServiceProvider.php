<?php namespace Wlkj\WeixinApi;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class WeixinApiServiceProvider extends ServiceProvider {

	/**
	 * Boot the service provider.
	 *
	 * @return void
	 */
	public function boot()
	{
		
		
	}

	
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
//		$this->app['weixinapi'] = $this->app->share(
//            function ($app) {
//                return new \Wlkj\WeixinApi\WeixinApi();
//            }
//        );
        
        $this->app['WeixinApi'] = new \Wlkj\WeixinApi\WeixinApi();
	}

}
