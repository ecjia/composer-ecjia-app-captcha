<?php

namespace Ecjia\App\Captcha\Tags;

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.captcha.php
 * Type:     function
 * Name:     captcha
 * Purpose:  验证码显示
 * -------------------------------------------------------------
 */

use RC_Uri;
use Smarty_Internal_Template;

class CaptchaTag
{

    public static function ecjia_function_captcha($params, Smarty_Internal_Template $template)
    {
        $rand = rc_random(10);

        $url = RC_Uri::url('captcha/index/init', ['code' => 'ecjia.captcha_royalcms', 'rand' => $rand]);
        return $url;
    }

}
