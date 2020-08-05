<?php

namespace Ecjia\App\Captcha;

use RC_Service;
use Royalcms\Component\App\AppParentServiceProvider;

class CaptchaServiceProvider extends  AppParentServiceProvider
{
    
    public function boot()
    {
        $this->package('ecjia/app-captcha');
    }
    
    public function register()
    {
        $this->registerAppService();
    }

    protected function registerAppService()
    {
        RC_Service::addService('admin_purview', 'captcha', 'Ecjia\App\Captcha\Services\AdminPurviewService');
        RC_Service::addService('setting_menu', 'captcha', 'Ecjia\App\Captcha\Services\SettingMenuService');
        RC_Service::addService('plugin_install', 'captcha', 'Ecjia\App\Captcha\Services\PluginInstallService');
        RC_Service::addService('plugin_list', 'captcha', 'Ecjia\App\Captcha\Services\PluginListService');
        RC_Service::addService('plugin_uninstall', 'captcha', 'Ecjia\App\Captcha\Services\PluginUninstallService');
    }
    
}