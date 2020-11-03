<?php


namespace Ecjia\App\Captcha\Subscribers;


use captcha_factory;
use ecjia;
use Ecjia\App\Captcha\Enums\CaptchaEnum;
use RC_ENV;
use RC_Hook;
use RC_Loader;
use RC_Uri;
use Royalcms\Component\Hook\Dispatcher;

class AdminHookSubscriber
{

    static public function admin_login_captcha()
    {
        if (ecjia::config('captcha_style', ecjia::CONFIG_EXISTS) &&
            (intval(ecjia::config('captcha')) & CaptchaEnum::CAPTCHA_ADMIN) &&
            RC_ENV::gd_version() > 0) {
            $captcha = RC_Loader::load_app_class('captcha_method', 'captcha');
            if ($captcha->check_activation_captcha()) {
                $captcha_url = $captcha->current_captcha_url(true);

                $click_for_another = __('看不清？点击更换另一个验证码。', 'captcha');
                $label_captcha     = __('验证码', 'captcha');

                $validate_length = 4;
                $validate_url    = RC_Uri::url('captcha/admin_captcha/check_validate');
                echo <<<EOF
		<div class="hideimg" style="z-index: -1; position: absolute;"><a class='close'>×</a><img src='$captcha_url' title='$click_for_another' ></div>
		<div class="formRow">
			<div class="input-prepend">
				<span class="add-on"><i class="icon-picture"></i></span>
				<input type="text" maxlength="4" name="captcha" placeholder="$label_captcha" value="" data-content="<div class='showimg'></div>" data-placement="top" />
			</div>
		</div>
		
		<script>
			var validate_length = {$validate_length},
				validate_url = "{$validate_url}";
			$(window).load(function(){
				var obj_hideimg = $('.hideimg');
				var obj_img = obj_hideimg.find('img');
						
						
				$(document).on('click', '.popover img', function(){
					var newsrc = $(this).attr('src')+Math.random();
					$(this).attr('src',newsrc);
					$('.hideimg img').attr('src',newsrc);
				});
				$(document).on('click', '.close', function(){
					$('.popover').remove();
				});
	
				$('input[name="captcha"]').keyup(function(event){
					if(event.keyCode === 27 || event.keyCode === 13){
						$('.popover').remove();
						$(this).blur();
					}
						
					if(event.keyCode === 13){
						return;
					}
					var obj_this = $(this),
						obj_row = obj_this.parents('.formRow');
					obj_this.val(obj_this.val().toUpperCase());
					if (obj_this.val().length == validate_length) {
						$.post(validate_url, {'captcha': obj_this.val()}, function(data) {
							if (data.state == 'success') {
								obj_row.hasClass('f_success') ? obj_row.removeClass('f_error') : obj_row.removeClass('f_error').addClass('f_success');
								$('.popover').remove();
							} else {
								obj_row.hasClass('f_error') ? obj_row.removeClass('f_success') : obj_row.removeClass('f_success').addClass('f_error');
							}
						});
					}
				}).popover({
					html: true,
					animation: false,
					trigger: 'manual',
					content: function(){
						var width = obj_img.width()+20;
						var height = obj_img.height()+20;
						return obj_hideimg.clone().css({width : width, height: height, position : 'relative', zIndex : '9999'});
					}
				});
				$('input[name="captcha"]').focus(function(){
					if(!$('.popover').text()){
						$(this).popover('show');
					}
				});
	
			});
		</script>
EOF;
            }
        }
    }


    static public function admin_login_validate($args)
    {
        if (ecjia::config('captcha_style', ecjia::CONFIG_EXISTS) &&
            !empty($_SESSION['captcha_word']) &&
            (intval(ecjia::config('captcha')) & CaptchaEnum::CAPTCHA_ADMIN)) {
            /* 检查验证码是否正确 */
            RC_Loader::load_app_class('captcha_factory', 'captcha', false);
            $validator = new captcha_factory(ecjia::config('captcha_style'));
            if (isset($args['captcha']) && !$validator->verify_word($args['captcha'])) {
                return __('您输入的验证码不正确。', 'captcha');
            }
        }
    }

    static public function set_admin_captcha_access($route)
    {
        $route[] = 'captcha/admin_captcha/init';
        $route[] = 'captcha/admin_captcha/check_validate';
        return $route;
    }


    /**
     * Register the listeners for the subscriber.
     *
     * @param \Royalcms\Component\Hook\Dispatcher $events
     * @return void
     */
    public function subscribe(Dispatcher $events)
    {
        RC_Hook::add_action('admin_login_captcha', array(__CLASS__, 'admin_login_captcha'));
        RC_Hook::add_filter('admin_login_validate', array(__CLASS__, 'admin_login_validate'));
        RC_Hook::add_filter('admin_access_public_route', array(__CLASS__, 'set_admin_captcha_access'));

    }

}