<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-11-11 17:11:44
 * @LastEditTime: 2020-12-21 18:14:04
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */


/**用户评论通过审核之后 */
add_action('comment_unapproved_to_approved', 'zib_newmsg_comment_approved', 99);
function zib_newmsg_comment_approved($comment)
{

    if (!$comment->user_id) return;
    $_link = get_comment_link($comment->comment_ID);
    $post = get_post($comment->comment_post_ID);
    $post_title = zib_str_cut($post->post_title, 0, 16, '...');

    $post_link = get_permalink($comment->comment_post_ID);

    $title = '您发表的评论已通过审核:文章[' . $post_title . ']';
    $comment_content =  preg_replace('/\[img=(.*?)\]/', '<img class="box-img lazyload" src="$1">', convert_smilies($comment->comment_content));
    $comment_content =  preg_replace('/\[g=(.*?)\]/', '<img class="smilie-icon" src="' . ZIB_STYLESHEET_DIRECTORY_URI . '/img/smilies/$1.gif">', $comment_content);

    $message = '您好！' . $comment->comment_author . '</br>';
    $message .= '您在文章[<a class="muted-color" href="' . esc_url($post_link) . '">' . $post->post_title . '</a>]中的评论，已经通过审核' . '</br>';
    $message .= '评论内容：' . '</br>';
    $message .= '<div style="padding: 10px 15px; border-radius: 8px; background: rgb(141 141 141 / 0.05); line-height: 1.7;">' . $comment_content  . '</div>';
    $message .= '评论时间：' . $comment->comment_date . '</br>';
    $message .= '</br>';

    $message .= '您可以点击下方按钮查看评论</br>';
    $message .= '<a target="_blank" style="margin-top: 20px;padding:5px 20px" class="but jb-blue" href="' . esc_url($_link) . '">查看评论</a>' . "</br>";

    $msg_arge = array(
        'send_user' => 'admin',
        'receive_user' => $comment->user_id,
        'type' => 'comment',
        'title' => $title,
        'content' => $message,
        'meta' => '',
        'other' => '',
    );
    //创建新消息
    if (zib_msg_is_allow_receive($comment->user_id, 'comment')) {
        ZibMsg::add($msg_arge);
    }
    wp_new_comment_notify_postauthor($comment->comment_ID);
}

/**当投稿的文章从草稿状态变更到已发布时 */
add_action('draft_to_publish', 'zib_newmsg_draft_to_publish', 99);
function zib_newmsg_draft_to_publish($post)
{
    $user_id = $post->post_author;
    //用户是否接收
    /**判断是否登录后投稿 */
    if (!$user_id || $user_id == _pz('post_article_limit', 1) || !zib_msg_is_allow_receive($user_id, 'posts')) return;
    $udata = get_userdata($user_id);
    /**判断是否是管理员或者作者 */
    if (in_array('administrator', $udata->roles) || in_array('roles', $udata->roles)) {
        return false;
    }
    $_link = get_permalink($post->ID);
    $post_title = zib_str_cut($post->post_title, 0, 20, '...');

    $title = '您投稿的文章已通过审核：文章[' . $post_title . ']';

    $message = '您好！' . $udata->display_name . '</br>';
    $message .= '您投稿的文章[' . $post->post_title . ']，已经通过审核' . '</br>';
    $message .= '内容摘要：</br>';;
    $message .= '<div style=" padding: 10px 15px; border-radius: 8px; background: #f5f7f9; line-height: 1.7;">' . zib_str_cut(trim(strip_tags($post->post_content)), 0, 200, '...') . '</div>';
    $message .= '投稿时间：' . $post->post_date . '</br>';
    $message .= '审核时间：' . $post->post_modified . '</br>';
    $message .= '</br>';

    $message .= '您可以点击下方按钮查看文章</br>';
    $message .= '<a target="_blank" style="margin-top: 20px;padding:5px 20px" class="but jb-blue" href="' . esc_url($_link) . '">查看文章</a>' . "</br>";

    $msg_arge = array(
        'send_user' => 'admin',
        'receive_user' => $user_id,
        'type' => 'posts',
        'title' => $title,
        'content' => $message,
        'meta' => '',
        'other' => '',
    );
    //创建新消息
    ZibMsg::add($msg_arge);
}

/**新的链接需要管理员审核 */
add_action('zib_ajax_frontend_links_submit_success', 'zib_newmsg_links_submit', 99);
function zib_newmsg_links_submit($data)
{
    if (!_pz('message_s', true)) return;
    $linkdata = array(
        'link_name'   => esc_attr($data['link_name']),
        'link_url'    => esc_url($data['link_url']),
        'link_description' => !empty($data['link_description']) ? esc_attr($data['link_description']) : '无',
        'link_image' => !empty($data['link_image']) ? esc_attr($data['link_image']) : '空',
    );
    $_link = admin_url('link-manager.php?orderby=visible&order=asc');

    $title = '新的链接待审核：' . $linkdata['link_name'];

    $message = '网站有新的链接提交：</br>';
    $message .= '链接名称：' . $linkdata['link_name'] . '</br>';
    $message .= '链接地址：' . $linkdata['link_url'] . '</br>';
    $message .= '链接简介：' . $linkdata['link_description'] . '</br>';
    $message .= '链接Logo：' . $linkdata['link_image'] . '</br>';
    $message .= '</br>';

    $message .= '您可以点击下方按钮快速管理链接</br>';
    $message .= '<a target="_blank" style="margin-top: 20px;padding:5px 20px" class="but jb-blue" href="' . esc_url($_link) . '">管理链接</a>' . "</br>";

    $msg_arge = array(
        'send_user' => 'admin',
        'receive_user' => 'admin',
        'type' => 'system',
        'title' => $title,
        'content' => $message,
        'meta' => '',
        'other' => '',
    );
    //创建新消息
    ZibMsg::add($msg_arge);
}


/** 文章有新评论时候给作者发消息 */
add_filter('comment_notification_text', 'zib_newmsg_new_comment', 10, 2);
function zib_newmsg_new_comment($notify_message, $comment_id)
{
    $comment = get_comment($comment_id);
    $post_id = $comment->comment_post_ID;

    $post   = get_post($post_id);
    $author = get_userdata($post->post_author);
    //非管理员则删除部分内容
    if (!in_array('administrator', $author->roles)) {
        $notify_message = preg_replace("/(Trash it|移至回收站|标记为垃圾评论|Spam it).+/", "", $notify_message);
    }

    if (!_pz('message_s', true) || !zib_msg_is_allow_receive($post->post_author, 'comment')) return $notify_message;

    $post_title = zib_str_cut($post->post_title, 0, 20, '...');

    $title = '有新的评论:文章[' . $post_title . ']';

    $msg_arge = array(
        'send_user' => 'admin',
        'receive_user' => $post->post_author,
        'type' => 'comment',
        'title' => $title,
        'content' => $notify_message,
        'meta' => '',
        'other' => '',
    );
    //创建新消息
    ZibMsg::add($msg_arge);
    return $notify_message;
}

/** 有评论待审核给管理员发消息 */
add_filter('comment_moderation_text', 'zib_newmsg_moderation_notify', 10, 2);
function zib_newmsg_moderation_notify($notify_message, $comment_id)
{
    if (!_pz('message_s', true)) return;
    $comment = get_comment($comment_id);
    $post_id = $comment->comment_post_ID;
    $post   = get_post($post_id);
    $post_title = zib_str_cut($post->post_title, 0, 20, '...');

    $title = '有新的评论待审核:文章[' . $post_title . ']';

    $msg_arge = array(
        'send_user' => 'admin',
        'receive_user' => 'admin',
        'type' => 'comment',
        'title' => $title,
        'content' => $notify_message,
        'meta' => '',
        'other' => '',
    );
    //创建新消息
    ZibMsg::add($msg_arge);
    return $notify_message;
}

/**用户文章获得点赞发消息 */
add_action('like-posts', 'zib_newmsg_post_like', 99, 3);
function zib_newmsg_post_like($post_id, $count, $user_id = 0)
{
    $post   = get_post($post_id);
    //判断是否是自己操作
    if ($user_id == $post->post_author) return;
    if (!zib_msg_is_allow_receive($post->post_author, 'like')) return;

    $post_title = zib_str_cut($post->post_title, 0, 20, '...');

    $title = '您的文章获得点赞：文章[' . $post_title . ']，共计' . $count . '次点赞';

    $message = '';
    $message .= '您的文章获得新的点赞！' . '</br>';
    $message .= '文章：[' . $post->post_title . ']</br>';
    $message .= '共计点赞：' . $count . '次</br>';
    if ($user_id) {
        $data = get_userdata($user_id);
        $message .= '点赞用户：<a target="_blank" href="' . esc_url(get_author_posts_url($user_id)) . '">' . $data->display_name . '</a></br>';
    }

    $_link = get_permalink($post_id);
    $message .= '您可以点击下方按钮查看文章</br>';
    $message .= '<a target="_blank" style="margin-top: 20px;padding:5px 20px" class="but jb-blue" href="' . esc_url($_link) . '">查看文章</a>' . "</br>";

    $msg_arge = array(
        'send_user' => 'admin',
        'receive_user' => $post->post_author,
        'type' => 'like',
        'title' => $title,
        'content' => $message,
        'meta' => '',
        'other' => '',
    );
    //创建新消息
    ZibMsg::add($msg_arge);
}


/**用户评论获得点赞发消息 */
add_action('like-comment', 'zib_newmsg_comment_like', 99, 3);
function zib_newmsg_comment_like($comment_id, $count, $user_id = 0)
{
    if (!zib_msg_is_allow_receive($user_id, 'like')) return;
    $comment = get_comment($comment_id);
    //判断是否是自己操作
    if ($user_id == $comment->user_id) return;

    $post_id = $comment->comment_post_ID;
    $post   = get_post($post_id);
    $post_link = get_permalink($post_id);
    $post_title = zib_str_cut($post->post_title, 0, 20, '...');
    $_link = get_comment_link($comment->comment_ID);
    $title = '您在文章[' . $post_title . ']中的评论获得点赞，共计' . $count . '次点赞';

    $comment_content =  preg_replace('/\[img=(.*?)\]/', '<img class="box-img lazyload" src="$1">', convert_smilies($comment->comment_content));
    $comment_content =  preg_replace('/\[g=(.*?)\]/', '<img class="smilie-icon" src="' . ZIB_STYLESHEET_DIRECTORY_URI . '/img/smilies/$1.gif">', $comment_content);

    $message = '您好！' . $comment->comment_author . '</br>';
    $message .= '您在文章[<a class="muted-color" href="' . esc_url($post_link) . '">' . $post->post_title . '</a>]中的评论，获得新的点赞' . '</br>';
    $message .= '评论内容：' . '</br>';
    $message .= '<div style="padding: 10px 15px; border-radius: 8px; background: rgb(141 141 141 / 0.05); line-height: 1.7;">' . $comment_content  . '</div>';
    $message .= '评论时间：' . $comment->comment_date . '</br>';

    $message .= '共计点赞：' . $count . '次</br>';
    if ($user_id) {
        $data = get_userdata($user_id);
        $message .= '点赞用户：<a target="_blank" href="' . esc_url(get_author_posts_url($user_id)) . '">' . $data->display_name . '</a></br>';
    }

    $message .= '您可以点击下方按钮查看此评论</br>';
    $message .= '<a target="_blank" style="margin-top: 20px;padding:5px 20px" class="but jb-blue" href="' . esc_url($_link) . '">查看评论</a>' . "</br>";

    $msg_arge = array(
        'send_user' => 'admin',
        'receive_user' => $comment->user_id,
        'type' => 'like',
        'title' => $title,
        'content' => $message,
        'meta' => '',
        'other' => '',
    );
    //创建新消息
    ZibMsg::add($msg_arge);
}



/**文章被收藏发消息 */
add_action('favorite-posts', 'zib_newmsg_post_favorite', 99, 3);
function zib_newmsg_post_favorite($post_id, $count, $user_id = 0)
{
    if (!zib_msg_is_allow_receive($user_id, 'favorite')) return;
    $post   = get_post($post_id);
    //判断是否是自己操作
    if ($user_id == $post->post_author) return;
    $post_title = zib_str_cut($post->post_title, 0, 20, '...');
    $udata = get_userdata($user_id);
    $user_name = zib_str_cut($udata->display_name, 0, 8, '...');
    $user_url = esc_url(get_author_posts_url($user_id));

    $title = '用户[' . $user_name . ']收藏了您的文章[' . $post_title . ']';

    $message = '';
    $message .= '有用户收藏了您的文章' . '</br>';
    $message .= '文章：[' . $post->post_title . ']</br>';
    $message .= '文章发布时间：' . $post->post_date . '</br>';

    $message .= '收藏用户：<a target="_blank" href="' . $user_url . '">' . $udata->display_name . '</a></br>';

    $_link = get_permalink($post_id);
    $message .= '<a target="_blank" style="margin-top: 20px;padding:5px 20px" class="but jb-blue" href="' . esc_url($_link) . '">查看文章</a>';
    $message .= '<a target="_blank" style="margin-top: 20px;padding:5px 20px" class="but jb-red ml10" href="' . $user_url . '">查看用户</a>' . "</br>";

    $msg_arge = array(
        'send_user' => 'admin',
        'receive_user' => $post->post_author,
        'type' => 'favorite',
        'title' => $title,
        'content' => $message,
        'meta' => '',
        'other' => '',
    );
    //创建新消息
    ZibMsg::add($msg_arge);
}



/**有新的粉丝、用户被关注 */
add_action('follow-user', 'zib_newmsg_followed', 99, 4);
function zib_newmsg_followed($follow_user_id, $followed_user_id, $follow_count = 0, $followed_count = 0)
{
    if (!zib_msg_is_allow_receive($followed_user_id, 'followed')) return;
    //判断是否是自己操作
    if ($follow_user_id == $followed_user_id) return;

    $udata = get_userdata($follow_user_id);
    $user_name = zib_str_cut($udata->display_name, 0, 8, '...');
    $user_url = esc_url(get_author_posts_url($follow_user_id));

    $title = '您有新的粉丝：[' . $user_name . ']';

    $message = '';
    $message .= '有用户关注您' . '</br>';
    $message .= '用户：<a target="_blank" href="' . $user_url . '">' . $udata->display_name . '</a></br>';
    $message .= '粉丝总数：' . $followed_count . '个</br>';

    $message .= '<a target="_blank" style="margin-top: 20px;padding:5px 20px" class="but jb-red mr10" href="' . $user_url . '">查看用户</a>';
    $message .= '<a target="_blank" style="margin-top: 20px;padding:5px 20px" class="but jb-blue" href="' . esc_url(add_query_arg('page', 'follow', get_author_posts_url($followed_user_id))) . '">查看所有粉丝</a>';

    $msg_arge = array(
        'send_user' => 'admin',
        'receive_user' => $followed_user_id,
        'type' => 'followed',
        'title' => $title,
        'content' => $message,
        'meta' => '',
        'other' => '',
    );
    //创建新消息
    ZibMsg::add($msg_arge);
}
