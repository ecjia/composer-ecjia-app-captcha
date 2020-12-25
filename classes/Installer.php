<?php

namespace Ecjia\App\Captcha;

use ecjia;
use ecjia_config;
use ecjia_installer;

class Installer extends ecjia_installer
{

    protected $dependent = array(
        'ecjia.system' => '1.0',
    );

    public function __construct()
    {
        $id = 'ecjia.captcha';
        parent::__construct($id);
    }

    /**
     * 安装
     * @return bool
     */
    public function install()
    {
        if (!ecjia::config('captcha', ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->insert_config('hidden', 'captcha', '', array('type' => 'hidden'));
        }

        if (!ecjia::config('captcha_width', ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->insert_config('hidden', 'captcha_width', '', array('type' => 'hidden'));
        }

        if (!ecjia::config('captcha_height', ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->insert_config('hidden', 'captcha_height', '', array('type' => 'hidden'));
        }

        return true;
    }

    /**
     * 卸载
     * @return bool
     */
    public function uninstall()
    {
        if (ecjia::config('captcha', ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->delete_config('captcha');
        }

        if (ecjia::config('captcha_width', ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->delete_config('captcha_width');
        }

        if (ecjia::config('captcha_height', ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->delete_config('captcha_height');
        }

        return true;
    }

    /**
     * 升级
     * @return bool
     */
    public function upgrade()
    {

        return true;
    }


}

// end