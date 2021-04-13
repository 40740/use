<?php
/* 搜库资源网 soku.cc*
 * The template for displaying all single posts
 *
 * @link https:// 搜库资源网 soku.cc   developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package ziranzhi2
 */

get_header();
?>

<?php
	switch (get_post_type()) {
		// 搜库资源网 soku.cc   研究所
		case 'labs':
			get_template_part( 'template-parts/single', zrz_get_labs_terms('slugs'));
			break;
		// 搜库资源网 soku.cc   商城
		// 搜库资源网 soku.cc    case 'activity':
		// 搜库资源网 soku.cc    	get_template_part( 'modules/activity/single', 'activity');
		// 搜库资源网 soku.cc    	break;
		case 'shop':
			get_template_part( 'template-parts/single', 'shop');
			break;
		case 'announcement':
			get_template_part( 'template-parts/single', 'announcement');
			break;
		case 'pps':
			get_template_part( 'template-parts/single','bubble');
			break;
		default:
			get_template_part( 'template-parts/single', 'default');
			break;
	}

?>
