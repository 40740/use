<link rel="stylesheet" href="<?php bloginfo('template_url');?>/static/css/swiper.min.css">
<?php if(_MBT('slider_fullwidth')){?>
  <style>
  <?php if(_MBT('header_type') == 'default'){?>
  body.home .swiper-container{margin-top: -70px;}
  <?php }?>
  .swiper-container .swiper-slide{background-color:#2d3757;height: 400px !important;padding-top:110px;background-image:url(../img/banner.jpg);background-position:center center;background-size:cover;background-repeat:no-repeat;position:relative;text-align: center;}
  .swiper-container .swiper-slide .container{z-index:10;position: relative;}
  .swiper-container .swiper-slide h2{font-size:35px;font-weight:700;margin-bottom:20px;color:#fff;}
  .swiper-container .swiper-slide p{margin-top:20px;font-size:18px;font-weight:700;color: #fff}
  .swiper-container .swiper-slide a{border:1px solid #fff;color:#1d1d1d;background:#fff;font-size:18px;border-radius:3px;display:inline-block;padding:10px 36px;margin-top:40px;width:auto;}
  .swiper-container .swiper-slide img{width:100%;height: auto;}
  @media (max-width: 768px){
    <?php if(_MBT('header_type') == 'default'){?>
    body.home .swiper-container{margin-top: -60px;margin-bottom: 15px;}
    <?php }?>
    .swiper-container .swiper-slide{padding-top: 100px;height: 200px !important;}
    .swiper-container .swiper-slide h2{font-size:24px;}
    .swiper-container .swiper-slide p{margin-top:18px;}
  }
  </style>
  <div class="swiper-container">
      <div class="swiper-wrapper">
        <?php 
          $sort = '1 2 3 4 5';
          $sort = array_unique(explode(' ', trim($sort)));
          $i = 0;
          foreach ($sort as $key => $value) {
              if( _MBT('slider_img'.$value) ){
        ?>
          <div class="swiper-slide" style="background-image: url(<?php echo _MBT('slider_img'.$value);?>);">
        <div class="container">
            <h2><?php echo _MBT('slider_title'.$value);?></h2>
              <p><?php echo _MBT('slider_desc'.$value);?></p>
              <?php if(_MBT('slider_btn'.$value)){?><a href="<?php echo _MBT('slider_link'.$value);?>" target="_blank"><?php echo _MBT('slider_btn'.$value);?></a><?php }?>
          </div>
          </div>
        <?php }}?>
      </div>
      <div class="swiper-pagination"></div>
  </div>
  <script src="<?php bloginfo('template_url');?>/static/js/swiper.min.js"></script>
  <script>
      var swiper = new Swiper('.swiper-container', {
        slidesPerView: '1',
        autoHeight: true,
        autoplay: {
          delay: 4000,
          disableOnInteraction: false,
        },
        pagination: {
          el: '.swiper-pagination',
          dynamicBullets: false,
          clickable: true,
        },
      });
  </script>
<?php }else{?>
  <div class="banner banner-slider">
    <div class="container">
      <div class="<?php if(_MBT('slider_right')) echo 'slider-left'; else echo 'slider-full';?>">
        <div class="swiper-container">
            <div class="swiper-wrapper">
            	<?php 
            		$sort = '1 2 3 4 5';
        		    $sort = array_unique(explode(' ', trim($sort)));
        		    $i = 0;
        		    foreach ($sort as $key => $value) {
        		        if( _MBT('slider_img'.$value) ){
            	?>
                <div class="swiper-slide">
        		      <a href="<?php echo _MBT('slider_link'.$value);?>" target="_blank">
                  <img src="<?php echo _MBT('slider_img'.$value);?>" alt="<?php echo _MBT('slider_title'.$value);?>" title="<?php echo _MBT('slider_title'.$value);?>">
                  </a>
                </div>
            	<?php }}?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
        <script src="<?php bloginfo('template_url');?>/static/js/swiper.min.js"></script>
        <script>
            var swiper = new Swiper('.swiper-container', {
              slidesPerView: '1',
              autoHeight: true,
              autoplay: {
                delay: 4000,
                disableOnInteraction: false,
              },
              pagination: {
                el: '.swiper-pagination',
                dynamicBullets: false,
                clickable: true,
              },
            });
        </script>
      </div>
      <?php if(_MBT('slider_right')){?>
      <div class="slider-right">
        <div class="item">
          <a href="<?php echo _MBT("slider_right_link1");?>" target="_blank"><img src="<?php echo _MBT("slider_right_img1");?>"></a>
        </div>
        <div class="item">
          <a href="<?php echo _MBT("slider_right_link2");?>" target="_blank"><img src="<?php echo _MBT("slider_right_img2");?>"></a>
        </div>
      </div>
      <?php }?>
    </div>
  </div>
<?php }?>