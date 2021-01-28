<?php
namespace Ecjia\App\Captcha\Services;

use ecjia_config;
use RC_Plugin;

/**
 * 验证码卸载API
 * @author royalwang
 */
class PluginUninstallService
{
	
	public function handle($options)
    {
	    if (isset($options['file'])) {
	        $plugin_file = $options['file'];
	        $plugin_file = RC_Plugin::plugin_basename( $plugin_file );
	        $plugin_dir = dirname($plugin_file);
	         
	        $plugins = ecjia_config::instance()->get_addon_config('captcha_plugins', true);	        
	        unset($plugins[$plugin_dir]);
	         
	        ecjia_config::instance()->set_addon_config('captcha_plugins', $plugins, true);
	         
	        return true;
	    }
	     
	    return false;
	}
}

// end