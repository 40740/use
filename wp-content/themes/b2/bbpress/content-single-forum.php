<?php

/* 搜库资源网 soku.cc*
 * Single Forum Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */
$forum_id = bbp_get_forum_id();
// 搜库资源网 soku.cc   分页
$count = (int)get_post_meta( $forum_id, '_bbp_topic_count', true );
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$per_page = (int)get_option( '_bbp_topics_per_page', 15 );
$pages = ceil( $count / $per_page);


// 搜库资源网 soku.cc   获取子分类
// 搜库资源网 soku.cc    $childs = bbp_forum_get_subforums($forum_id);
// 搜库资源网 soku.cc    $ids = array($forum_id);
// 搜库资源网 soku.cc    foreach($childs as $child){
// 搜库资源网 soku.cc    	$ids[] = $child->ID;
// 搜库资源网 soku.cc    }
// 搜库资源网 soku.cc    $bbp_loop_args = array('post_parent__in' => $ids,'post_parent'=>'any' ,'post_type' => 'topic');
?>

<div id="bbpress-forums" class="bbpress-wrapper">
	<div class="box">
		<?php echo zrz_bbp_forum_info(); ?>
		<?php 
			$ids = zrz_bbp_has_topics_query();
			$id = get_the_id();
			$role = true;
			if(isset($ids['post_parent__not_in']) && in_array($id,$ids['post_parent__not_in'])){
				$role = false;
			}
		if ( post_password_required() || !$role){

			bbp_get_template_part( 'form', 'protected' );
			$show = false;
			
			}else{
			$show = true;
		?>

			<?php if ( ! bbp_is_forum_category() && bbp_has_topics() ) : ?>

				<?php bbp_get_template_part( 'loop', 'topics' ); ?>

				<page-nav class="b-t" nav-type="bbp-home-<?php echo $forum_id; ?>" :paged="'<?php echo $paged; ?>'" :pages="'<?php echo $pages; ?>'" :locked-nav="1"></page-nav>

			<?php elseif ( ! bbp_is_forum_category() ) : ?>

				<?php bbp_get_template_part( 'feedback',   'no-topics' ); ?>

			<?php endif; ?>

		<?php } ?>
	</div>

<?php
	if($show){
		echo '<div class="mar10-t"></div>';
		bbp_get_template_part( 'form', 'topic' );
		do_action( 'bbp_template_after_single_forum' );
	}
?>
</div>
