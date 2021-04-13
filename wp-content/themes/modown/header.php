<!DOCTYPE HTML>
<html itemscope="itemscope" itemtype="http://schema.org/WebPage">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
<meta name="apple-mobile-web-app-title" content="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
<meta http-equiv="Cache-Control" content="no-siteapp">
<title><?php wp_title( _MBT('delimiter','-'), true, 'right' ); ?></title>
<?php MBThemes_keywords();MBThemes_description();?>
<link rel="shortcut icon" href="<?php echo _MBT('favicon')?>">
<?php wp_head();?>
<!--[if lt IE 9]><script src="<?php bloginfo('template_url') ?>/static/js/html5.min.js"></script><![endif]-->
<script>window._MBT = {uri: '<?php bloginfo('template_url') ?>', url:'<?php bloginfo('url');?>',usr: '<?php echo get_permalink(MBThemes_page("template/user.php"));?>', roll: [<?php echo _MBT('sidebar_fixed');?>], admin_ajax: '<?php echo admin_url('admin-ajax.php');?>', erphpdown: '<?php if(defined("erphpdown")) echo constant("erphpdown");?>', image: '<?php if(_MBT('timthumb_height')) echo round(_MBT('timthumb_height')/285,4);else echo '0.6316';?>'}</script>
<?php get_template_part("inc/skin");?>
</head>
<body <?php body_class(); ?>>
<header class="header">
  <div class="container clearfix">
  	<?php $logoTagName = is_home() ? 'h1' : 'div';?>
    <?php echo '<'.$logoTagName;?> class="logo"><a <?php if(_MBT('logo')) echo 'style="background-image:url('._MBT('logo').')"';?> href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></a></<?php echo $logoTagName.'>';?>
    <ul class="nav-main">
      <?php echo str_replace("</ul></div>", "", preg_replace("{<div[^>]*><ul[^>]*>}", "", wp_nav_menu(array('theme_location' => 'main', 'echo' => false, 'fallback_cb'=> 'wp_menu_none')) )); ?>
    </ul>
    <ul class="nav-right">
      <?php if(_MBT('header_vip')){?>
      <li class="nav-vip">
        <a href="<?php echo get_permalink(MBThemes_page("template/vip.php"));?>"><i class="icon icon-vip-s"></i></a>
      </li>
      <?php }?>
      <?php if(_MBT('header_tougao')){?>
      <li class="nav-tougao">
        <a href="<?php echo get_permalink(MBThemes_page("template/tougao.php"));?>" title="投稿"><i class="icon icon-edit"></i></a>
      </li>
      <?php }?>
      <li class="nav-search">
        <a href="javascript:;" class="search-loader" title="搜索"><i class="icon icon-search"></i></a>
      </li>
      <?php if(!is_user_logged_in()){?>
      <li class="nav-login no"><a href="<?php echo get_permalink(MBThemes_page("template/login.php"));?>" class="signin-loader"><i class="icon icon-user"></i><span>登录</span></a><?php if(!_MBT('register')){?><b class="nav-line"></b><a href="<?php echo get_permalink(MBThemes_page("template/login.php"));?>?action=register" class="signup-loader"><span>注册</span></a><?php }?></li>
      <?php }else{ global $current_user;?>
      <li class="nav-login yes"><a href="<?php echo get_permalink(MBThemes_page("template/user.php"));?>"><?php echo get_avatar($current_user->ID,36);?><i class="icon icon-user"></i><?php if(wp_is_erphpdown_active()){ if(getUsreMemberTypeById($current_user->ID)) echo '<span class="vip"></span>'; }?></a>
        <ul class="sub-menu">
          <?php if(wp_is_erphpdown_active()){ $okMoney = erphpGetUserOkMoney(); $userTypeId=getUsreMemberType();?>
          <li>
            <a class="money"><?php echo '<i class="icon icon-ticket"></i> '.sprintf("%.2f",$okMoney);?>
            <?php if($userTypeId>0&&$userTypeId<10){
              echo '<div class="vip-endtime">'.getUsreMemberTypeEndTime().'到期</div>';
            }elseif($userTypeId == 10){
              echo '<div class="vip-endtime">终身尊享VIP</div>';
            }
            ?>
            </a>
          </li>
          <?php if(current_user_can('administrator')){?>
          <li><a href="<?php echo admin_url();?>"><i class="icon icon-setting"></i> 后台管理</a></li>
          <?php }?>
          <li><a href="<?php echo get_permalink(MBThemes_page("template/user.php"));?>"><i class="icon icon-money"></i> 在线充值</a></li>
          <li><a href="<?php echo add_query_arg('action','order',get_permalink(MBThemes_page("template/user.php")));?>"><i class="icon icon-order"></i> 购买清单</a></li>
          <?php }?>
          <li><a href="<?php echo add_query_arg('action','info',get_permalink(MBThemes_page("template/user.php")));?>"><i class="icon icon-info"></i> 我的资料</a></li>
          <li><a href="<?php echo wp_logout_url(MBThemes_selfURL());?>"><i class="icon icon-signout"></i> 安全退出</a></li>
        </ul>
      </li>
      <?php }?>
      <li class="nav-button"><a href="javascript:;" class="nav-loader"><i class="icon icon-menu"></i></a></li>
    </ul>
  </div>
</header>
<div class="search-wrap">
  <div class="container">
    <form action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search-form" method="get">
      <input autocomplete="off" class="search-input" name="s" placeholder="输入关键字回车" type="text">
      <i class="icon icon-close"></i>
    </form>
  </div>
</div>