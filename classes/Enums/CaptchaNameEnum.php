<?php
/**
 * Created by PhpStorm.
 * User: royalwang
 * Date: 2019/3/10
 * Time: 03:12
 */

namespace Ecjia\App\Captcha\Enums;


use Royalcms\Component\Enum\Enum;

class CaptchaNameEnum extends Enum
{

    /* 验证码 */
    const CAPTCHA_REGISTER      = 'captcha_register'; //注册时使用验证码
    const CAPTCHA_LOGIN         = 'captcha_login'; //登录时使用验证码
    const CAPTCHA_COMMENT       = 'captcha_comment'; //评论时使用验证码
    const CAPTCHA_ADMIN         = 'captcha_admin'; //后台登录时使用验证码
    const CAPTCHA_LOGIN_FAIL    = 'captcha_login_fail'; //登录失败后显示验证码
    const CAPTCHA_MESSAGE       = 'captcha_message'; //留言时使用验证码

    protected function __statusMap()
    {

        return [
            self::CAPTCHA_REGISTER      => __('注册时使用验证码', 'captcha'),
            self::CAPTCHA_LOGIN         => __('登录时使用验证码', 'captcha'),
            self::CAPTCHA_COMMENT       => __('评论时使用验证码', 'captcha'),
            self::CAPTCHA_ADMIN         => __('后台登录时使用验证码', 'captcha'),
            self::CAPTCHA_LOGIN_FAIL    => __('登录失败后显示验证码', 'captcha'),
            self::CAPTCHA_MESSAGE       => __('留言时使用验证码', 'captcha'),
        ];
    }
}