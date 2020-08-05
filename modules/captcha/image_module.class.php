<?php
defined('IN_ECJIA') or exit('No permission resources.');

/**
 * 获取图形验证码
 * 
 * captcha/image
 * 
 * @author royalwang
 *
 */
class image_module extends api_front implements api_interface
{
    
    public function handleRequest($request)
    {
    	$this->authSession();
        //重新开启缓存区
        ob_start();
        
        $captcha = RC_Loader::load_app_class('captcha_method', 'captcha');
        $captcha->captcha_default_image();
        //从缓冲区中拿到图片
        $image = ob_get_contents();
        //清空缓冲区
        ob_end_clean();
        
        return ['base64' => base64_encode($image)];
    }
    
}

// end