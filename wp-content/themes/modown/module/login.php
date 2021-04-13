<div class="sign">			
	<div class="sign-mask"></div>			
	<div class="container <?php if(_MBT('oauth_qq') || _MBT('oauth_weibo') || _MBT('oauth_weixin')) echo 'has-social';?>">			
		<div class="sign-tips"></div>			
		<form id="sign-in">  
		    <div class="form-item center"><a href="<?php echo home_url();?>"><img class="logo-login" src="<?php echo _MBT('logo_login');?>" alt="<?php bloginfo('name');?>"></a></div>
			<div class="form-item"><input type="text" name="user_login" class="form-control" id="user_login" placeholder="用户名/邮箱"><i class="icon icon-user"></i></div>			
			<div class="form-item"><input type="password" name="password" class="form-control" id="user_pass" placeholder="密码"><i class="icon icon-lock"></i></div>			
			<div class="sign-submit">			
				<input type="button" class="btn signinsubmit-loader" name="submit" value="登录">  			
				<input type="hidden" name="action" value="signin">			
			</div>			
			<div class="sign-trans"><?php if(!_MBT('register')){?>没有账号？ <a href="javascript:;" class="signup-loader">注册</a><?php }?><a href="<?php echo get_permalink(MBThemes_page("template/login.php"));?>?action=password" style="float:right" rel="nofollow" target="_blank">忘记密码？</a></div>	
			<?php if(_MBT('oauth_qq') || _MBT('oauth_weibo') || _MBT('oauth_weixin')){?>			
			<div class="sign-social">
				<h2>社交账号快速登录</h2>
				<?php if(_MBT('oauth_qq')){?><a class="login-qq" href="<?php echo home_url();?>/oauth/qq?rurl=<?php echo MBThemes_selfURL();?>"><i class="icon icon-qq"></i>QQ登录</a><?php }?>
				<?php if(_MBT('oauth_weibo')){?><a class="login-weibo" href="<?php echo home_url();?>/oauth/weibo?rurl=<?php echo MBThemes_selfURL();?>"><i class="icon icon-weibo"></i>微博登录</a><?php }?>
				<?php if(_MBT('oauth_weixin')){?><a class="login-weixin" href="https://open.weixin.qq.com/connect/qrconnect?appid=<?php echo _MBT('oauth_weixinid');?>&redirect_uri=<?php echo home_url();?>/oauth/weixin/&response_type=code&scope=snsapi_login&state=MBT_weixin_login#wechat_redirect"><i class="icon icon-weixin"></i>微信登录</a><?php }?>			
			</div>	
			<?php }?>		
		</form>	
		<?php if(!_MBT('register')){?>		
		<form id="sign-up" style="display: none;"> 	
		    <div class="form-item center"><a href="<?php echo home_url();?>"><img class="logo-login" src="<?php echo _MBT('logo_login');?>" alt="<?php bloginfo('name');?>"></a></div>				
			<div class="form-item"><input type="text" name="name" class="form-control" id="user_register" placeholder="用户名"><i class="icon icon-user"></i></div>			
			<div class="form-item"><input type="email" name="email" class="form-control" id="user_email" placeholder="邮箱"><i class="icon icon-mail"></i></div>		
			<div class="form-item"><input type="password" name="password2" class="form-control" id="user_pass2" placeholder="密码"><i class="icon icon-lock"></i></div>
			<?php if(_MBT('captcha') == 'email'){?>	
			<div class="form-item">
				<input type="text" class="form-control" style="width:calc(100% - 123px);display: inline-block;" id="captcha" name="captcha" placeholder="验证码"><span class="captcha-clk">获取邮箱验证码</span>
				<i class="icon icon-safe"></i>
			</div>	
			<?php }elseif(_MBT('captcha') == 'image'){?>
			<div class="form-item">
				<input type="text" class="form-control" style="width:calc(100% - 129px);display: inline-block;" id="captcha" name="captcha" placeholder="验证码"><img src="<?php bloginfo("template_url");?>/static/img/captcha.png" class="captcha-clk2" style="height:40px;position: relative;top: -1px;cursor: pointer;border: 1px solid #e6eaed;"/>
				<i class="icon icon-safe"></i>
			</div>
			<?php }?>		
			<div class="sign-submit">			
				<input type="button" class="btn signupsubmit-loader" name="submit" value="注册">  			
				<input type="hidden" name="action" value="signup">  				
			</div>			
			<div class="sign-trans">已有账号？ <a href="javascript:;" class="signin-loader">登录</a></div>		
			<?php if(_MBT('oauth_qq') || _MBT('oauth_weibo') || _MBT('oauth_weixin')){?>			
			<div class="sign-social">
				<h2>社交账号快速注册</h2>
				<?php if(_MBT('oauth_qq')){?><a class="login-qq" href="<?php echo home_url();?>/oauth/qq?rurl=<?php echo MBThemes_selfURL();?>"><i class="icon icon-qq"></i>QQ注册</a><?php }?>
				<?php if(_MBT('oauth_weibo')){?><a class="login-weibo" href="<?php echo home_url();?>/oauth/weibo?rurl=<?php echo MBThemes_selfURL();?>"><i class="icon icon-weibo"></i>微博注册</a><?php }?>	
				<?php if(_MBT('oauth_weixin')){?><a class="login-weixin" href="https://open.weixin.qq.com/connect/qrconnect?appid=<?php echo _MBT('oauth_weixinid');?>&redirect_uri=<?php echo home_url();?>/oauth/weixin/&response_type=code&scope=snsapi_login&state=MBT_weixin_login#wechat_redirect"><i class="icon icon-weixin"></i>微信注册</a><?php }?>		
			</div>	
			<?php }?>	
		</form>	
		<?php }?>		
	</div>			
</div>