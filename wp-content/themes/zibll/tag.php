<?php
/*
 * @Author: Qinver
 * @Url: zibll.com
 * @Date: 2020-12-12 15:58:40
 * @LastEditTime: 2020-12-16 21:14:14
 */
	get_header();
?>
<?php if (function_exists('dynamic_sidebar')) {
	echo '<div class="container fluid-widget">';
	dynamic_sidebar('all_top_fluid');
	dynamic_sidebar('cat_top_fluid');
	echo '</div>';
}
?>
<main role="main" class="container">
	<div class="content-wrap">
		<div class="content-layout">
			<?php if (function_exists('dynamic_sidebar')) {
				dynamic_sidebar('tag_top_content');
			}
			?>
			<?php
			zib_tag_cover();
			echo '<div class="ajaxpager">';
			zib_ajax_option_menu('tag');
			zib_posts_list();
			zib_paging();
			echo '</div>';
			?>
			<?php if (function_exists('dynamic_sidebar')) {
				dynamic_sidebar('tag_bottom_content');
			}
			?>
		</div>
	</div>
	<?php get_sidebar(); ?>
</main>
<?php if (function_exists('dynamic_sidebar')) {
	echo '<div class="container fluid-widget">';
	dynamic_sidebar('tag_bottom_fluid');
	dynamic_sidebar('all_bottom_fluid');
	echo '</div>';
}
?>
<?php get_footer(); ?>
