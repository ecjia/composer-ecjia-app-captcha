<?php
namespace Ecjia\App\Captcha\Services;


/**
 * 后台权限API
 * @author royalwang
 *
 */
class AdminPurviewService
{
    /**
     * @param $options
     * @return array
     */
    public function handle(& $options)
    {
        $purviews = array(
            array('action_name' => __('验证码管理', 'mail'), 	'action_code' => 'captcha_manage', 'relevance' => ''),
        );
        return $purviews;
    }
}

// end