<?php
/* 搜库资源网 soku.cc*
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https:// 搜库资源网 soku.cc   codex.wordpress.org/Template_Hierarchy
 *
 * @package ziranzhi2
 */

get_header();

global $wp_query;
$nub = get_option('posts_per_page',18);
$ipaged = get_query_var('paged') ? get_query_var('paged') : 1;
$ipages = ceil( $wp_query->found_posts / $nub);
$show_sidebar = zrz_get_theme_settings('show_sidebar');
?>

<?php do_action( 'home_before_7b2' ); ?>

<div id="primary-home" class="content-area fd mobile-full-width <?php echo $show_sidebar ? '' : 'w100'; ?>" ref="primaryHome" data-paged="<?php echo $ipaged; ?>" data-pages="<?php echo $ipages; ?>">
	<main id="main" class="site-main grid clearfix pos-r arc" ref="grid">

	<?php do_action( 'home_loop_before_7b2' ); ?>

	<?php
	$i = 0;
	$mobile = zrz_wp_is_mobile();
	$count = zrz_get_theme_settings('pinterest_count');
	if ( have_posts() ) :

		$theme_style = zrz_get_theme_style();

		// 搜库资源网 soku.cc    $open_ads_list = zrz_get_ads_settings('home_list');
		// 搜库资源网 soku.cc    $open_ads_card = zrz_get_ads_settings('home_card');
		// 搜库资源网 soku.cc   
		// 搜库资源网 soku.cc    $open_ads_list = $open_ads_list['open'] && $theme_style === 'list' ? 1 : 0;
		// 搜库资源网 soku.cc    $open_ads_card = $open_ads_card['open'] && $theme_style === 'pinterest' ? 1 : 0;

		echo '<div ref="postList" class="'.($theme_style === 'pinterest' ? 'grid-bor' : '').'">';
		/* 搜库资源网 soku.cc Start the Loop */
		while ( have_posts() ) : the_post();

			if($i == $count*2){
				do_action( 'home_loop_middle_7b2' );
			}
			
			get_template_part( 'formats/content',get_post_format());

			$i++;
		endwhile;

		echo '</div><page-nav class="box" :nav-type="index" :paged="paged" :pages="pages" :show-type="\'p\'"></page-nav>';

	else :

		get_template_part( 'template-parts/content', 'none' );

	endif; ?>

	<?php do_action( 'home_loop_after_7b2' ); ?>

	</main><!-- #main -->
</div><?php do_action( 'home_after_7b2' ); ?><?php
if($show_sidebar){
	get_sidebar(); 
}
get_footer();
