<?php

namespace Ecjia\App\Captcha;

use ecjia;
use Ecjia\App\Captcha\Tags\CaptchaTag;
use RC_Loader;
use RC_Service;
use Royalcms\Component\App\AppParentServiceProvider;

class CaptchaServiceProvider extends  AppParentServiceProvider
{
    
    public function boot()
    {
        $this->package('ecjia/app-captcha');

        //加载验证码常量
        RC_Loader::load_app_config('constant', 'captcha', false);

        //注册模板插件
        ecjia::register_view_plugin('function', 'captcha', array(CaptchaTag::class, 'ecjia_function_captcha'));
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