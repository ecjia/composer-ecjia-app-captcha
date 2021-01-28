<?php
defined('IN_ECJIA') or exit('No permission resources.');

/**
 * 验证码应用
 */
return array(
    'identifier'  => 'ecjia.captcha',
    'directory'   => 'captcha',
    'name'        => __('验证码', 'captcha'),
    'description' => __('验证码插件核心功能为“防止恶意破解密码、刷票、论坛灌水、刷页”等，为用户账户安全带来一定防护作用。在插件使用过程中，您可以添加多个不同验证码且可以实时点击预览效果；并选用您需要使用的验证码；您可以自定义验证码的宽度、高度及验证码使用范围、机制等。', 'captcha'),            /* 描述对应的语言项 */
    'author'      => 'ECJIA TEAM',            /* 作者 */
    'website'     => 'http://www.ecjia.com',    /* 网址 */
    'version'     => '2.0.0',                    /* 版本号 */
    'copyright'   => 'ECJIA Copyright 2014.',
    'namespace'   => 'Ecjia\App\Captcha',
    'provider'    => 'CaptchaServiceProvider',
    'autoload'    => array(
        'psr-4' => array(
            "Ecjia\\App\\Captcha\\" => "classes/"
        )
    ),
    'discover'    => array(
        'providers' => array(
            "Ecjia\\App\\Captcha\\CaptchaServiceProvider"
        ),
        'aliases'   => [

        ]
    ),
);

// end