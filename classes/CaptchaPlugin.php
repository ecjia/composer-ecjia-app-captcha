<?php


namespace Ecjia\App\Captcha;


use ecjia;
use Ecjia\Component\Plugin\PluginNoModel;
use Ecjia\Component\Plugin\Storages\CaptchaPluginStorage;
use ecjia_error;
use RC_Uri;

class CaptchaPlugin extends PluginNoModel
{

    /**
     * 获取当前类型的已经安装激活插件
     */
    public function getInstalledPlugins()
    {
        return (new CaptchaPluginStorage())->getPlugins();
    }

    /**
     * 获取数据中的Config配置数据，并处理
     */
    public function configData($code)
    {

    }

    /**
     * 取得指定code的验证码
     * @param $code
     * @return ecjia_error|void
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

        /**
         * @var CaptchaAbstract $handler
         */
        $handler = $this->pluginInstance($code, $config);

        if (!$handler) {
            return new ecjia_error('code_not_found', $code . ' plugin not found!');
        }

        //清除之前出现的多余输入
        @ob_end_clean();
        error_reporting(0);

        return $handler->generate_image();
    }

    /**
     * 取得默认的验证码
     * @param $code
     * @return ecjia_error|void
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

        /**
         * @var CaptchaAbstract $handler
         */
        $handler = $this->pluginInstance($code, $config);

        if (!$handler) {
            return new ecjia_error('code_not_found', $code . ' plugin not found!');
        }

        $handler->generate_image();
    }

    /**
     * 获取验证码图片的url
     * @param string $code
     * @param boolean $is_admin 是否在后台使用的地址
     * @return string
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
     * @param string|null $code
     */
    public function check_activation_captcha($code = null)
    {
        if (empty($code)) {
            $code = ecjia::config('captcha_style');
        }

        $captcha_plugins = $this->getInstalledPlugins();
        if (isset($captcha_plugins[$code])) {
            return true;
        }

        return false;
    }

    /**
     * 获取默认插件实例
     */
    public function defaultChannel()
    {
        if (empty($code)) {
            $code = ecjia::config('captcha_style');
        }

        return $this->channel($code);
    }

    public function channel($code = null)
    {
        $config = array(
            'width'  => ecjia::config('captcha_width'),
            'height' => ecjia::config('captcha_height'),
        );

        /**
         * @var CaptchaAbstract $handler
         */
        $handler = $this->pluginInstance($code, $config);

        if (!$handler) {
            return new ecjia_error('code_not_found', $code . ' plugin not found!');
        }

        return $handler;
    }

}