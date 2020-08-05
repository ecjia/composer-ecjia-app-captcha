<?php
	function assign_adminlog_content() {
		ecjia_admin_log::instance()->add_object('config',__('配置', 'captcha'));
	}
	
//end