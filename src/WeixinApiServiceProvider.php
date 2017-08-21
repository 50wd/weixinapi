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
		$this->app['WeixinApi'] = $this->app->share(
            function ($app) {
                return new \Wlkj\WeixinApi\WeixinApi();
            }
        );
	}

}
