<?php 
/*
	template name: 登录页面
	description: template for mobantu.com modown theme 
*/
	if(is_user_logged_in()){
		if(isset($_GET["redirect_to"])){
			header("Location:".$_GET["redirect_to"]);
		}else{
			header("Location:".get_permalink(MBThemes_page('template/user.php')));
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
  <title>登录 - <?php bloginfo("name");?></title>
  <link rel="shortcut icon" href="<?php echo _MBT('favicon')?>">
  <link rel="stylesheet"  href="<?php bloginfo("template_url");?>/static/css/libs.css" type="text/css" media="screen" />
  <link rel="stylesheet"  href="<?php bloginfo("template_url");?>/static/css/login.css" type="text/css" media="screen" />
  <script type="text/javascript" src="<?php bloginfo("template_url");?>/static/js/jquery.min.js"></script>
  <!--[if lt IE 9]><script src="<?php bloginfo("template_url");?>/static/js/html5.min.js"></script><![endif]-->
  <script>window._MBT = {uri: '<?php bloginfo('template_url') ?>', url:'<?php bloginfo('url');?>'}</script>
  <style><?php $theme_color_custom = _MBT('theme_color_custom');
  $theme_color = _MBT('theme_color');
  $color = '';
  if($theme_color && $theme_color != '#ff5f33'){
   $color = $theme_color;
  }
  if($theme_color_custom && $theme_color_custom != '#ff5f33'){
   $color = $theme_color_custom;
  }
  if($color){
  	echo '.loginbox .input-submit .submit{background:'.$color.';}';
  }?></style>
</head>
<body>
	<section class="login-wrap">
		<div id="loginbox" class="loginbox">	
	        
	    	<?php if($_GET['action'] == 'register' && !_MBT('register')){?>
		    <div class="part regPart">
		    	<h2><a href="<?php bloginfo("url");?>"><img src="<?php echo _MBT('logo');?>" alt="<?php bloginfo("name");?>"></a></h2>
		        <form id="regform" class="loginform" method="post" novalidate="novalidate" onSubmit="return false;" autocomplete="off">
		            <p class="input-item">
		                <input class="input-control" id="regname" type="text" placeholder="用户名" name="regname" required="" >
		            </p>
		            <p class="input-item">
		                <input class="input-control" id="regemail" type="email" placeholder="邮箱" name="regemail" required="" >
		            </p>
		            <p class="input-item">
		                <input class="input-control" id="regpass" type="password" placeholder="密码" name="regpass" required="">
		            </p>
		            <?php if(_MBT('captcha') == 'email'){?>
		            <p class="input-item">
		                <input class="input-control" id="captcha" type="text" placeholder="验证码" name="captcha" required="">
		                <span class="captcha-clk">获取邮箱验证码</span>
		            </p>
		            <?php }elseif(_MBT('captcha') == 'image'){?>
		            <p class="input-item">
		            	<input class="input-control" id="captcha" type="text" placeholder="验证码" name="captcha" required="">
		                <img src="<?php bloginfo("template_url");?>/static/img/captcha.png" class="captcha-clk2" />
		            </p>
		            <?php }?>
		            <p class="sign-tips"></p>
		            <p class="input-submit">
		                <input class="submit register-loader btn" type="submit" value="注册账号">
		                <input type="hidden" name="action" value="register">
		                <input type="hidden" id="security" name="security" value="<?php echo  wp_create_nonce( 'security_nonce' );?>">
            			<input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
		            </p>
		            <p class="safe">
		                <a class="signin-loader" href="<?php echo get_permalink(MBThemes_page('template/login.php'));?>">返回登录</a>
		            </p>
		        </form>
		    </div>
		    <?php }elseif($_GET['action'] == 'password'){?>
		    
		    <div class="part passPart">
		    	<h2><a href="<?php bloginfo("url");?>"><img src="<?php echo _MBT('logo');?>" alt="<?php bloginfo("name");?>"></a></h2>
		        <form id="passform" class="loginform" method="post" novalidate="novalidate" onSubmit="return false;">
		            <p class="input-item">
		                <input class="input-control" id="passname" type="text" placeholder="用户名/电子邮箱" name="passname" required="">
		            </p>
		            <p class="sign-tips"></p>
		            <p class="input-submit">
		                <input class="submit pass-loader btn" type="submit" value="找回密码">
		                <input type="hidden" name="action" value="password">
		                <input type="hidden" id="security" name="security" value="<?php echo  wp_create_nonce( 'security_nonce' );?>">
            			<input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
		            </p>
		            <p class="safe">
		                <a class="signin-loader" href="<?php echo get_permalink(MBThemes_page('template/login.php'));?>">返回登录</a>
		            </p> 
		        </form>
		    </div>
		    <?php }elseif($_GET['action'] == 'reset_password'){

		    ?>
		    <div class="part resetPart">
		    	<h2><a href="<?php bloginfo("url");?>"><img src="<?php echo _MBT('logo');?>" alt="<?php bloginfo("name");?>"></a></h2>
		    <?php
		    	$reset_key = $_GET['key']; 
				$user_login = esc_sql($_GET['login']); 
				$user_data = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email, user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));   
				$user_login = $user_data->user_login;   
				$user_email = $user_data->user_email;   
				if(!empty($reset_key) && !empty($user_data) && md5('mbt'.$user_data->user_activation_key) == $reset_key) {   
		    ?>

		        <form id="resetform" class="loginform" method="post" novalidate="novalidate" onSubmit="return false;">
		            <p class="input-item">
                        <input class="input-control" id="resetpass" type="password" placeholder="新密码" name="resetpass">
                    </p>
                    <p class="input-item">
                        <input class="input-control" id="resetpass2" type="password" placeholder="确认新密码" name="resetpass2">
                    </p>
                    <p class="sign-tips"></p>
                    <p class="input-submit">
                        <input class="submit reset-loader btn" type="button" value="修改密码">
                        <input type="hidden" name="action" value="reset">
                        <input type="hidden" name="key" id="resetkey" value="<?php echo $reset_key;?>">
                        <input type="hidden" name="user_login" id="user_login" value="<?php echo $user_login;?>">
                        <input type="hidden" id="security" name="security" value="<?php echo  wp_create_nonce( 'security_nonce' );?>">
        				<input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    </p>
		        </form>
		        <?php }else{?>
		        	<div class=regSuccess>错误的请求，请查看邮箱里的重置密码链接。</div>
		        <?php }?>
		    </div>
		    <?php }else{?>
		    <div class="part loginPart">
		    	<h2><a href="<?php bloginfo("url");?>"><img src="<?php echo _MBT('logo');?>" alt="<?php bloginfo("name");?>"></a></h2>
		        <form id="loginform" class="loginform" method="post" novalidate="novalidate" onSubmit="return false;">
		            <p class="input-item">
		                <input class="input-control" id="username" type="text" placeholder="用户名/邮箱" name="username" required="" aria-required="true">
		            </p>
		            <p class="input-item">
		                <input class="input-control" id="password" type="password" placeholder="密码" name="password" required="" aria-required="true">
		            </p>
		            <p class="sign-tips"></p>
		            <p class="input-submit">
		                <input class="submit login-loader btn" type="submit" value="登录">
		                <input type="hidden" name="action" value="login">
		                <input type="hidden" id="security" name="security" value="<?php echo  wp_create_nonce( 'security_nonce' );?>">
            			<input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
            			<input type="hidden" id="redirect_to" value="<?php echo $_GET['redirect_to']; ?>">
		            </p>
		            <p class="safe">
		                <a class="lostpwd-loader left" href="<?php echo add_query_arg('action','password',get_permalink(MBThemes_page('template/login.php')));?>">忘记密码？</a>
		                <?php if(!_MBT('register')){?><a class="signup-loader right" href="<?php echo add_query_arg('action','register',get_permalink(MBThemes_page('template/login.php')));?>">注册账号</a><?php }?>
		            </p>
		            <?php if(_MBT('oauth_qq') || _MBT('oauth_weibo') || _MBT('oauth_weixin')){?>
		            <div class="social-login sign-social">
		            	<div class="social-title"><span>使用第三方账号登录</span></div>
		                
	                	<?php if(_MBT('oauth_qq')){?>
	                	<a href="<?php bloginfo("url");?>/oauth/qq?rurl=<?php if(isset($_GET['redirect_to'])) echo $_GET['redirect_to'];else echo get_permalink(MBThemes_page('template/user.php'));?>" rel="nofollow" class="login-qq"><i class="icon icon-qq"></i>QQ登录</a>
	                	<?php }?>
	                	<?php if(_MBT('oauth_weibo')){?>
	                	<a href="<?php bloginfo("url");?>/oauth/weibo?rurl=<?php if(isset($_GET['redirect_to'])) echo $_GET['redirect_to'];else echo get_permalink(MBThemes_page('template/user.php'));?>" rel="nofollow" class="login-weibo"><i class="icon icon-weibo"></i>微博登录</a>
	                	<?php }?>
	                	<?php if(_MBT('oauth_weixin')){?>
	                	<a href="https://open.weixin.qq.com/connect/qrconnect?appid=<?php echo _MBT('oauth_weixinid');?>&redirect_uri=<?php bloginfo("url")?>/oauth/weixin/&response_type=code&scope=snsapi_login&state=MBT_weixin_login#wechat_redirect" rel="nofollow" class="login-weixin"><i class="icon icon-weixin"></i>微信登录</a>
	                	<?php }?>
		                
		            </div>
		            <?php }?>
		        </form>
		    </div>
		    <?php }?>
		</div>
	</section>
	<script type="text/javascript" src="<?php bloginfo("template_url");?>/static/js/login.js"></script>
</body>
</html>