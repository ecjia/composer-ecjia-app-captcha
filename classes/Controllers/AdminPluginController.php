<?php
/**
 * 验证码管理
 */

namespace Ecjia\App\Captcha\Controllers;

use admin_nav_here;
use ecjia;
use Ecjia\App\Captcha\CaptchaPlugin;
use Ecjia\App\Captcha\Enums\CaptchaEnum;
use Ecjia\Component\CaptchaScreen\CaptchaScreen;
use Ecjia\Component\CaptchaScreen\CaptchaScreenManager;
use ecjia_admin;
use ecjia_config;
use ecjia_screen;
use RC_App;
use RC_ENV;
use RC_Lang;
use RC_Loader;
use RC_Script;
use RC_Style;
use RC_Uri;

class AdminPluginController extends AdminBase
{

    public function __construct()
    {
        parent::__construct();

        RC_Lang::load('captcha_manage');

        if (!ecjia::config('captcha_style', ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->insert_config('hidden', 'captcha_style', '', array('type' => 'hidden'));
        }

        RC_Style::enqueue_style('fontello');
        RC_Script::enqueue_script('smoke');
        // 单选复选框css
        RC_Style::enqueue_style('uniform-aristo');
        RC_Script::enqueue_script('jquery-uniform');

        ecjia_screen::get_current_screen()->set_parentage('captcha', 'captcha/admin_plugin');
    }

    public function init()
    {
        /* 检查权限 */
        $this->admin_priv('captcha_manage');

        RC_Script::enqueue_script('jquery-validate');
        RC_Script::enqueue_script('jquery-form');
        RC_Script::enqueue_script('captcha', RC_App::apps_url('statics/js/captcha.js', $this->__FILE__), array());
        RC_Script::localize_script('captcha', 'js_lang_captcha', config('app-captcha::jslang.captcha'));
        RC_Script::localize_script('captcha', 'admin_captcha_lang', config('app-captcha::jslang.admin_captcha_lang'));

        $captchas = (new CaptchaPlugin())->availablePluginList();

        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('可用验证码样式', 'captcha')));

        $this->assign('action_link', array('text' => __('验证码配置', 'captcha'), 'href' => RC_Uri::url('captcha/admin_config/init')));

        $this->assign('ur_here', __('可用验证码样式', 'captcha'));
        $this->assign('captchas', $captchas);
        $this->assign('current_captcha', ecjia::config('captcha_style'));

        $this->assign_lang();
        return $this->display('captcha_list.dwt');
    }

    /**
     * 切换验证码展示样式
     */
    public function apply()
    {
        try {
            $this->admin_priv('captcha_manage', ecjia::MSGTYPE_JSON);

            $captcha_code = trim($_GET['code']);
            if (ecjia::config('current_captcha') == $captcha_code) {
                return $this->showmessage(__('操作成功！', 'captcha'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
            }

            ecjia_config::item()->write('captcha_style', $captcha_code);

            ecjia_admin::admin_log(__('工具>切换验证码展示样式', 'captcha'), 'setup', 'config');

            return $this->showmessage(__('启用验证码样式成功。', 'captcha'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('captcha/admin_plugin/init')));
        } catch (\Exception $exception) {
            return $this->showmessage($exception->getMessage(), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
        } catch (\Error $exception) {
            return $this->showmessage($exception->getMessage(), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
        }
    }

}

// end