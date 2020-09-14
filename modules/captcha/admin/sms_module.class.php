<?php
defined('IN_ECJIA') or exit('No permission resources.');

class sms_module extends api_admin implements api_interface {
    public function handleRequest($request) {
	    //sms_get_validate 
    
		$type = $this->requestData('type');
		$value = $this->requestData('mobile', '');
		if (empty($type) || empty($value)) {
			return new ecjia_error( 'invalid_parameter', __('参数无效', 'captcha'));
		}
		
		$code = rand(100001, 999999);
// 	    $chars = "/^1(3|4|5|7|8)\d{9}$/s";
// 	    if (!preg_match($chars, $value)) {
// 	        return new ecjia_error('mobile_error', '手机号码格式错误');
// 	    }
		$check_mobile = \Ecjia\App\Sms\Helper::check_mobile($value);
		if (is_ecjia_error($check_mobile)) {
		    return $check_mobile;
		}
	    if (RC_Time::gmtime() - $_SESSION['captcha']['sms']['sendtime'] < 60) {
	        return new ecjia_error('send_error', __('发送频率过高，请一分钟后再试', 'captcha'));
	    }
	    
	    //type
	    //staff_modify_mobile(员工修改手机（包含配送员）)
	    $common_template = array('staff_modify_mobile');
	    
	    if (in_array($type, $common_template)) {
	        
	    }
	    
	    //发送短信
	    $options = array(
	        'mobile' => $value,
	        'event'	 => 'sms_get_validate',
	        'value'  =>array(
	            'code' 			=> $code,
	            'service_phone' => ecjia::config('service_phone'),
	        ),
	    );
	     
	    $_SESSION['captcha']['sms'][$type] = array(
	        'value' => $value,
	        'code' => $code,
	        'lifetime' => RC_Time::gmtime() + 1800,
	        'sendtime' => RC_Time::gmtime(),
	    );
	    $_SESSION['captcha']['sms']['sendtime'] = RC_Time::gmtime();
	     
	    $response = RC_Api::api('sms', 'send_event_sms', $options);
	    if (is_ecjia_error($response)) {
	        return new ecjia_error('sms_error', __('短信发送失败！', 'captcha'));//$response['description']
	    } else {
	        return array();
	    }
		
	    //发送短信
// 	    $tpl_name = 'sms_get_validate';
// 	    $tpl   = RC_Api::api('sms', 'sms_template', $tpl_name);
	    
// 	    ecjia_front::$controller->assign('mobile', $value);
// 	    ecjia_front::$controller->assign('service_phone', ecjia::config('service_phone'));
// 	    ecjia_front::$controller->assign('code', $code);
// 	    ecjia_front::$controller->assign('action', '使用积分');
	    
// 	    $content = ecjia_front::$controller->fetch_string($tpl['template_content']);
	    
// 	    $options = array(
// 	        'mobile' 		=> $value,
// 	        'msg'			=> $content,
// 	        'template_id' 	=> $tpl['template_id'],
// 	    );
// 	    $response = RC_Api::api('sms', 'sms_send', $options);
// 	    if (is_ecjia_error($response)) {
// 	    	return new ecjia_error('sms_error', '短信发送失败！');//$response['description']
// 	    }else{
// 	    	 $_SESSION['captcha']['sms'][$type] = array(
// 		        'value' => $value,
// 		        'code' => $code,
// 		        'lifetime' => RC_Time::gmtime() + 1800,
// 		        'sendtime' => RC_Time::gmtime(),
// 		    );
// 		    $_SESSION['captcha']['sms']['sendtime'] = RC_Time::gmtime();
// 			return array();
// 	    }
	}
    
}


// end