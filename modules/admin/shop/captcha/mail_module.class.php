<?php
defined('IN_ECJIA') or exit('No permission resources.');

/**
 * 绑定邮箱发送验证码
 * zrl
 */
class mail_module extends api_admin implements api_interface {
    public function handleRequest($request) {
    
		$type = $this->requestData('type', 'user_modify_mail');
		$value = $this->requestData('value');
		if (empty($type) || empty($value)) {
			return new ecjia_error( 'invalid_parameter', __('参数无效', 'captcha'));
		}
		
		$code = rand(100001, 999999);
		    
	    $chars = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
	    if (!preg_match($chars, $value)) {
	        return new ecjia_error('email_error', __('邮箱账号格式错误', 'captcha'));
	    }
	    if (RC_Time::gmtime() - $_SESSION['captcha']['mail']['sendtime'] < 60) {
	        return new ecjia_error('send_error', __('发送频率过高，请一分钟后再试', 'captcha'));
	    }
	    
	    //type说明wiki地址:http://wiki.shangchina.com/index.php?title=Captcha_type_code
	    $common_template = array('user_modify_mail');

	    $tpl_name = 'email_verifying_authentication';
	    $tpl   = RC_Api::api('mail', 'mail_template', $tpl_name);

	    /* 判断短信模板是否存在*/
	    if (!empty($tpl)) {
	        ecjia_api::$controller->assign('user_name', $_SESSION['admin_name']);
	        ecjia_api::$controller->assign('code', $code);
	        ecjia_api::$controller->assign('action', __('绑定邮箱操作', 'captcha'));
	        ecjia_api::$controller->assign('server_phone', ecjia::config('service_phone'));
	        ecjia_api::$controller->assign('shop_name', ecjia::config('shop_name'));
	        ecjia_api::$controller->assign('send_date', RC_Time::local_date('Y-m-d'));
	        $content  = ecjia_api::$controller->fetch_string($tpl['template_content']);
	        $response = RC_Mail::send_mail(ecjia::config('shop_name'), $value, $tpl['template_subject'], $content, $tpl['is_html']);
	    } else {
	        return new ecjia_error('email_template_error', __('请检查邮件模板email_verifying_authentication', 'captcha'));
	    }
	    
	    /* 判断是否发送成功*/
	    if ($response === true) {
	        $time = RC_Time::gmtime();
	        $_SESSION['captcha']['mail'][$type] = array(
	            'value' => $value,
	            'code' => $code,
	            'lifetime' => $time + 1800,
	            'sendtime' => $time,
	        );
	        $_SESSION['captcha']['mail']['sendtime'] = $time;
	        $re = array('send_code_success' => __('验证码发送成功！', 'captcha'));
			return array('data' => $re);
	    } else {
	        return new ecjia_error('send_code_error', __('验证码发送失败！', 'captcha'));
	    }
		    
	}
}


// end