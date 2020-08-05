<?php
defined('IN_ECJIA') or exit('No permission resources.');

/**
 * 绑定手机号发送验证码
 * zrl
 */
class sms_module extends api_front implements api_interface {
    public function handleRequest($request) {
		$type = $this->requestData('type', 'user_modify_mobile');
		$value = $this->requestData('value', '');
		//type说明wiki地址:http://wiki.shangchina.com/index.php?title=Captcha_type_code
		$types = array('user_modify_mobile');
		
		//参数判断
		if (empty($type) || empty($value)) {
			return new ecjia_error( 'invalid_parameter', __('参数无效', 'captcha'));
		}
		
		$code = rand(100001, 999999);
// 	    $chars = "/^1(3|4|5|7|8)\d{9}$/s";
// 	    if (!preg_match($chars, $value)) {
// 	        return new ecjia_error('mobile_error', '手机号码格式错误');
// 	    }
		$check_mobile = Ecjia\App\Sms\Helper::check_mobile($value);
		if (is_ecjia_error($check_mobile)) {
		    return $check_mobile;
		}
	    if (RC_Time::gmtime() - $_SESSION['captcha']['sms']['sendtime'] < 60) {
	        return new ecjia_error('send_error', __('发送频率过高，请一分钟后再试', 'captcha'));
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
	    $response = RC_Api::api('sms', 'send_event_sms', $options);
	    
	    $time = RC_Time::gmtime();
	    $_SESSION['captcha']['sms'][$type] = array(
	        'value' => $value,
	        'code' => $code,
	        'lifetime' => $time + 1800,
	        'sendtime' => $time,
	    );
	    $_SESSION['captcha']['sms']['sendtime'] = $time;
	     
	   
	    if (is_ecjia_error($response)) {
	        return new ecjia_error('sms_error', __('短信发送失败！', 'captcha'));//$response['description']
	    } else {
	        return array();
	    }
	
	    //发送短信
// 	    $tpl_name = 'sms_verifying_authentication ';
// 	    $tpl = RC_Api::api('sms', 'sms_template', $tpl_name);
// 	    ecjia_front::$controller->assign('code', $code);
// 	    ecjia_front::$controller->assign('action', '绑定手机号操作');
// 	    ecjia_front::$controller->assign('server_phone', ecjia::config('service_phone'));
// 	    $content = ecjia_front::$controller->fetch_string($tpl['template_content']);
// 	    $options = array(
// 	        'mobile' 		=> $value,
// 	        'msg'			=> $content,
// 	        'template_id' 	=> $tpl['template_id'],
// 	    );
// 		$response = RC_Api::api('sms', 'sms_send', $options);
// 		if ($response === true) {
// 		    $time = RC_Time::gmtime();
// 		    $time = RC_Time::gmtime();
// 		    $_SESSION['captcha']['sms'][$type] = array(
// 		    		'value' => $value,
// 		    		'code' => $code,
// 		    		'lifetime' => $time + 1800,
// 		    		'sendtime' => $time,
// 		    );
// 		    $_SESSION['captcha']['sms']['sendtime'] = $time;
// 		    $re = array('send_code_success' => '验证码发送成功！');
// 			 return array('data' => $re);
// 		} else {
// 			return new ecjia_error('sms_error', '短信发送失败！');
// 		}

	}
}


// end