<?php
defined('IN_ECJIA') or exit('No permission resources.');

/**
 * 插件使用方法
 * @author royalwang
 *
 */
class captcha_method
{

    public function __construct()
    {
    }

    /**
     * 获取所有可用的验证码
     */
    public function captcha_list()
    {
        $plugins         = RC_Plugin::get_plugins();
        $captcha_plugins = ecjia_config::instance()->get_addon_config('captcha_plugins', true);

        $list = array();
        foreach ($captcha_plugins as $code => $plugin) {
            if (isset($plugins[$plugin])) {
                $list[$code] = $plugins[$plugin];

                $list[$code]['code']               = $code;
                $list[$code]['format_name']        = $list[$code]['Name'];
                $list[$code]['format_description'] = $list[$code]['Description'];
            }
        }

        return $list;
    }


    /**
     * 取得指定code的验证码
     * @param $code
     */
    public function captcha_style_image($code = null)
    {
        if (empty($code)) {
            $code = ecjia::config('captcha_style');
        }

        $config = array(
            'width'  => ecjia::config('captcha_width'),
            'height' => ecjia::config('captcha_height'),
        );


        RC_Loader::load_app_class('captcha_factory', 'captcha', false);
        $handler = new captcha_factory($code, $config);
        @ob_end_clean(); //清除之前出现的多余输入
        error_reporting(0);
        return $handler->generate_image();
    }


    /**
     * 取得默认的验证码
     * @param $code
     */
    public function captcha_default_image($code = null)
    {
        if (empty($code)) {
            $code = ecjia::config('captcha_style');
        }

        $config = array(
            'width'  => ecjia::config('captcha_width'),
            'height' => ecjia::config('captcha_height'),
        );

        RC_Loader::load_app_class('captcha_factory', 'captcha', false);
        $handler = new captcha_factory($code, $config);
        $handler->generate_image();
    }

    /**
     * 获取验证码图片的url
     * @param string $code
     * @param boolean $is_admin 是否在后台使用的地址
     * @return Ambigous <string, String>
     */
    public function captcha_style_url($code, $is_admin = false)
    {
        $random = rc_random(10);
        if ($is_admin) {
            $captcha_url = RC_Uri::url('captcha/admin_captcha/init', array('code' => $code, 'rand' => $random));
        } else {
            $captcha_url = RC_Uri::url('captcha/index/init', array('code' => $code, 'rand' => $random));
        }
        return $captcha_url;
    }


    /**
     * 取得当前验证码的url
     * @param bool $is_admin 是否在后台使用的地址
     */
    public function current_captcha_url($is_admin = false)
    {
        return $this->captcha_style_url(ecjia::config('captcha_style'), $is_admin);
    }

    /**
     * 检测激活的验证码
     * @param unknown $code
     */
    public function check_activation_captcha($code = null)
    {
        if (empty($code)) {
            $code = ecjia::config('captcha_style');
        }
        $captcha_plugins = ecjia_config::instance()->get_addon_config('captcha_plugins', true);
        if (isset($captcha_plugins[$code])) {
            return true;
        }
        return false;
    }

}

// end