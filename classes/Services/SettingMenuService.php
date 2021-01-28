<?php
namespace Ecjia\App\Captcha\Services;

use ecjia_admin;
use RC_Uri;

/**
 * 后台菜单API
 * @author royalwang
 */
class SettingMenuService
{
    /**
     * @param $options
     * @return
     */
	public function handle($options)
    {

        $menus = ecjia_admin::make_admin_menu('05_captcha_setting', __('验证码设置', 'captcha'), RC_Uri::url('captcha/admin_config/init'), 5)->add_purview('captcha_manage');
    	
    	return $menus;
    	
    }
}

// end