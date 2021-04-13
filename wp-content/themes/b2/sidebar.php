<?php
/* 搜库资源网 soku.cc*
 * The sidebar containing the main widget area
 *
 * @link https:// 搜库资源网 soku.cc   developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ziranzhi2
 */

?><aside id="secondary" class=" widget-area fd mobile-full-width">
	<div class="widget-area-in">
		<?php
			if(zrz_get_page(false,'write')){
				get_template_part( 'sidebar/sidebar','write');
			}elseif(zrz_get_page(false,'add-labs')){
				get_template_part( 'sidebar/sidebar','add-labs');
			}elseif(zrz_get_page(false,'directmessage')){
				get_template_part( 'sidebar/sidebar','directmessage');
			}elseif(zrz_get_page(false,'gold')){
				get_template_part( 'sidebar/sidebar','gold');
			}elseif(zrz_get_page(false,'top')){
				get_template_part( 'sidebar/sidebar','top');
			}elseif(zrz_get_page(false,'vips')){
				get_template_part( 'sidebar/sidebar','vips');
			}elseif(zrz_get_page(false,'notifications')){
				get_template_part( 'sidebar/sidebar','notifications');
			}elseif(zrz_get_page(false,'hot')){
				get_template_part( 'sidebar/sidebar','hot');
			}elseif(zrz_get_page(false,'task')){
				get_template_part( 'sidebar/sidebar','task');
			}elseif(zrz_get_labs_terms('slugs') == 'task'){
				get_template_part( 'sidebar/sidebar','relay');
			}elseif(is_author()){
				get_template_part( 'sidebar/sidebar','author');
			}elseif(zrz_is_custom_tax('shop')){
				dynamic_sidebar( 'sidebar-3' );
			}elseif(zrz_is_custom_tax('bbpress')){
				dynamic_sidebar( 'sidebar-4' );
			}elseif(zrz_is_custom_tax('bubble')){
				dynamic_sidebar( 'sidebar-5' );
			}elseif(is_singular('post')){
				dynamic_sidebar( 'sidebar-2' );
			}elseif(is_page()){
				dynamic_sidebar( 'sidebar-7' );
			}else{
				dynamic_sidebar( 'sidebar-1' );
			}
		?>
	</div>
</aside><!-- #secondary -->
