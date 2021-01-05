<?php

namespace Ecjia\App\Captcha;

use Ecjia\App\Captcha\Enums\CaptchaEnum;
use Ecjia\Component\CaptchaScreen\CaptchaScreen;
use Ecjia\Component\CaptchaScreen\CaptchaScreenServiceProvider;
use Ecjia\Component\CaptchaScreen\Facades\CaptchaManager;
use ecjia_admin_log;
use RC_Loader;
use RC_Service;
use Royalcms\Component\App\AppParentServiceProvider;

/**
 * Class CaptchaServiceProvider
 * @package Ecjia\App\Captcha
 *
 * examples code:
 * $screens = [
 * new CaptchaScreen('captcha_admin', CaptchaEnum::CAPTCHA_ADMIN, __('后台管理员登录', 'captcha')),
 * new CaptchaScreen('captcha_register', CaptchaEnum::CAPTCHA_REGISTER, __('新用户注册', 'captcha')),
 * new CaptchaScreen('captcha_login', CaptchaEnum::CAPTCHA_LOGIN, __('用户登录', 'captcha')),
 * new CaptchaScreen('captcha_comment', CaptchaEnum::CAPTCHA_COMMENT, __('发表评论', 'captcha')),
 * new CaptchaScreen('captcha_message', CaptchaEnum::CAPTCHA_MESSAGE, __('留言板留言', 'captcha')),
 * ];
 */
class CaptchaServiceProvider extends AppParentServiceProvider
{

    public function boot()
    {
        $this->package('ecjia/app-captcha');

        //加载验证码常量
        RC_Loader::load_app_config('constant', 'captcha', false);

        $this->assignAdminLogContent();

        $this->addCaptchaScreen();
    }

    public function register()
    {
        //注册验证码场景
        $this->royalcms->register(CaptchaScreenServiceProvider::class);

        $this->registerAppService();
    }

    protected function registerAppService()
    {
//         RC_Service::addService('admin_purview', 'captcha', 'Ecjia\App\Captcha\Services\AdminPurviewService');
        RC_Service::addService('setting_menu', 'captcha', 'Ecjia\App\Captcha\Services\SettingMenuService');
        RC_Service::addService('plugin_menu', 'captcha', 'Ecjia\App\Captcha\Services\PluginMenuService');
        RC_Service::addService('plugin_install', 'captcha', 'Ecjia\App\Captcha\Services\PluginInstallService');
        RC_Service::addService('plugin_list', 'captcha', 'Ecjia\App\Captcha\Services\PluginListService');
        RC_Service::addService('plugin_uninstall', 'captcha', 'Ecjia\App\Captcha\Services\PluginUninstallService');
    }

    protected function assignAdminLogContent()
    {
        ecjia_admin_log::instance()->add_object('config', __('配置', 'captcha'));
    }

    protected function addCaptchaScreen()
    {
        CaptchaManager::addScreen(new CaptchaScreen('captcha_admin', CaptchaEnum::CAPTCHA_ADMIN, __('后台管理员登录', 'captcha')));
    }


}