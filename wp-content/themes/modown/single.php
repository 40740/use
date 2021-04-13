<?php get_header();$nosidebar = get_post_meta(get_the_ID(),'nosidebar',true);$video = get_post_meta(get_the_ID(),'video',true);$video_type = get_post_meta(get_the_ID(),'video_type',true);?>
<div class="main">
	<div class="container">
		<?php if (function_exists('MBThemes_breadcrumbs')) MBThemes_breadcrumbs(); ?>
		<div class="content-wrap">
	    	<div class="content<?php if($nosidebar) echo ' nosidebar';?>">
	    		<?php MBThemes_ad('ad_post_header');?>
	    		<?php 
	    			if($video){
	    				if($video_type){
	    					echo '<div class="single-video"><iframe src="'.$video.'" class="ckplayer-video"></iframe></div>';
	    				}else{
		    				$nonce = wp_create_nonce(rand(10,1000));
		    				echo '<div class="single-video"><div id="ckplayer-video-'.$nonce.'" class="ckplayer-video"></div><script type="text/javascript">
						        var videoObject'.$nonce.' = {
						            container:"#ckplayer-video-'.$nonce.'",
						            variable:"player",
						            autoplay:false,
						            video:"'.trim($video).'"
						        };
						        var player=new ckplayer(videoObject'.$nonce.');
						    </script></div>';
						}
	    			}
	    		?>
	    		<?php while (have_posts()) : the_post(); ?>
	    		<article class="single-content">
		    		<header class="article-header">
		    			<h1 class="article-title"><?php the_title(); ?></h1>
		    			<div class="article-meta">
		    				<?php if(_MBT('post_date')){?><span class="item"><i class="icon icon-time"></i> <?php echo MBThemes_timeago( get_the_time('Y-m-d G:i:s') ) ?></span><?php }?>
		    				<span class="item"><i class="icon icon-circle"></i> <?php $category = get_the_category(); ?><a href="<?php echo get_category_link($category[0]->term_id );?>"><?php echo $category[0]->cat_name;?></a></span>
		    				<?php if(_MBT('post_views')){?><span class="item"><i class="icon icon-eye"></i> <?php MBThemes_views() ?></span><?php }?>
		    				<?php $downtimes = get_post_meta(get_the_ID(),'down_times',true);
							if(_MBT('post_downloads')) echo '<span class="item"><i class="icon icon-download"></i> '.($downtimes?$downtimes:'0').'</span>';?>
		    				<span class="item"><?php edit_post_link('[编辑]'); ?></span>
		    			</div>
		    		</header>
		    		<div class="article-content">
		    			<?php if(wp_is_erphpdown_active()){ if(_MBT('down_position') == 'top' || _MBT('down_position') == 'sidetop') MBThemes_erphpdown_box();}?>
		    			<?php the_content(); ?>
		    			<?php wp_link_pages('link_before=<span>&link_after=</span>&before=<div class="article-paging">&after=</div>&next_or_number=number'); ?>
		    			<?php if(wp_is_erphpdown_active()){ if(_MBT('down_position') == 'bottom' || _MBT('down_position') == 'sidebottom' || _MBT('down_position') == 'side') MBThemes_erphpdown_box();}?>
		            </div>
		    		
		            <?php if(_MBT('post_tags')) the_tags('<div class="article-tags">','','</div>'); ?>
		            <div class="clearfix">
		            <?php if(_MBT('post_zan') || _MBT('post_collect') || _MBT('post_share_cover_img')){?>
		            	<div class="article-act">
		            	<?php if(_MBT('post_share_cover_img')){?>
		            		<a href="javascript:;" class="article-cover" title="分享卡片" data-s-id="<?php the_ID();?>"><i class="icon icon-cover"></i><span id="wx-thumb-qrcode" data-url="<?php the_permalink();?>"></span></a>
		            	<?php }?>
		            	<?php if(_MBT('post_collect')){?>
		            		<?php if(is_user_logged_in()){?>
		            			<?php if(MBThemes_check_collect(get_the_ID())){?>
		            			<a href="javascript:;" class="article-collect active" data-id="<?php the_ID();?>" title="已收藏"><i class="icon icon-star"></i></a>
		            			<?php }else{?>
		            			<a href="javascript:;" class="article-collect" data-id="<?php the_ID();?>" title="收藏"><i class="icon icon-star"></i></a>
		            			<?php }?>
		            		<?php }else{?>
		            			<a href="javascript:;" class="article-collect signin-loader" title="收藏"><i class="icon icon-star"></i></a>
		            		<?php }?>
		            	<?php }?>
		            	<?php if(_MBT('post_zan')){?>
		            		<a href="javascript:;" class="article-zan" data-id="<?php the_ID();?>" title="赞"><i class="icon icon-zan"></i> <span><?php echo MBThemes_get_zans(get_the_ID());?></span></a>
		            	<?php }?>
						</div>
					<?php }?>
					<?php if(_MBT('post_share')) echo '<div class="article-shares"><b>分享到：</b>
				        <a href="javascript:;" data-url="'. get_the_permalink() .'" class="share-weixin" title="分享到微信"><i class="icon icon-weixin"></i></a><a data-share="qzone" class="share-qzone" title="分享到QQ空间"><i class="icon icon-qzone"></i></a><a data-share="weibo" class="share-tsina" title="分享到新浪微博"><i class="icon icon-weibo"></i></a><a data-share="qq" class="share-sqq" title="分享到QQ好友"><i class="icon icon-qq"></i></a><a data-share="douban" class="share-douban" title="分享到豆瓣网"><i class="icon icon-douban"></i></a>
				    </div>';?>
					</div>
	            </article>
	            <?php endwhile;  ?>
	            <?php if(_MBT('post_nav')){?>
	            <nav class="article-nav">
	                <span class="article-nav-prev"><?php previous_post_link('上一篇<br>%link'); ?></span>
	                <span class="article-nav-next"><?php next_post_link('下一篇<br>%link'); ?></span>
	            </nav>
	            <?php }?>
	            <?php MBThemes_ad('ad_post_footer');?>
	            <?php if(_MBT('post_related')) get_template_part('module/related');?>
	            <?php comments_template('', true); ?>
	            <?php MBThemes_ad('ad_post_comment');?>
	    	</div>
	    </div>
		<?php if(!$nosidebar) get_sidebar(); ?>
	</div>
</div>
<?php get_footer();?>