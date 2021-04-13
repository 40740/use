<?php 
	$ciphp_quarter_price = get_option('ciphp_quarter_price');
	$ciphp_month_price  = get_option('ciphp_month_price');
	$ciphp_day_price  = get_option('ciphp_day_price');
	$ciphp_year_price    = get_option('ciphp_year_price');
	$ciphp_life_price  = get_option('ciphp_life_price');
?>
<div class="vip-content clearfix">
    <div class="container">
        <h2><span><?php echo _MBT("home_vip_title","关于VIP");?></span></h2>
    	<?php if($ciphp_day_price){?>
        <div class="vip-item item-0">
            <h6>体验VIP</h6>
            <span class="price"><?php echo $ciphp_day_price;?><small><?php echo get_option('ice_name_alipay');?></small></span>
            <p class="border-decor"><span>1天</span></p>
            <?php echo _MBT('vip_day');?>
            <?php if(is_user_logged_in()){?>
            <a href="javascript:;" data-type="6" class="btn btn-small btn-vip-action">立即升级</a>
        	<?php }else{?>
        	<a href="javascript:;" class="btn btn-small signin-loader">立即升级</a>
        	<?php }?>
        </div>  
    	<?php }?>

    	<?php if($ciphp_month_price){?>
        <div class="vip-item item-1">
            <h6>包月VIP</h6>
            <span class="price"><?php echo $ciphp_month_price;?><small><?php echo get_option('ice_name_alipay');?></small></span>
            <p class="border-decor"><span>1个月</span></p>
            <?php echo _MBT('vip_month');?>
            <?php if(is_user_logged_in()){?>
            <a href="javascript:;" data-type="7" class="btn btn-small btn-vip-action">立即升级</a>
        	<?php }else{?>
        	<a href="javascript:;" class="btn btn-small signin-loader">立即升级</a>
        	<?php }?>
        </div>  
    	<?php }?>

    	<?php if($ciphp_quarter_price){?>
        <div class="vip-item item-2">
            <h6>包季VIP</h6>
            <span class="price"><?php echo $ciphp_quarter_price;?><small><?php echo get_option('ice_name_alipay');?></small></span>
            <p class="border-decor"><span>3个月</span></p>
            <?php echo _MBT('vip_quarter');?>
            <?php if(is_user_logged_in()){?>
            <a href="javascript:;" data-type="8" class="btn btn-small btn-vip-action">立即升级</a>
        	<?php }else{?>
        	<a href="javascript:;" class="btn btn-small signin-loader">立即升级</a>
        	<?php }?>
        </div>  
    	<?php }?>

    	<?php if($ciphp_year_price){?>
        <div class="vip-item item-3">
            <h6>包年VIP</h6>
            <span class="price"><?php echo $ciphp_year_price;?><small><?php echo get_option('ice_name_alipay');?></small></span>
            <p class="border-decor"><span>12个月</span></p>
            <?php echo _MBT('vip_year');?>
            <?php if(is_user_logged_in()){?>
            <a href="javascript:;" data-type="9" class="btn btn-small btn-vip-action">立即升级</a>
        	<?php }else{?>
        	<a href="javascript:;" class="btn btn-small signin-loader">立即升级</a>
        	<?php }?>
        </div>  
    	<?php }?>

    	<?php if($ciphp_life_price){?>
        <div class="vip-item item-4">
            <h6>终身VIP</h6>
            <span class="price"><?php echo $ciphp_life_price;?><small><?php echo get_option('ice_name_alipay');?></small></span>
            <p class="border-decor"><span>永久</span></p>
            <?php echo _MBT('vip_life');?>
            <?php if(is_user_logged_in()){?>
            <a href="javascript:;" data-type="10" class="btn btn-small btn-vip-action">立即升级</a>
        	<?php }else{?>
        	<a href="javascript:;" class="btn btn-small signin-loader">立即升级</a>
        	<?php }?>
        </div>  
    	<?php }?>
    </div>

</div>