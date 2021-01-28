<?php
namespace Ecjia\App\Captcha\Services;

use Ecjia\App\Captcha\CaptchaPlugin;

/**
 * 轮播图插件列表API
 * @author royalwang
 */
class PluginListService
{
	
	public function handle($options)
    {
		$list = (new CaptchaPlugin())->availablePluginList();

		return $list;
	}
}

// end