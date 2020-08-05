<?php
defined('IN_ECJIA') or exit('No permission resources.');

class sms_module extends api_admin implements api_interface {
    public function handleRequest($request) {
	    //sms_get_validate 
    
		$type = $this->requestData('type');
		$value = $this->requestData('mobile', '');
		$code = $this->requestData('code');
		if (empty($type) || empty($value) || empty($code)) {
			return new ecjia_error( 'invalid_parameter', __('参数无效', 'captcha'));
		}
		
		if (RC_Time::gmtime() > $_SESSION['captcha']['sms'][$type]['lifetime']) {
		    return new ecjia_error('code pasted', __('验证码已过期', 'captcha'));
		}
		if ($code != $_SESSION['captcha']['sms'][$type]['code']) {
		    return new ecjia_error('code error', __('验证码错误', 'captcha'));
		}
		if ($value != $_SESSION['captcha']['sms'][$type]['value']) {
		    return new ecjia_error('mobile error', __('接收和验证的手机号不同', 'captcha'));
		}
		
		return array();
	}
}


// end