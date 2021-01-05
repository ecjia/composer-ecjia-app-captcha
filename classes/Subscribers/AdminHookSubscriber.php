<?php


namespace Ecjia\App\Captcha\Subscribers;


use ecjia;
use Ecjia\App\Captcha\BrowserEvent\CaptchaPopoverEvent;
use Ecjia\App\Captcha\CaptchaPlugin;
use Ecjia\App\Captcha\CaptchaScreenValidator;
use Ecjia\App\Captcha\Enums\CaptchaEnum;
use Ecjia\App\Captcha\Enums\CaptchaNameEnum;
use Ecjia\App\Installer\BrowserEvent\InstallCheckDatabaseAccountEvent;
use Ecjia\App\Installer\BrowserEvent\InstallCheckDatabaseExistsEvent;
use Ecjia\App\Installer\BrowserEvent\InstallCheckUserPasswordEvent;
use Ecjia\App\Installer\BrowserEvent\InstallStartEvent;
use Ecjia\Component\BrowserEvent\PageEventManager;
use ecjia_admin;
use RC_Hook;
use RC_Uri;
use Royalcms\Component\Hook\Dispatcher;

class AdminHookSubscriber
{

    static public function admin_login_captcha()
    {
        if ((new CaptchaScreenValidator())->validate(CaptchaNameEnum::CAPTCHA_ADMIN)) {
            $captcha = new CaptchaPlugin();
            if ($captcha->check_activation_captcha()) {
                $captcha_url = $captcha->current_captcha_url(true);

                $click_for_another = __('看不清？点击更换另一个验证码。', 'captcha');
                $label_captcha     = __('验证码', 'captcha');

                //加载页面JS
                ecjia_admin::$controller->getPageEvent()->addPageHandler(CaptchaPopoverEvent::class);

                echo <<<EOF
		<div class="captcha-popover" style="z-index: -1; position: absolute;">
		    <a class='close'>×</a>
		    <img src='$captcha_url' title='$click_for_another' alt='$click_for_another' />
        </div>
		<div class="formRow">
			<div class="input-prepend">
				<span class="add-on"><i class="icon-picture"></i></span>
				<input type="text" maxlength="4" name="captcha" placeholder="$label_captcha" value="" data-content="<div class='showimg'></div>" data-placement="top" />
			</div>
		</div>
EOF;
            }
        }
    }


    static public function admin_login_validate($args)
    {
        if ((new CaptchaScreenValidator())->validate(CaptchaNameEnum::CAPTCHA_ADMIN)) {
            $captcha = new CaptchaPlugin();
            if ($captcha->check_activation_captcha() && !empty($_SESSION['captcha_word'])) {
                /* 检查验证码是否正确 */
                $validator = $captcha->defaultChannel();
                if (isset($args['captcha']) && !$validator->verify_word($args['captcha'])) {
                    return __('您输入的验证码不正确。', 'captcha');
                }
            }
        }
    }

    static public function set_admin_captcha_access($route)
    {
        $route[] = 'captcha/admin_captcha/init';
        $route[] = 'captcha/admin_captcha/check_validate';
        return $route;
    }

    public static function append_admin_setting_group($menus)
    {
        $menus[] = ecjia_admin::make_admin_menu('nav-header', '验证码', '', 130)->add_purview(array('captcha_manage'));
        $menus[] = ecjia_admin::make_admin_menu('captcha', '验证码设置', RC_Uri::url('captcha/admin_config/init'), 131)->add_purview('captcha_manage');

        return $menus;
    }


    /**
     * Register the listeners for the subscriber.
     *
     * @param \Royalcms\Component\Hook\Dispatcher $events
     * @return void
     */
    public function subscribe(Dispatcher $events)
    {
        RC_Hook::add_action('admin_login_captcha', array(__CLASS__, 'admin_login_captcha'));
        RC_Hook::add_filter('admin_login_validate', array(__CLASS__, 'admin_login_validate'));
        RC_Hook::add_filter('admin_access_public_route', array(__CLASS__, 'set_admin_captcha_access'));

        RC_Hook::add_action('append_admin_setting_group', array(__CLASS__, 'append_admin_setting_group'));
    }

}