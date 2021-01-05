<?php


namespace Ecjia\App\Captcha;


use Ecjia\Component\CaptchaScreen\Facades\CaptchaManager;
use ecjia_config;
use RC_ENV;

class CaptchaScreenValidator
{
    protected $captcha_value;

    /**
     * CaptchaScreenValidator constructor.
     * @param $value
     */
    public function __construct($value = null)
    {
        if (is_null($value)) {
            $value = intval(ecjia_config::get('captcha'));
        }

        $this->captcha_value = $value;
    }

    /**
     * 检查验证码场景是否开启
     * @param $name
     * @return bool
     */
    public function validate($name)
    {
        return $this->checkEnv() && $this->checkScreen($name);
    }

    /**
     * 检查环境
     * @return bool
     */
    protected function checkEnv()
    {
        return RC_ENV::gd_version() > 0;
    }

    /**
     * 检查场景
     * @param $name
     * @return bool
     */
    protected function checkScreen($name)
    {
        return CaptchaManager::hasSelectedScreen($name, $this->captcha_value);
    }

}