<footer class="footer">
	<div class="container">
	    <?php if(!_MBT('footer_widget')){?>
		<div class="footer-widgets">
	    	<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('widget_bottom')) : endif; ?>
	    </div>
	    <?php }?>
	    <?php if((is_home() || is_front_page()) && _MBT('friendlink')){?>
	    <div class="footer-links">
	    	<ul><li>友情链接：</li><?php wp_list_bookmarks('title_li=&categorize=0&show_images=0'); ?></ul>
	    </div>
	    <?php }?>
	    <p class="copyright"><?php echo _MBT('copyright');?></p>
	</div>
</footer>
<?php if(_MBT('rollbar')){?>
<div class="rollbar">
	<ul>
		<?php if(!_MBT('vip_hidden')){?><li><a href="<?php echo get_permalink(MBThemes_page("template/vip.php"));?>" title="升级VIP"><i class="icon icon-crown"></i></a></li><?php }?>
		<?php 
			if(_MBT('checkin')) {
		?>
		<?php if(is_user_logged_in()){global $current_user;?>
			<?php if(MBThemes_check_checkin($current_user->ID)){?>
			<li><a href="javascript:;" title="已签到" class="day-checkin active"><i class="icon icon-calendar"></i></a></li>
			<?php }else{?>
			<li><a href="javascript:;" title="每日签到" class="day-checkin"><i class="icon icon-calendar"></i></a></li>
			<?php }?>
		<?php }else{?>
			<li><a href="javascript:;" title="每日签到" class="signin-loader"><i class="icon icon-calendar"></i></a></li>
		<?php }?>
		<?php
			}
		?>
		<?php if(_MBT('kefu_qq')){?><li><a href="<?php echo _MBT('kefu_qq');?>" target="_blank" rel="nofollow" title="QQ客服"><i class="icon icon-qq"></i></a></li><?php }?>
		<?php if(_MBT('kefu_weixin')){?><li><a href="javascript:;" title="官方微信" class="kefu_weixin"><i class="icon icon-weixin"></i><img src="<?php echo _MBT('kefu_weixin');?>"></a></li><?php }?>
		<?php if(_MBT('fullscreen')){?><li><a href="javascript:;" title="全屏" class="fullscreen"><i class="icon icon-fullscreen"></i></a></li><?php }?>
		<li class="totop-li"><a href="javascript:;" class="totop"><i class="icon icon-arrow-up" title="返回顶部"></i></a></li>    
	</ul>
</div>
<?php }?>
<?php if(_MBT('site_tips')){?><div class="sitetips"><?php echo _MBT('site_tips');?><a href="javascript:;" class="close"><i class="icon icon-close"></i></a></div><?php }?>
<?php if(!is_user_logged_in()) get_template_part('module/login');?>
<?php wp_footer();?>
<script>MOBANTU.init({ias: <?php echo _MBT('ajax_list_load')?'1':'0';?>, lazy: <?php echo _MBT('lazyload')?'1':'0';?>, water: <?php echo _MBT('waterfall')?'1':'0';?>});<?php if(_MBT('frontend_copy')){?>document.oncontextmenu = new Function("return false;");document.onkeydown = document.onkeyup = document.onkeypress = function(event) {var e = event || window.event || arguments.callee.caller.arguments[0];if (e && (e.keyCode == 123 || e.keyCode == 116)) {e.returnValue = false;return (false);}}<?php }?>
</script>
<?php echo _MBT('js');?>
<div class="analysis"><?php echo _MBT('analysis');?></div>
</body>
</html>