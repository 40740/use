<?php 
/*
	template name: VIP介绍
	description: template for mobantu.com modown theme 
*/
get_header();
$ciphp_quarter_price = get_option('ciphp_quarter_price');
$ciphp_month_price  = get_option('ciphp_month_price');
$ciphp_day_price  = get_option('ciphp_day_price');
$ciphp_year_price    = get_option('ciphp_year_price');
$ciphp_life_price  = get_option('ciphp_life_price');
?>
<style>
    body{background: #fff !important}
	.main{padding-top:0}
	.content-wrap{margin:0;float: none;}
	.single-content{padding:0;margin:0;}
	.article-title{margin-top:100px;margin-bottom:50px;font-size:28px}
	.article-content{margin-bottom:0}
	.vip-desc{text-align: center;font-size: 14px;color:#8a92a9;margin-top: -40px;margin-bottom: 40px;}
</style>
<div class="main">
	<div class="container">
		<div class="content-wrap clearfix">
	    	<div class="content">
	    		<?php while (have_posts()) : the_post(); ?>
	    		<article class="single-content">
	    			<header class="article-header">
		    			<h1 class="article-title center"><?php the_title(); ?></h1>
		    			<div class="vip-desc"><?php echo _MBT('vip_desc');?></div>
		    		</header>
		    		<div class="vip-content clearfix">
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
		    		<?php endwhile;  ?>
	            </article>
	    	</div>
	    </div>
	</div>
	<?php if(_MBT('vip_why')) get_template_part("module/why");?>
	<!--div class="vip-faqs">
		<div class="container">
			<div class="items">
				<h4>常见问题</h4>
				<div class="item">
					<h5>可以商用吗？</h5>
					<p>不可以～～～</p>
				</div>
				<div class="item">
					<h5>可以商用吗？</h5>
					<p>不可以～～～</p>
				</div>
				<div class="item">
					<h5>可以商用吗？</h5>
					<p>不可以～～～</p>
				</div>
			</div>
		</div>
	</div-->
</div>
<?php get_footer();?>