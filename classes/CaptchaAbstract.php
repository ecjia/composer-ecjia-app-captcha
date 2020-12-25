<?php


namespace Ecjia\App\Captcha;


use Ecjia\Component\Plugin\AbstractPlugin;

/**
 * 插件抽象类
 * @author royalwang
 */
abstract class CaptchaAbstract extends AbstractPlugin
{

    abstract public function generate_image();

    abstract public function verify_word($word);


}