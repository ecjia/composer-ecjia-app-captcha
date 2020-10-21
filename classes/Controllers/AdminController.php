<?php
/**
 * 验证码管理
 */

namespace Ecjia\App\Captcha\Controllers;

use admin_nav_here;
use ecjia;
use Ecjia\App\Captcha\Enums\CaptchaEnum;
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

class AdminController extends AdminBase
{
    private $captcha;

    public function __construct()
    {
        parent::__construct();

        RC_Lang::load('captcha_manage');

        $this->captcha = RC_Loader::load_app_class('captcha_method');

        if (!ecjia::config('captcha_style', ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->insert_config('hidden', 'captcha_style', '', array('type' => 'hidden'));
        }

        RC_Style::enqueue_style('fontello');
        RC_Script::enqueue_script('smoke');
        // 单选复选框css
        RC_Style::enqueue_style('uniform-aristo');
        RC_Script::enqueue_script('jquery-uniform');
    }

    public function init()
    {
        /* 检查权限 */
        $this->admin_priv('captcha_manage');

        RC_Script::enqueue_script('jquery-validate');
        RC_Script::enqueue_script('jquery-form');
        RC_Script::enqueue_script('captcha', RC_App::apps_url('statics/js/captcha.js', $this->__FILE__), array());
        RC_Script::localize_script('captcha', 'js_lang_captcha', config('app-captcha::jslang.captcha'));

        $admin_captcha_lang = array(
            'captcha_width_required'  => __('请输入验证码图片宽度！', 'captcha'),
            'captcha_width_min'       => __('验证码图片宽度不能小于40！', 'captcha'),
            'captcha_width_max'       => __('验证码图片宽度不能大于145！', 'captcha'),
            'captcha_height_required' => __('请输入验证码图片高度！', 'captcha'),
            'captcha_height_min'      => __('验证码图片高度不能小于15！', 'captcha'),
            'captcha_height_max'      => __('验证码图片高度不能大于50！', 'captcha'),
            'setupConfirm'            => __('您确定要更换验证码样式吗？', 'captcha'),
            'is_checked'              => __('您已选中此验证码样式！', 'captcha'),
        );
        RC_Script::localize_script('captcha', 'admin_captcha_lang', $admin_captcha_lang);

        $captcha       = intval(ecjia::config('captcha'));
        $captcha_check = array();
        if ($captcha & CaptchaEnum::CAPTCHA_REGISTER) {
            $captcha_check['register'] = 'checked="checked"';
        }
        if ($captcha & CaptchaEnum::CAPTCHA_LOGIN) {
            $captcha_check['login'] = 'checked="checked"';
        }
        if ($captcha & CaptchaEnum::CAPTCHA_COMMENT) {
            $captcha_check['comment'] = 'checked="checked"';
        }
        if ($captcha & CaptchaEnum::CAPTCHA_ADMIN) {
            $captcha_check['admin'] = 'checked="checked"';
        }
        if ($captcha & CaptchaEnum::CAPTCHA_MESSAGE) {
            $captcha_check['message'] = 'checked="checked"';
        }

        if ($captcha & CaptchaEnum::CAPTCHA_LOGIN_FAIL) {
            $captcha_check['login_fail_yes'] = 'checked="checked"';
        } else {
            $captcha_check['login_fail_no'] = 'checked="checked"';
        }

        $captcha_check['captcha_width']  = ecjia::config('captcha_width');
        $captcha_check['captcha_height'] = ecjia::config('captcha_height');

        $this->assign('captcha', $captcha_check);

        $captchas = $this->captcha->captcha_list();

        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('验证码设置', 'captcha')));
        ecjia_screen::get_current_screen()->add_help_tab(array(
            'id'      => 'overview',
            'title'   => __('概述', 'captcha'),
            'content' =>
                '<p>' . __('欢迎访问ECJia智能后台验证码设置页面，可以在此页面设置验证码。', 'captcha') . '</p>'
        ));

        ecjia_screen::get_current_screen()->set_help_sidebar(
            '<p><strong>' . __('更多信息:', 'captcha') . '</strong></p>' .
            '<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:验证码设置" target="_blank">关于验证码设置帮助文档</a>', 'captcha') . '</p>'
        );

        $this->assign('ur_here', __('验证码设置', 'captcha'));
        $this->assign('captchas', $captchas);
        $this->assign('current_captcha', ecjia::config('captcha_style'));
        $this->assign('form_action', RC_Uri::url('captcha/admin/save_config'));

        $this->assign_lang();
        return $this->display('captcha_list.dwt');
    }

    /**
     * 保存设置
     */
    public function save_config()
    {
        if (RC_ENV::gd_version() == 0) {
            return $this->showmessage(__('开启验证码需要服务GD库支持，而您的服务器不支持GD。', 'captcha'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
        }

        $captcha = 0;
        $captcha = empty($_POST['captcha_register']) ? $captcha : $captcha | CaptchaEnum::CAPTCHA_REGISTER;
        $captcha = empty($_POST['captcha_login']) ? $captcha : $captcha | CaptchaEnum::CAPTCHA_LOGIN;
        $captcha = empty($_POST['captcha_comment']) ? $captcha : $captcha | CaptchaEnum::CAPTCHA_COMMENT;
        $captcha = empty($_POST['captcha_admin']) ? $captcha : $captcha | CaptchaEnum::CAPTCHA_ADMIN;
        $captcha = empty($_POST['captcha_login_fail']) ? $captcha : $captcha | CaptchaEnum::CAPTCHA_LOGIN_FAIL;
        $captcha = empty($_POST['captcha_message']) ? $captcha : $captcha | CaptchaEnum::CAPTCHA_MESSAGE;

        $captcha_width  = empty($_POST['captcha_width']) ? 145 : intval($_POST['captcha_width']);
        $captcha_height = empty($_POST['captcha_height']) ? 20 : intval($_POST['captcha_height']);

        ecjia_config::instance()->write_config('captcha', $captcha);
        ecjia_config::instance()->write_config('captcha_width', $captcha_width);
        ecjia_config::instance()->write_config('captcha_height', $captcha_height);

        ecjia_admin::admin_log(__('工具>验证码设置', 'captcha'), 'setup', 'config');

        return $this->showmessage(__('设置保存成功！', 'captcha'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
    }

    /**
     * 切换验证码展示样式
     */
    public function apply()
    {
        $this->admin_priv('captcha_manage', ecjia::MSGTYPE_JSON);

        $captcha_code = trim($_GET['code']);
        if (ecjia::config('current_captcha') == $captcha_code) {
            return $this->showmessage(__('操作成功！', 'captcha'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
        }

        ecjia_config::item()->write('captcha_style', $captcha_code);

        ecjia_admin::admin_log(__('工具>切换验证码展示样式', 'captcha'), 'setup', 'config');

        return $this->showmessage(__('启用验证码样式成功。', 'captcha'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('captcha/admin/init')));
    }
}

// end