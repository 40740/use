<div class="post list"><?php global $post_target;?>
  <div class="img"><a href="<?php the_permalink();?>" title="<?php the_title();?>" target="<?php echo $post_target;?>" rel="bookmark">
    <img <?php echo (_MBT('lazyload'))?'data-src':'src';?>="<?php echo MBThemes_thumbnail();?>" class="thumb" alt="<?php the_title();?>">
  </a></div>
  <div class="con">
	  <h3 itemprop="name headline"><a itemprop="url" rel="bookmark" href="<?php the_permalink();?>" title="<?php the_title();?>" target="<?php echo $post_target;?>"><?php the_title();?></a></h3>
	  <p class="desc"><?php echo MBThemes_get_excerpt(180);?></p>
	  <div class="list-meta">
	    <?php if(_MBT('post_date')){?><span class="time"><i class="icon icon-time"></i> <?php echo MBThemes_timeago( get_gmt_from_date(get_the_time('Y-m-d G:i:s')) ) ?></span><?php }?><?php if(_MBT('post_views')){?><span class="views"><i class="icon icon-eye"></i> <?php MBThemes_views();?></span><?php }?><?php if(_MBT('post_comments')){?><span class="comments"><i class="icon icon-comment"></i> <?php echo get_comments_number('0', '1', '%');?></span><?php }?>
	  </div>
  </div>
</div>