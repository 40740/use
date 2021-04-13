<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-09-29 13:18:37
 * @LastEditTime: 2021-01-07 15:50:05
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */
function zib_comments_list($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment;
	global $commentcount, $wpdb, $post;
	//echo esc_attr(json_encode($depth));
	zib_get_comments_list($comment, $depth);
}

function zib_get_comments_list($comment, $depth = 0, $echo = true)
{

	if (!$comment) return false;
	$user_id = $comment->user_id;
	$comment_id = $comment->comment_ID;

	$c_like = zib_get_comment_like('action action-comment-like pull-right muted-2-color', $comment_id);
	$vip_icon = '';
	if ($user_id) {
		$vip_icon = zibpay_get_vip_icon(zib_get_user_vip_level($user_id), "");
		$vip_icon = $vip_icon ? '<div class="avatar-icontag">' . $vip_icon . '</div>' : '';
	}

	$html = '';
	$html .= '';

	$html .= '<li ' . comment_class('', $comment_id, null, false) . ' id="comment-' . $comment_id . '">';
	$html .=  '<ul class="list-inline">';
	if (!$comment->comment_parent > 0) {
		$html .=  '<li>';
		$html .=  '<div class="comt-avatar relative">' . zib_get_data_avatar($user_id) . $vip_icon . '</div>';
		$html .=  '</li>';
	}
	$html .=  '<li class="comt-main" id="div-comment-' . $comment_id . '">';

	$con = zib_comment_filters($comment->comment_content);

	$author_link  = '<strong class="mr6">' . $comment->comment_author . '</strong>';
	if ($user_id) {
		$author_link = '<a href="' . get_author_posts_url($user_id) . '">' . $author_link . '</a>';

		if ($user_id ==  get_the_author_meta('ID') && _pz('comment_author_tag', true)) {
			$author_link  .= '<span class="badg c-red hollow badg-sm">作者</span>';
		}
	}
	if ($comment->comment_parent > 0) {
		$author_link = '<div class="comt-avatar-mini mr10 relative">' . zib_get_data_avatar($user_id) . $vip_icon . '</div>' . $author_link;
	}
	if ($comment->comment_approved == '0') {
		$author_link .= '<span class="badg c-red badg-sm ml6">待审核</span>';
	}

	$html .= '<div class="comt-avatar-name  mb10">' . $author_link . $c_like . '</div>';
	$html .= '<div class="mb10 comment-content">' . $con . '</div>';
	$html .= '<div class="comt-meta muted-2-color">';

	$html .= '<span class="comt-author" title="' . $comment->comment_date . '">';
	$html .= zib_get_time_ago($comment->comment_date);
	$html .= '</span>';

	if ($comment->comment_parent > 0) {
		$html .= '<span>@<a rel="nofollow" class="url" href="javascript:(scrollTo(\'#comment-' . $comment->comment_parent . '\',-70));">' . get_comment_author($comment->comment_parent) . '</a></span>';
	}

	$max_depth = get_option('thread_comments_depth');
	if ($comment->comment_approved !== '0' && $depth && !zib_is_close_sign()) {
		$replyText = get_comment_reply_link(array('add_below' => 'div-comment', 'reply_text' => '回复', 'login_text' => '回复', 'depth' => $depth, 'max_depth' => $max_depth), $comment->comment_ID);
		if (strstr($replyText, 'reply-login')) {
			$replyText =  preg_replace('# class="[\s\S]*?" href="[\s\S]*?"#', ' class="signin-loader" href="javascript:;"', $replyText);
		} else {
			$replyText =  preg_replace('# aria-label=#', ' data-toggle="tooltip" data-original-title=', $replyText);
		}
		$html .= '<span>' . $replyText . '</span>';
	}

	if ((_pz('user_edit_comment', 'true') && $user_id && $user_id == get_current_user_id()) || is_super_admin()) {
		$edit_but = '<a class="comment-edit-link" data-commentid="' . $comment->comment_ID . '" data-postid="' . $comment->comment_post_ID . '" href="javascript:;"><i class="fa fa-edit mr10 fa-fw" aria-hidden="true"></i>编辑</a>';
		$trash_but = '<a class="comment-trash-link" data-commentid="' . $comment->comment_ID . '" data-postid="' . $comment->comment_post_ID . '" href="javascript:;"><i class="fa fa-trash-o mr10 fa-fw" aria-hidden="true"></i>删除</a>';

		$list = '<li>' . $edit_but . '</li>';
		$list .= '<li>' . $trash_but . '</li>';

		$icon_a = '<a href="javascript:;" class="muted-color padding-6" data-toggle="dropdown">';
		$icon_a .= zib_svg('menu_2');
		$icon_a .= '</a>';

		$html .= '<span class="dropdown padding-6">' . $icon_a . '<ul class="dropdown-menu">' . $list . '</span>';
	}

	$html .= '</div>';
	$html .= '</li>';
	$html .= '</ul>';

	if ($echo) {
		echo $html;
	} else {
		return $html;
	}
}

function zib_comments_author_list($comment, $args = '')
{
	if (!$comment) return false;
	$cont =  zib_comment_filters($comment->comment_content, 'noimg');

	$_link = get_comment_link($comment->comment_ID);
	$post_title = get_the_title($comment->comment_post_ID);
	$post_tlink = get_the_permalink($comment->comment_post_ID);

	$time = $comment->comment_date;
	$approved = '';
	$parent = '';
	$post = '<a class="muted-color" href="' . $post_tlink . '">' . $post_title . '</a>';

	$cont = '<a class="muted-color text-ellipsis-5" href="' . $_link . '">' . $cont . '</a>';
	if ($comment->comment_parent > 0) {
		$parent = '<span class="mr10" >@' . get_comment_author($comment->comment_parent) . '</span>';
	}

	if ($comment->comment_approved == '0') {
		$approved = '<span class="badg c-red badg-sm mr6">待审核</span>';
	}

	$time = zib_get_time_ago($comment->comment_date);

	echo '<div class="list-inline">';
	echo '<div class="author-set-left muted-2-color" title="' . $comment->comment_date . '">';
	echo $time;
	echo '</div>';

	echo '<div class="author-set-right">';
	echo '<div class="mb10 comment-content">';
	echo $approved . $cont;

	echo '</div>';
	echo '<span class="muted-2-color em09">';
	echo $parent . '评论于：' . $post;

	echo '</span>';
	echo '</div>';

	echo '</div>';
}


function zib_widget_comments($limit, $outpost, $outer)
{
	global $wpdb;
	$args = array(
		'orderby' => 'comment_date',
		'number' => $limit,
		'status' => 'approve',
		'author__not_in' =>  preg_split("/,|，|\s|\n/", $outer),
		'post__not_in' =>  preg_split("/,|，|\s|\n/", $outpost),
	);

	$comments = get_comments($args);

	$output = '';
	foreach ($comments as $comment) {
		$cont =  zib_comment_filters($comment->comment_content, 'noimg');
		$_link = get_comment_link($comment->comment_ID);
		//$post_title = $comment->post_title;
		//$post_link = get_the_permalink($comment->ID);
		$time = zib_get_time_ago($comment->comment_date);
		$user_name = $comment->comment_author;
		$user_id = $comment->user_id;
		$c_like = zib_get_comment_like('action action-comment-like pull-right muted-2-color', $comment->comment_ID);
		$vip_icon = '';

		if ($user_id) {
			$user_name = '<a href="' . get_author_posts_url($user_id) . '">' . $user_name . '</a>';
			$vip_icon = zibpay_get_vip_icon(zib_get_user_vip_level($user_id), "");
			$vip_icon = $vip_icon ? '<div class="avatar-icontag">' . $vip_icon . '</div>' : '';
		}
		$avatar = '<div class="avatar-img">' . zib_get_data_avatar($user_id, '22') . $vip_icon . '</div>';


		echo '<div class="posts-mini">';
		echo $avatar;
		echo '<div class="comment-con em09">';
		echo '<p>';
		echo $user_name;
		echo '<span class="icon-spot muted-3-color" title="' . $comment->comment_date . '">' . $time . '</span>';
		echo '<span class="pull-right">' . $c_like . '</span>';
		echo '</p>';

		echo '<a class="muted-color text-ellipsis-5" href="' . $_link . '">' . $cont . '</a>';
		echo '</div>';
		echo '</div>';
	}
};

function zib_comment_filters($cont, $type = '')
{

	$cont =  preg_replace('/\[img=(.*?)\]/', '<img class="box-img lazyload" src="$1">', convert_smilies($cont));
	if ($type == 'noimg') {
		$cont =  preg_replace('/\<img(.*?)\>/', '[图片]', $cont);
		$cont =  preg_replace('/\[code]([\s\S]*)\[\/code]/', '[代码]', $cont);
	} else {
		$cont =  str_replace('[code]', '<pre><code>', $cont);
		$cont =  str_replace('[/code]', '</code></pre>', $cont);
	}

	$cont =  preg_replace('/\[g=(.*?)\]/', '<img class="smilie-icon" src="' . ZIB_STYLESHEET_DIRECTORY_URI . '/img/smilies/$1.gif">', $cont);
	if (_pz('lazy_comment')) {
		$cont =  str_replace(' src=', ' src="' . zib_default_thumb() . '" data-src=', $cont);
	}
	//
	return $cont;
}
