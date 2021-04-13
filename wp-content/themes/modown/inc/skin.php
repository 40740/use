<style>
  <?php 
  MBThemes_color(); 
  
  $timthumb_height = _MBT('timthumb_height');
  if(is_category()){
    $cat_ID = get_query_var('cat');
    $timthumb_height_cat = get_term_meta($cat_ID,'timthumb_height',true);
    if($timthumb_height_cat){
      $timthumb_height = $timthumb_height_cat;
    }
  }
  if($timthumb_height && $timthumb_height != '180'){
  ?>
  .grids .grid .img{height: <?php echo $timthumb_height;?>px;}
  .widget-postlist .hasimg li{padding-left: calc(<?php echo ($timthumb_height=="285")?"66":(66*285/$timthumb_height);?>px + 10px);}
  .widget-postlist .hasimg li .img{width:<?php echo ($timthumb_height=="285")?"66":(66*285/$timthumb_height);?>px;}
  @media (max-width: 1230px){
    .grids .grid .img{height: <?php echo ($timthumb_height=="285")?"232.5":(232.5*$timthumb_height/285);?>px;}
  }
  @media (max-width: 1024px){
    .grids .grid .img{height: <?php echo $timthumb_height;?>px;}
  }
  @media (max-width: 925px){
    .grids .grid .img{height: <?php echo ($timthumb_height=="285")?"232.5":(232.5*$timthumb_height/285);?>px;}
  }
  @media (max-width: 768px){
    .grids .grid .img{height: <?php echo $timthumb_height;?>px;}
  }
  @media (max-width: 620px){
    .grids .grid .img{height: <?php echo ($timthumb_height=="285")?"232.5":(232.5*$timthumb_height/285);?>px;}
  }
  @media (max-width: 480px){
    .grids .grid .img{height: <?php echo ($timthumb_height=="285")?"180":(180*$timthumb_height/285);?>px;}
  }
  <?php
  }

  if(_MBT('header_type') == 'dark'){
    $header_txtcolor = _MBT('header_txtcolor')?_MBT('header_txtcolor'):'#fff';
  ?>
  .header{background: #1c1f2b}
  .nav-main > li, .nav-main > li > a, .nav-right a{color:<?php echo $header_txtcolor;?>;}
  @media (max-width: 768px){
    .nav-right .nav-button a {color: <?php echo $header_txtcolor;?>;}
  }
  <?php
  }elseif(_MBT('header_type') == 'light'){
    $header_txtcolor = _MBT('header_txtcolor')?_MBT('header_txtcolor'):'#062743';
  ?>
    .nav-main > li, .nav-main > li > a, .nav-right a{color:<?php echo $header_txtcolor;?>;}
    @media (max-width: 768px){
      .nav-right .nav-button a {color: <?php echo $header_txtcolor;?>;}
    }
  <?php
  }elseif(_MBT('header_type') == 'custom'){
    $header_bgcolor = _MBT('header_bgcolor');
    $header_txtcolor = _MBT('header_txtcolor')?_MBT('header_txtcolor'):'#062743';

    $theme_color_custom = _MBT('theme_color_custom');
    $theme_color = _MBT('theme_color');
    $color = '';
    if($theme_color && $theme_color != '#ff5f33'){
     $color = $theme_color;
    }
    if($theme_color_custom && $theme_color_custom != '#ff5f33'){
     $color = $theme_color_custom;
    }
  ?>
  .header{background: <?php echo $header_bgcolor;?>}
  .nav-main > li, .nav-main > li > a, .nav-right a{color:<?php echo $header_txtcolor;?>;}
  <?php if($color == $header_bgcolor){?>
  body.home .header:not(.scrolled) .nav-main > li > a:hover, body.home .header:not(.scrolled) .nav-right > li > a:hover, .nav-main > li > a:hover, .nav-right a:hover{color:<?php echo $header_txtcolor;?>;}
  <?php }?>
  @media (max-width: 768px){
    .nav-right .nav-button a {color: <?php echo $header_txtcolor;?>;}
  }
  <?php
  }else{
    if( _MBT('banner') == '1' || (_MBT('banner') == '2' && _MBT('slider_fullwidth')) ){
      $header_color = _MBT('header_color')?_MBT('header_color'):'#fff';
    ?>
    body.home .banner{margin-top: -70px;}
    body.home .banner-slider{padding-top: 90px;background: #fff;background-image: none;}
    body.home .banner-slider:after{content: none;}
    body.home .header{background: transparent;box-shadow: none;webkit-box-shadow:none;}
    body.home .header.scrolled{background: #fff;webkit-box-shadow: 0px 5px 10px 0px rgba(17, 58, 93, 0.1);-ms-box-shadow: 0px 5px 10px 0px rgba(17, 58, 93, 0.1);box-shadow: 0px 5px 10px 0px rgba(17, 58, 93, 0.1);}

    body.home .header:not(.scrolled) .nav-main > li, body.home .header:not(.scrolled) .nav-main > li > a, body.home .header:not(.scrolled) .nav-right > li > a{color:<?php echo $header_color;?>;}

    @media (max-width: 925px){
      body.home .banner{padding-top: 85px;}
      body.home .banner-slider{padding-top: 85px;}
    }

    @media (max-width: 768px){
      body.home .banner{margin-top: -60px;padding-top: 70px;}
      body.home .banner-slider{padding-top: 70px;}
    }
    <?php 
    }
  }

  if(_MBT('banner_dark')){
  ?>
  body.home .banner:after, .banner.banner-archive:after, body.home .swiper-container .swiper-slide:after, .mocats .moli .moli-header:after{content:"";position:absolute;top:0;bottom:0;left:0;right:0;background:rgba(0,0,0,.5);z-index:9}
  <?php
  }

  if(_MBT('list_column') == 'six' && _MBT('list_style') != 'list'){
  ?>
    .container{max-width:1810px;}
    .slider-left{max-width: 1505px;}
    @media (max-width:1840px){
      .container{max-width:1505px;}
      .modown-ad .item:nth-child(6){display: none;}
      .modown-ad .item:nth-child(5){margin-right: 0}
    }
    @media (max-width:1535px){
      .modown-ad .item:nth-child(5){display: none;}
      .modown-ad .item:nth-child(4){margin-right: 0}
    }
  <?php
  }

  if(_MBT('list_column') == 'five' && _MBT('list_style') != 'list'){
  ?>
    .container{max-width:1505px;}
    .slider-left{max-width: 1200px;}
    @media (max-width:1535px){
      .modown-ad .item:nth-child(5){display: none;}
      .modown-ad .item:nth-child(4){margin-right: 0}
    }
  <?php
  }

    if(_MBT('post_title')){
  ?>
    .grids .grid h3 a, .mocat .lists .grid h3 a{height: 40px;-webkit-line-clamp:2;}
  <?php
  }

  if(_MBT('header_fullwidth')){
    echo '.header .container{max-width:none !important;padding:0 15px;}';
  }

  if(_MBT('logo_width')){
    echo '.logo{width:'._MBT('logo_width').'px;}';
    echo '@media (max-width: 1024px){.logo, .logo a {width: '._MBT('logo_width_wap').'px;height: 60px;}}';
  }

  if(_MBT('header_vip_wap')){
    echo '@media (max-width: 768px){.nav-right .nav-vip{display: none;}}';
  }

  if(_MBT('footer_widget_num') == '4'){
    echo '.footer-widget{width:25%;}@media (max-width: 768px){.footer-widget{width:50%;}}';
  }elseif(_MBT('footer_widget_num') == '3'){
    echo '.footer-widget{width:33.3333%;}@media (max-width: 480px){.footer-widget{width:100%;}}';
  }elseif(_MBT('footer_widget_num') == '2'){
    echo '.footer-widget{width:50%;}@media (max-width: 768px){.footer-widget{width:50%;}}@media (max-width: 480px){.footer-widget{width:100%;}}';
  }

  echo _MBT('css');

  ?>
</style>