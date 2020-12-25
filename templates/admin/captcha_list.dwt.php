<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="plugin_config.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.captcha.init();
</script>
<!-- {/block} -->

<!-- {block name="admin_plugin_list"} -->
<div>
    <h3 class="heading">{if $ur_here}  {$ur_here} {/if}</h3>
</div>
<div class="row-fluid edit-page">
    <div class="span12">
        <div class="media_captcha wookmark">
            <ul>
                <!-- {foreach from=$captchas item=captcha} -->
                <li class="thumbnail">
                    <div class="hd">
                        <span class="flash-choose error_color {if $captcha.code neq $current_captcha}hidden{/if}">{t domain="captcha"}当前样式{/t}</span>
                        <!-- {$captcha.format_name} -->
                    </div>

                    <div class="bd">
                        <img name="media-boject" src='{url path="captcha/index/init" args="code={$captcha.code}&rand={$rand}"}' alt="captcha" data-toggle="change_captcha" data-src='{url path="captcha/index/init" args="code={$captcha.code}&rand="}' />
                    </div>

                    <div class="ft">
                        <!-- {$captcha.format_description} -->
                    </div>
                    <div class="input" data-url='{url path="captcha/admin/apply" args="code={$captcha.code}"}'><span>{t domain="captcha"}启用此验证码{/t}</span></div>

                </li>
                <!-- {/foreach} -->
                <li class="thumbnail">
                    <a class="more" href="{url path='@admin_plugin/init'}">
                        <i class="fontello-icon-plus"></i>
                        <span>{t domain="captcha"}添加验证码{/t}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- {/block} -->