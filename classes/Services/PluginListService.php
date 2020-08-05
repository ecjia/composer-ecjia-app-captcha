<?php
namespace Ecjia\App\Captcha\Services;

use RC_Loader;

/**
 * 轮播图插件列表API
 * @author royalwang
 */
class PluginListService
{
	
	public function handle(& $options)
    {
		$captcha = RC_Loader::load_app_class('captcha_method', 'captcha');
		
		$list = $captcha->captcha_list();

		return $list;
	}
}

// end