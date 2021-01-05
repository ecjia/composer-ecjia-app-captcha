<?php


namespace Ecjia\App\Captcha\BrowserEvent;


use Ecjia\Component\BrowserEvent\BrowserEventInterface;
use RC_Uri;

class CaptchaPopoverEvent implements BrowserEventInterface
{

    public function __construct()
    {

    }

    public function __invoke()
    {
        $validate_length = 4;
        $validate_url    = RC_Uri::url('captcha/admin_captcha/check_validate');

        return <<<JS
(function () {
    //打开验证码显示
    let captcha_element = $('input[name="captcha"]');
    let captcha_popover = $('.captcha-popover');
    let captcha_popover_img = captcha_popover.find('img');
    let validate_length = {$validate_length};
    let validate_url = "{$validate_url}";
    
    captcha_element.popover({
        html: true,
        animation: false,
        trigger: 'manual',
        content: function() {
            let width = captcha_popover_img.width() + 20;
            let height = captcha_popover_img.height() + 20;
            return captcha_popover.clone().css({width : width, height: height, position : 'relative', zIndex : '9999'});
        }
    });
    
    captcha_element.keyup(function(event){
        if(event.keyCode === 27 || event.keyCode === 13){
            $('.popover').remove();
            $(this).blur();
        }
            
        if(event.keyCode === 13){
            return;
        }
        
        let that = $(this);
        let formRow = that.parents('.formRow');
        that.val(that.val().toUpperCase());
        if (that.val().length === validate_length) {
            $.post(
                validate_url, 
                {
                    'captcha': that.val()
                }, 
                function(data) {
                    if (data.state === 'success') {
                        formRow.hasClass('f_success') ? formRow.removeClass('f_error') : formRow.removeClass('f_error').addClass('f_success');
                        $('.popover').remove();
                    } else {
                        formRow.hasClass('f_error') ? formRow.removeClass('f_success') : formRow.removeClass('f_success').addClass('f_error');
                    }
                });
        }
    });
    
    captcha_element.focus(function () {
        if (!$('captcha-popover .popover').text()) {
            $(this).popover('show');
        }
    });
    
    //关闭验证码窗口
    $(document).on('click', '.close', function(){
        console.log('click');
        $('.popover').remove();
    });
    
    //更换验证码
    $(document).on('click', '.popover img', function() {
        let src = $(this).attr('src') + Math.random();
        $(this).attr('src', src);
        captcha_popover_img.attr('src', src);
    });
    
})();
JS;

    }

}