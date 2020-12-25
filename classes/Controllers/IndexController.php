<?php
/**
 * 验证码显示
 */

namespace Ecjia\App\Captcha\Controllers;

use ecjia;
use Ecjia\App\Captcha\CaptchaPlugin;
use Ecjia\System\BaseController\EcjiaFrontController;

class IndexController extends EcjiaFrontController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function init()
    {
        $code = trim($_GET['code']);

        $image = (new CaptchaPlugin())->captcha_style_image($code);

        echo $image;
    }

    public function check_validate()
    {
        if (isset($_POST['captcha']) && $_SESSION['captcha_word'] != strtolower($_POST['captcha'])) {
            return $this->showmessage(__('验证码错误!', 'captcha'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
        } else {
            return $this->showmessage(__('验证码正确！', 'captcha'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
        }
    }

}

// end