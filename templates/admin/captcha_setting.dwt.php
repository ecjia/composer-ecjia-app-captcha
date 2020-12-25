<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="admin_shop_config.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.captcha.init();
</script>
<!-- {/block} -->

<!-- {block name="admin_config_form"} -->
<div>
    <h3 class="heading">{if $ur_here}  {$ur_here} {/if}</h3>
</div>
<div class="row-fluid edit-page">
    <div class="span12">
        <form class="m_b20" method="post" action="{$form_action}" name="theForm" >
            <div class="control-group formSep">
                <label class="control-label"><strong>{t domain="captcha"}启用验证码{/t}</strong></label>
                <p class="ecjiafc-999">{t domain="captcha"}图片验证码可以避免恶意批量评论或提交信息，推荐打开验证码功能。注意: 启用验证码会使得部分操作变得繁琐，建议仅在必需时打开{/t}</p>
                <div class="controls chk_radio">
                    {$captcha_selected_render}
                </div>
            </div>
            <!-- 登录失败时显示验证码 -->
            <div class="control-group formSep clear">
                <label class="control-label"><strong>{t domain="captcha"}登录失败时显示验证码{/t}</strong></label>
                <p class="ecjiafc-999">{t domain="captcha"}选择“是”将在用户登录失败 3 次后才显示验证码，选择“否”将始终在登录时显示验证码。注意: 只有在启用了用户登录验证码时本设置才有效{/t}</p>
                <div class="controls chk_radio">
                    <input type="radio" name="captcha_login_fail" value="16" {$captcha.login_fail_yes} /><span>{t domain="captcha"}是{/t}</span>
                    <input type="radio" name="captcha_login_fail" value="0" {$captcha.login_fail_no} /><span>{t domain="captcha"}否{/t}</span>
                </div>
            </div>
            <!--TODO验证码宽度高度值目前是写死的。 -->
            <div class="control-group formSep">
                <label class="control-label"><strong>{t domain="captcha"}验证码图片宽度{/t}</strong></label>
                <p class="ecjiafc-999">{t domain="captcha"}验证码图片的宽度，范围在 40～145 之间{/t}</p>
                <div class="controls">
                    <input type="text" name="captcha_width" value="{$captcha.captcha_width}" />
                    <span class="input-must">*</span>
                </div>
            </div>
            <div class="control-group formSep">
                <label class="control-label"><strong>{t domain="captcha"}验证码图片高度{/t}</strong></label>
                <p class="ecjiafc-999">{t domain="captcha"}验证码图片的高度，范围在 15～50 之间{/t}</p>
                <div class="controls capcha_sl">
                    <input type="text" name="captcha_height" value="{$captcha.captcha_height}" />
                    <span class="input-must">*</span>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <input class="btn btn-gebo" type="submit" value="{t domain="captcha"}保存设置{/t}" />
                </div>
            </div>
        </form>
    </div>
</div>
<!-- {/block} -->