<?php namespace Wlkj\WeixinApi\Facades;

use Illuminate\Support\Facades\Facade;

class WeixinApi extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'WeixinApi';
	}
}
