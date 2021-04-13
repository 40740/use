<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-09-29 13:18:37
 * @LastEditTime: 2021-01-07 23:12:40
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

/**
 * @description: 作者页面的头部封面
 * @param {*}
 * @return {*}
 */
function zib_author_header()
{
    global $wp_query;
    $curauth = $wp_query->get_queried_object();

    $like_n = get_user_posts_meta_count($curauth->ID, 'like');
    $view_n = get_user_posts_meta_count($curauth->ID, 'views');
    $followed_n = get_user_meta($curauth->ID, 'followed-user-count', true);
    $com_n = get_user_comment_count($curauth->ID);
    $post_n = (int) count_user_posts($curauth->ID, 'post', true);
    $post_n = $post_n ? $post_n : 0;
    $com_n = $com_n ? $com_n : 0;

    $this_url = get_author_posts_url($curauth->ID);
    $img = get_user_cover_img($curauth->ID);
    $src = ZIB_STYLESHEET_DIRECTORY_URI . '/img/thumbnail-lg.svg';
    $vip_level = zib_get_user_vip_level($curauth->ID);
    $vip_icon = $vip_level ? zibpay_get_vip_icon($vip_level, "em12 ml6") : '';

    $name =  '<dt class="mr6 em12"><a href="' . $this_url . '">'  . $curauth->display_name . '</a>' . $vip_icon . '</dt>';

    $items = $view_n ? '<item><a data-toggle="tooltip" data-original-title="人气值 ' . $view_n . '">' . zib_svg('huo') . $view_n . '</a></item>' : '';
    $items .= $like_n ? '<item><a data-toggle="tooltip" data-original-title="获得' . $like_n . '个点赞">' . zib_svg('like') . $like_n . '</a></item>' : '';
    $items .= $followed_n ? '<item><a data-toggle="tooltip" data-original-title="共' . $followed_n . '个粉丝"><i class="fa fa-heart em09"></i>' . $followed_n . '</a></item>' : '';

    $metas = ($view_n || $like_n || $followed_n) ? '<div class="article-meta abs-right radius">' . $items . '</div>' : '';

    $user = wp_get_current_user();
    $right_but = '';
    if (!empty($user->ID) && $user->ID === $curauth->ID) {
        $href = get_author_posts_url($user->ID);
        if (empty($_GET['message'])) {
            if (_pz('message_s', true)) {
                $badge = zibmsg_get_user_msg_count($user->ID, '', 'top');
                $href = add_query_arg(array('message' => 'news'), $href);
                $right_but = '<a href="' . esc_html($href) . '" class="but hollow c-white mr6"><i class="fa fa-bell fa-fw" aria-hidden="true"></i>消息中心' . $badge . '</a>';
            } else {
                $right_but = '<a href="javascript:;" data-onclick="' . esc_attr('[href="#author-tab-user"]') . '" class="but hollow c-white"><i class="fa fa-fw fa-address-card-o" aria-hidden="true"></i>我的资料</a>';
            }
        } else {
            $right_but = '<a href="' . esc_html($href) . '" class="but hollow c-white mr6">' . zib_svg('user', '50 0 924 924') . '个人中心</a>';
        }
    } else {
        $follow_but = zib_get_user_follow('but hollow c-white mr6', $curauth->ID);
        $right_but = $follow_but;
        if (_pz('private_s', true) && _pz('message_s', true)) {
            $right_but .= Zib_Private::get_but($curauth->ID, '<i><i class="fa fa-envelope mr6"></i></i>私信', 'but hollow c-white ml6');
        } else {
            $rewards_but = zib_get_rewards_button($curauth->ID, 'rewards but hollow c-white mr10');
            $right_but .= $rewards_but;
        }
    }
    $right_but = $right_but ? '<div style="margin-top:-.7em" class="abs-right em09 mt10">' . $right_but . '</div>' : '';

    $meta = '文章 ' . $post_n;
    if (!_pz('close_comments')) {
        $meta .= '<span class="icon-spot">评论 ' . $com_n . '</span>';
    }

    echo '<div class="author-cover page-cover theme-box radius8 main-shadow">';
    echo '<img class="lazyload fit-cover" ' . (_pz('lazy_cover', true) ? 'src="' . $src . '" data-src="' . $img . '"' : 'src="' . $img . '"') . '>';
    echo '<div class="absolute page-mask"></div>';
    echo '<ul class="list-inline box-body page-cover-con">';
    echo '<li>';
    echo '<div class="avatar-img avatar-lg">';
    echo '<a href="' . $this_url . '">' . zib_get_data_avatar($curauth->ID) . '</a>';
    echo '</div>';
    echo '</li>';
    echo '<li style="max-width:488px;">';
    echo $name;
    echo '<dd class="mt6 em09">' . $meta   . '</dd>';
    echo '<dd class="em09 page-desc mt3">' . get_user_desc($curauth->ID) . '</dd>';
    echo '</li>';
    echo $right_but;
    echo '</ul>';
    echo $metas;
    echo '</div>';
}


/**
 * @description: 作者页主内容外框架
 * @param {*}
 * @return {*}
 */
function zib_author_content()
{
    global $wp_query;
    $curauth = $wp_query->get_queried_object();
    $author_id = $curauth->ID;

    do_action('zib_author_main_content');
    $current_id = get_current_user_id();
    if ($current_id && $current_id == $author_id && !empty($_GET['message'])) {
        zib_author_message_content($author_id);
    } else {
        zib_author_main_content($author_id);
    }
}

/**
 * @description: 作者页主要tab内容构建
 * @param int $user_id 用户ID
 * @return echo 内容
 */
function zib_author_main_content($author_id = '')
{
    if (!$author_id) {
        global $wp_query;
        $curauth = $wp_query->get_queried_object();
        $author_id = $curauth->ID;
    }
    $posts_count = (int) count_user_posts($author_id);
?>
    <div class="index-tab box-header text-center zib-widget">
        <ul class="scroll-x mini-scrollbar">
            <li class="active"><a data-toggle="tab" href="#author-tab-publish-posts"><i class="fa fa fa-file-text-o mr6"></i>发布</a></li>
            <?php
            echo zib_author_main_tab('nav', $author_id);
            do_action('zib_author_main_tab', $author_id);
            ?>
        </ul>
    </div>

    <div class="zib-widget nopw-sm author-content">
        <div class="tab-content author-tab-content">
            <div class="tab-pane fade in active" id="author-tab-publish-posts">
                <?php if ($posts_count) { ?>
                    <ul class="list-inline scroll-x mini-scrollbar box-body notop">
                        <?php
                        echo '<li class="active"><a class="muted-color" data-toggle="tab" href="#author-tab-publish-posts-data">最新发布</a></li>' . zib_author_posts_tab('nav', $author_id);
                        ?>
                    </ul>
                <?php } ?>
                <div class="tab-content">
                    <div class="ajaxpager tab-pane fade in active" id="author-tab-publish-posts-data">
                        <?php
                        $args = array(
                            'no_margin' => true,
                            'no_author' => true,
                        );
                        if ($posts_count) {
                            zib_posts_list($args);
                            zib_paging();
                        } else {
                            echo zib_get_null('暂无文章', '60', 'null-post.svg');
                            $user_id = get_current_user_id();
                            if ($user_id && $user_id == $author_id && !is_page_template('pages/newposts.php')) {
                                echo '<p class="text-center" style="margin:20px 0 50px; ">' . zib_get_write_posts_button('but jb-blue padding-lg', '发布文章') . '</p>';
                            }
                        }
                        ?>
                    </div>
                    <?php echo zib_author_posts_tab('content', $author_id); ?>
                </div>

            </div>
            <?php
            echo zib_author_main_tab('content', $author_id);
            do_action('zib_author_main_tab_con', $author_id);
            ?>
        </div>
    </div>
<?php }

/**
 * @description: 作者页面-文章排序的TAB构建
 * @param {*}
 * @return {*}
 */
function zib_author_posts_tab($type = 'nav', $user_id)
{
    $tabs = array();
    $ajax_url = add_query_arg('user_id', $user_id, admin_url('admin-ajax.php'));
    $tabs[] =  array(
        'name' => '最近更新',
        'action' => 'user_posts_by',
        'id' => 'user_posts_by_modified',
        'ajax_url' => add_query_arg('orderby', 'modified', $ajax_url),
    );
    $tabs[] =  array(
        'name' => '热门文章',
        'action' => 'user_posts_by',
        'id' => 'user_posts_by_views',
        'ajax_url' => add_query_arg('orderby', 'views', $ajax_url),
    );
    if (!_pz('close_comments')) {
        $tabs[] = array(
            'name' => '最多评论',
            'action' => 'user_posts_by',
            'id' => 'user_posts_by_comment_count',
            'ajax_url' => add_query_arg('orderby', 'comment_count', $ajax_url),
        );
    }
    $current_id = get_current_user_id();
    if ($current_id && $current_id == $user_id) {
        $tabs[] = array(
            'name' => '待审核',
            'action' => 'user_posts_by',
            'id' => 'user_posts_by_draft',
            'ajax_url' => add_query_arg('post_status', 'draft', $ajax_url),
        );
        $tabs[] = array(
            'name' => '回收站',
            'action' => 'user_posts_by',
            'id' => 'user_posts_by_trash',
            'ajax_url' => add_query_arg('post_status', 'trash', $ajax_url),
        );
    }
    $args = array(
        'nav_class' => 'muted-color',
    );
    return zib_get_ajax_tab($type, $tabs, $args);
}


/**
 * @description: 主tab框架
 * @param {*}
 * @return {*}
 */
function zib_author_main_tab($type = 'nav', $user_id)
{
    $example[] = array(
        'name' => '例子',
        'id' => 'posts-example',
        'action' => 'posts_example',
        'class' => 'example',
    );
    $args = array(
        'ajax_url' => add_query_arg('user_id', $user_id, admin_url('admin-ajax.php')),
    );
    $tabs = zib_author_ajax_content_but_args($user_id);
    if ($user_id == 'posts') return wp_posts_tab_count();
    return zib_get_ajax_tab($type, $tabs, $args);
}


/**
 * @description: 作者页主tab的参数构建
 * @param int $user_id 作者ID
 * @return {*}
 */
function zib_author_ajax_content_but_args($user_id = '')
{
    if (!$user_id) {
        global $wp_query;
        $curauth = $wp_query->get_queried_object();
        $user_id = $curauth->ID;
    }
    $current_id = get_current_user_id();
    $ajax_url = add_query_arg('user_id', $user_id, admin_url('admin-ajax.php'));

    $args = array();
    $args[] =  array(
        'name' => '<i class="fa fa-star-o mr6"></i>收藏',
        'id' => 'author-tab-favorite',
        'action' => 'user_posts_by',
        'ajax_url' => add_query_arg('type', 'favorite', $ajax_url),
    );
    if (!_pz('close_comments')) {
        $args[] =  array(
            'name' => '<i class="fa fa-comments-o mr6"></i>评论',
            'id' => 'author-tab-comment',
            'action' => 'author_comment',
            'loader' => '',
        );
    }
    $args[] =  array(
        'name' => '<i class="fa fa-heart-o mr6"></i>关注',
        'id' => 'author-tab-follow',
        'action' => 'author_follow',
        'loader' => '',
    );
    $args[] = array(
        'name' => '<i class="fa fa-user-o mr6"></i>资料',
        'id' => 'author-tab-data',
        'action' => 'author_data',
        'loader' => '<div><div class="col-sm-6 mt10"><i class="placeholder s1 ml10" style=" height: 20px; "></i><i class="placeholder s1 ml10" style=" height: 20px; width: calc(100% - 92px); "></i></div><div class="col-sm-6 mt10"><i class="placeholder s1 ml10" style=" height: 20px; "></i><i class="placeholder s1 ml10" style=" height: 20px; width: calc(100% - 92px); "></i></div><div class="col-sm-6 mt10"><i class="placeholder s1 ml10" style=" height: 20px; "></i><i class="placeholder s1 ml10" style=" height: 20px; width: calc(100% - 92px); "></i></div><div class="col-sm-6 mt10"><i class="placeholder s1 ml10" style=" height: 20px; "></i><i class="placeholder s1 ml10" style=" height: 20px; width: calc(100% - 92px); "></i></div><div class="col-sm-6 mt10"><i class="placeholder s1 ml10" style=" height: 20px; "></i><i class="placeholder s1 ml10" style=" height: 20px; width: calc(100% - 92px); "></i></div><div class="col-sm-6 mt10"><i class="placeholder s1 ml10" style=" height: 20px; "></i><i class="placeholder s1 ml10" style=" height: 20px; width: calc(100% - 92px); "></i></div><div class="col-sm-6 mt10"><i class="placeholder s1 ml10" style=" height: 20px; "></i><i class="placeholder s1 ml10" style=" height: 20px; width: calc(100% - 92px); "></i></div><div class="col-sm-6 mt10"><i class="placeholder s1 ml10" style=" height: 20px; "></i><i class="placeholder s1 ml10" style=" height: 20px; width: calc(100% - 92px); "></i></div><div class="col-sm-6 mt10"><i class="placeholder s1 ml10" style=" height: 20px; "></i><i class="placeholder s1 ml10" style=" height: 20px; width: calc(100% - 92px); "></i></div><div class="col-sm-6 mt10"><i class="placeholder s1 ml10" style=" height: 20px; "></i><i class="placeholder s1 ml10" style=" height: 20px; width: calc(100% - 92px); "></i></div></div>',
    );

    if ($current_id && $user_id == $current_id) {
        $args[] =  array(
            'name' => '<i class="fa fa-address-card-o mr6"></i>用户',
            'id' => 'author-tab-user',
            'action' => 'author_user',
            'loader' => '<div class="box-body"><p class="placeholder t1" style=" width: 160px; "></p> <h4 class="item-excerpt placeholder k1"></h4><p class="placeholder k2" style=" height: 203px; "></p><i class="placeholder s1"></i><i class="placeholder s1 ml10"></i><p class="placeholder k2 mt10"></p></div>',
        );
    }
    return apply_filters('zib_author_ajax_content_but_args', $args, $user_id);
}

/**挂钩_GET参数打开tab */
function zib_author_show_tab()
{
    $_GET_page = '';
    if (!empty($_GET['page'])) $_GET_page = 'author-tab-' . $_GET['page'];
    if (!empty($_GET['message'])) $_GET_page = 'user_msg_' . $_GET['message'];
    // if (!$_GET_page) return;
    if (is_author()) {
        $page_args = zib_author_ajax_content_but_args();
    }
    $page_args[]['id'] = 'user_msg_news';
    $page_args[]['id'] = 'user_msg_posts';
    $page_args[]['id'] = 'user_msg_like';
    $page_args[]['id'] = 'user_msg_system';
    $page_args[]['id'] = 'user_msg_private';
    $page_args[]['id'] = 'user_msg_set';

    $page = array_column($page_args, 'id');

    if (in_array($_GET_page, $page)) {
        echo '<script type="text/javascript">';
        echo 'window._win.show_tab = "' . $_GET_page . '";';
        echo '</script>';
        return;
    }
}
add_action('wp_footer', 'zib_author_show_tab', 99);

/**
 * @description: 获取用户文章不同状态的数量
 * @param int $user_id 用户ID
 * @param int $poststatus 文章状态 ： 
 * @return int 数量
 */
//获取文章数量
function zib_get_user_post_count($user_id, $poststatus)
{
    global $wpdb;
    $cuid = esc_sql($user_id);
    $poststatus = esc_sql($poststatus);

    if ($poststatus == 'all') {
        $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(post_author) FROM $wpdb->posts WHERE post_author=%d AND post_type='post'", $cuid));
    } else {
        $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(post_author) FROM $wpdb->posts WHERE post_author=%d AND post_type=%s", $cuid, $poststatus));
    }
    return (int)$count;
}

/**
 * @description: 获取作者、用户卡片列表
 * @param {*}
 * @return {*}
 */
function zib_author_card_lists($args, $users_args = array())
{
    $defaults = array(
        'user_id' => '',
        'show_info' => true,
        'show_posts' => true,
        'show_img_bg' => false,
        'show_img' => true,
        'show_name' => true,
        'show_tag' => true,
        'show_button' => true,
        'limit' => 6,
        'orderby' => 'views'
    );
    $args = wp_parse_args((array) $args, $defaults);
    if (!$users_args) {
        $users_args = array(
            'include' => array(),
            'exclude' => array('1'),
            'order' => 'DESC',
            'orderby' => 'user_registered',
            'number' => 8,
        );
    }
    $users = get_users($users_args);

    if ($users) {
        foreach ($users as $user) {
            zib_author_card($user->ID);
        }
    } else {
        echo '未找到用户!';
    }
}

function zib_author_card($user_id = '')
{
    if (!$user_id) return;
    $user_data = get_userdata($user_id);
    if (!$user_data) return;
    $link = get_author_posts_url($user_id);
    $name = esc_attr($user_data->display_name);
    $avatar = zib_get_data_avatar($user_id);
    $follow = zib_get_user_follow('focus-color px12 ml10 follow', $user_id);
    $desc = get_user_desc($user_id);
    $vip_icon = zibpay_get_vip_icon(zib_get_user_vip_level($user_id), "");
    $vip_icon = $vip_icon ? '<div class="avatar-icontag">' . $vip_icon . '</div>' : '';

    echo '
    <div class="author-minicard radius8 relative-h">
    <div class="absolute blur">' . $avatar . '</div>
        <ul class="list-inline relative">
            <li><a class="avatar-img" href="' . $link . '">' . $avatar . $vip_icon . '</a>
            </li>
            <li>
                <dl>
                    <dt><a href="' . $link . '">' . $name . '</a>' . $follow . '</dt>
                    <dd class="mt6 em09 muted-color text-ellipsis">' . $desc . '</dd>
                </dl>
            </li>
        </ul></div>';
}

//用户资料
function zib_author_con_datas($user_id = '', $class = 'col-sm-6 box-body', $t_class = 'muted-2-color', $v_class = '')
{
    if (!$user_id) return;
    $current_id = get_current_user_id();
    $udata = get_userdata($user_id);
    if (!$udata) return;
    $privacy = get_user_meta($user_id, 'privacy', true);

    $datas = array(
        array(
            'title' => '昵称',
            'value' => esc_attr($udata->display_name),
            'no_show' => false,
        ),
        array(
            'title' => '签名',
            'value' => get_user_desc($user_id),
            'no_show' => false,
        ), array(
            'title' => '性别',
            'value' => esc_attr(get_user_meta($user_id, 'gender', true)),
            'spare' => '保密',
            'no_show' => true,
        ), array(
            'title' => '地址',
            'value' => esc_textarea(get_user_meta($user_id, 'address', true)),
            'spare' => '未知',
            'no_show' => true,
        ), array(
            'title' => '注册时间',
            'value' => $udata->user_registered,
            'spare' => '未知',
            'no_show' => false,
        ), array(
            'title' => '最后登录',
            'value' => get_user_meta($user_id, 'last_login', true),
            'spare' => '未知',
            'no_show' => false,
        ), array(
            'title' => '邮箱',
            'value' => esc_attr($udata->user_email),
            'spare' => '未知',
            'no_show' => true,
        ), array(
            'title' => '个人网站',
            'value' => zib_get_url_link($user_id),
            'spare' => '未知',
            'no_show' => true,
        ), array(
            'title' => 'QQ',
            'value' => esc_attr(get_user_meta($user_id, 'qq', true)),
            'spare' => '未知',
            'no_show' => true,
        ), array(
            'title' => '微信',
            'value' => esc_attr(get_user_meta($user_id, 'weixin', true)),
            'spare' => '未知',
            'no_show' => true,
        ), array(
            'title' => '微博',
            'value' => esc_url(get_user_meta($user_id, 'weibo', true)),
            'spare' => '未知',
            'no_show' => true,
        ), array(
            'title' => 'Github',
            'value' => esc_url(get_user_meta($user_id, 'github', true)),
            'spare' => '未知',
            'no_show' => true,
        )
    );
    foreach ($datas as $data) {
        if (!is_super_admin() && $data['no_show'] && $privacy != 'public' && $current_id != $user_id) {
            if (($privacy == 'just_logged' && !$current_id) || $privacy != 'just_logged') {
                $data['value'] = '用户未公开';
            }
        }
        echo '<div class="' . $class . '">';
        echo '<ul class="list-inline list-author-data">';
        echo '<li class="author-set-left ' . $t_class . '">' . $data['title'] . '</li>';
        echo '<li class="author-set-right ' . $v_class . '">' . ($data['value'] ? $data['value'] : $data['spare']) . '</li>';
        echo '</ul>';
        echo '</div>';
    }
}
function zib_get_url_link($user_id, $class = 'focus-color')
{
    $user_url =  get_userdata($user_id)->user_url;
    $url_name = get_user_meta($user_id, 'url_name', true) ? get_user_meta($user_id, 'url_name', true) : $user_url;
    $user_url =  go_link($user_url, true);
    return $user_url ? '<a class="' . $class . '" href="' . esc_url($user_url) . '" target="_blank">' . esc_attr($url_name) . '</a>' : 0;
}




// 我的资料-个人设置
function zib_author_con_user($user_id = '')
{

    $udata = get_userdata($user_id);
    $_d = array(
        'regtime' => $udata->user_registered,
        'last_login' => get_user_meta($user_id, 'last_login', true),
        'logname' => $udata->user_login,
        'nickname' => $udata->display_name,
        'email' => $udata->user_email,
        'url' => $udata->user_url,
        'roles' => $udata->roles,
        'url_name' => get_user_meta($user_id, 'url_name', true),
        'gender' => get_user_meta($user_id, 'gender', true),
        'address' => get_user_meta($user_id, 'address', true),
        'privacy' => get_user_meta($user_id, 'privacy', true),
        'avatar' => get_user_meta($user_id, 'custom_avatar', true),
        'desc' => get_user_meta($user_id, 'description', true),
        'show_desc' => get_user_desc($user_id),
        'qq' => get_user_meta($user_id, 'qq', true),
        'weixin' => get_user_meta($user_id, 'weixin', true),
        'weibo' => get_user_meta($user_id, 'weibo', true),
        'github' => get_user_meta($user_id, 'github', true)
    );
    //$avatar_img = zib_get_data_avatar($user_id);
    $oauth_new = get_user_meta($user_id, 'oauth_new', true);
?>
    <div class="row author-user">
        <div class="col-sm-3 author-user-but">
            <div class="zib-widget zib-widget-sm">
                <ul class="list-inline scroll-x mini-scrollbar">
                    <?php do_action('author_info_tab', $user_id); ?>
                    <li class="<?php echo _pz('pay_show_user') ? '' : 'active'; ?>"><a class="muted-2-color but hollow" data-toggle="tab" href="#author-tab-dataset"><i class="fa fa-address-card hide-sm fa-fw" aria-hidden="true"></i>资料修改</a></li>
                    <?php
                    $post_rewards_s = _pz('post_rewards_s');
                    $title = $post_rewards_s ? '打赏收款' : '收款设置';
                    if ($post_rewards_s || _pz('pay_rebate_s')) {
                        echo '<li class=""><a class="muted-2-color but hollow" data-toggle="tab" href="#author-tab-rewards"><i class="fa fa-usd hide-sm fa-fw" aria-hidden="true"></i>' . $title . '</a></li>';
                    }
                    ?>
                    <li class=""><a class="muted-2-color but hollow" data-toggle="tab" href="#author-tab-avatarset"><i class="fa fa-user-circle-o hide-sm fa-fw" aria-hidden="true"></i>修改头像</a></li>
                    <li class=""><a class="muted-2-color but hollow" data-toggle="tab" href="#author-tab-coverimgset"><i class="fa fa-picture-o hide-sm fa-fw" aria-hidden="true"></i>修改封面</a></li>
                    <li class=""><a class="muted-2-color but hollow" data-toggle="tab" href="#author-tab-securityset"><i class="fa fa-cog hide-sm fa-fw" aria-hidden="true"></i>账户安全</a></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-9 author-user-con">
            <div class="tab-content zib-widget zib-widget-sm">
                <?php do_action('author_info_tab_con', $user_id); ?>
                <div class="tab-pane list-unstyled<?php echo _pz('pay_show_user') ? '' : ' fade in active'; ?>" id="author-tab-dataset">
                    <form>
                        <li>
                            <div class="author-set-left">用户名</div>
                            <div class="author-set-right">
                                <div disabled type="input" class="form-control"><?php echo esc_attr($_d['logname']) ?></div>
                            </div>
                        </li>
                        <li>
                            <div class="author-set-left">昵称</div>
                            <div class="author-set-right">
                                <input type="input" class="form-control" name="name" value="<?php echo esc_attr($_d['nickname']) ?>" placeholder="请输入用户名">
                            </div>
                        </li>
                        <li>
                            <div class="author-set-left">个人签名</div>
                            <div class="author-set-right">
                                <input type="input" class="form-control" name="desc" value="<?php echo esc_attr($_d['desc']) ?>" placeholder="请简短的介绍自己">
                            </div>
                        </li>
                        <li>
                            <div class="author-set-left">隐私设置</div>
                            <div class="author-set-right form-select">
                                <select class="form-control" name="privacy">
                                    <option value="not_show" <?php selected('not_show', $_d['privacy']); ?>>社交资料 所有人都不可见</option>
                                    <option value="public" <?php selected('public', $_d['privacy']); ?>>社交资料 所有人可见</option>
                                    <option value="just_logged" <?php selected('just_logged', $_d['privacy']); ?>>社交资料 仅注册用户可见</option>
                                </select>
                            </div>
                        </li>
                        <li>
                            <div class="author-set-left">性别</div>
                            <div class="author-set-right form-select">
                                <select class="form-control" name="gender">
                                    <option value="保密" <?php selected('保密', $_d['gender']); ?>>保密</option>
                                    <option value="男" <?php selected('男', $_d['gender']); ?>>男</option>
                                    <option value="女" <?php selected('女', $_d['gender']); ?>>女</option>
                                </select>
                            </div>
                        </li>
                        <li>
                            <div class="author-set-left">居住地</div>
                            <div class="author-set-right">
                                <input type="input" class="form-control" name="address" value="<?php echo esc_attr($_d['address']) ?>" placeholder="请输入居住地址">
                            </div>
                        </li>
                        <li>
                            <div class="author-set-left">个人网站</div>
                            <div class="author-set-right">
                                <input type="input" class="form-control" name="url_name" value="<?php echo esc_attr($_d['url_name']) ?>" placeholder="请输入网站名称">
                                <input type="input" class="form-control" name="url" style="margin-top:10px" value="<?php echo esc_attr($_d['url']) ?>" placeholder="请输入网址">
                            </div>
                        </li>
                        <li>
                            <div class="author-set-left">QQ</div>
                            <div class="author-set-right">
                                <input type="input" class="form-control" name="qq" value="<?php echo esc_attr($_d['qq']) ?>" placeholder="请输入QQ">
                            </div>
                        </li>
                        <li>
                            <div class="author-set-left">微信</div>
                            <div class="author-set-right">
                                <input type="input" class="form-control" name="weixin" value="<?php echo esc_attr($_d['weixin']) ?>" placeholder="请输入微信">
                            </div>
                        </li>
                        <li>
                            <div class="author-set-left">微博</div>
                            <div class="author-set-right">
                                <input type="input" class="form-control" name="weibo" value="<?php echo esc_attr($_d['weibo']) ?>" placeholder="请输入微博地址">
                            </div>
                        </li>
                        <li>
                            <div class="author-set-left">Github</div>
                            <div class="author-set-right">
                                <input type="input" class="form-control" name="github" value="<?php echo esc_attr($_d['github']) ?>" placeholder="请输入Github地址">
                            </div>
                        </li>
                        <li>
                            <div class="author-set-left"></div>
                            <div class="author-set-right">
                                <input type="hidden" name="user_id" value="<?php echo esc_attr($user_id) ?>">
                                <input type="hidden" name="action" value="user_edit_datas">
                                <button type="button" zibajax="submit" class="but jb-blue padding-lg author-submit" name="submit"><i class="fa fa-check mr10"></i>确认提交</button>
                            </div>
                        </li>

                    </form>
                </div>
                <?php
                $post_rewards_s = _pz('post_rewards_s');
                if ($post_rewards_s || _pz('pay_rebate_s')) {
                    $weixin = get_user_meta($user_id, 'rewards_wechat_image_id', true);
                    $alipay = get_user_meta($user_id, 'rewards_alipay_image_id', true);
                    $rewards_title = get_user_meta($user_id, 'rewards_title', true);
                    $weixin_img = '<img style="width: 100%;" src="' . ZIB_STYLESHEET_DIRECTORY_URI . '/img/thumbnail-sm.svg">';
                    $alipay_img = '<img style="width: 100%;" src="' . ZIB_STYLESHEET_DIRECTORY_URI . '/img/thumbnail-sm.svg">';
                    if ($weixin) {
                        $weixin = wp_get_attachment_image_src($weixin, 'medium');
                        $weixin_img = '<img class="lazyload fit-cover" data-src="' . esc_attr($weixin[0]) . '">';
                    }
                    if ($alipay) {
                        $alipay = wp_get_attachment_image_src($alipay, 'medium');
                        $alipay_img = '<img class="lazyload fit-cover" data-src="' . esc_attr($alipay[0]) . '">';
                    }
                    $title = $post_rewards_s ? '请在下方设置打赏的标题文案，并上传微信和支付宝收款二维码' : '请上传微信和支付宝收款二维码';
                ?>
                    <div class="tab-pane fade" id="author-tab-rewards">
                        <form class="set-rewards-form text-center mini-upload">
                            <div class="box-body">
                                <p class="muted-color"><?php echo $title; ?></p>
                            </div>
                            <div class="box-body radius8 main-shadow">
                                <?php if ($post_rewards_s) { ?>
                                    <div class="box-body rewards-title notop">
                                        <div class="muted-color text-left">打赏文案：</div>
                                        <div class="line-form">
                                            <input type="input" class="line-form-input" name="rewards_title" value="<?php echo esc_attr($rewards_title); ?>" placeholder="文章很赞！支持一下吧">
                                            <i class="line-form-line"></i>
                                        </div>
                                    </div>
                                <?php } ?>
                                <ul class="list-inline avatar-upload">
                                    <li>
                                        <p class="muted-2-color">微信收款码</p>
                                        <div class="upload-preview large radius8 preview weixin"><?php echo $weixin_img; ?></div>
                                        <label>
                                            <a class="but hollow padding-lg c-green"><i class="fa fa-cloud-upload mr10"></i>选择微信收款码</a>
                                            <input class="hide" type="file" zibupload="image_upload" data-preview=".preview.weixin" accept="image/gif,image/jpeg,image/jpg,image/png" data-tag="weixin" name="image_upload" action="image_upload" multiple="false">
                                        </label>
                                    </li>
                                    <li>
                                        <p class="muted-2-color">支付宝收款码</p>
                                        <div class="upload-preview large radius8 preview alipay"><?php echo $alipay_img; ?></div>
                                        <label>
                                            <a class="but hollow padding-lg c-blue"><i class="fa fa-cloud-upload mr10"></i>选择支付宝收款码</a>
                                            <input class="hide" type="file" zibupload="image_upload" data-preview=".preview.alipay" accept="image/gif,image/jpeg,image/jpg,image/png" data-tag="alipay" name="image_upload" action="image_upload" multiple="false">
                                        </label>
                                    </li>
                                </ul>
                            </div>

                            <div class="box-body">
                                <button type="button" action="info.upload" zibupload="submit" class="but jb-blue author-submit padding-lg" name="submit"><i class="fa fa-check mr10"></i>确认修改</button>
                                <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                                <?php wp_nonce_field('upload_rewards', 'upload_rewards_nonce') ?>
                                <input type="hidden" name="action" value="user_set_rewards">
                            </div>
                        </form>
                    </div>
                <?php } ?>
                <div class="tab-pane fade" id="author-tab-avatarset">
                    <div class="box-body">
                        <form class="set-avatar-form text-center mini-upload">
                            <div class="">
                                <h4>选择头像</h4>
                                <p class="muted-2-color">
                                    请在下方上传头像，支持jpg、png、gif格式，大小不能超过<?php echo _pz("up_max_size") ?>M，建议尺寸150x150</p>
                                <ul class="list-inline avatar-upload">
                                    <li class="hide-sm">
                                        <div class="upload-preview large radius8 preview"><img style="width: 100%;" src="<?php echo ZIB_STYLESHEET_DIRECTORY_URI . '/img/thumbnail-sm.svg' ?>"></div>
                                    </li>
                                    <li>
                                        <p class="">效果预览</p>
                                        <div class="upload-preview small radius preview"><img style="width: 100%;" src="<?php echo ZIB_STYLESHEET_DIRECTORY_URI . '/img/thumbnail-sm.svg' ?>"></div>
                                        <p class=""></p>
                                        <label>
                                            <a class="but hollow padding-lg c-yellow"><i class="fa fa-cloud-upload mr10"></i>选择头像</a>
                                            <input class="hide" type="file" zibupload="image_upload" accept="image/gif,image/jpeg,image/jpg,image/png" name="image_upload" action="image_upload" multiple="false">
                                        </label>
                                    </li>
                                </ul>
                            </div>
                            <div class="box-body">
                                <button type="button" action="info.upload" zibupload="submit" class="but jb-blue author-submit padding-lg" name="submit"><i class="fa fa-check mr10"></i>确认修改</button>
                                <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                                <?php wp_nonce_field('upload_avatar', 'upload_avatar_nonce') ?>
                                <input type="hidden" name="action" value="user_upload_avatar">
                            </div>
                        </form>

                    </div>
                </div>
                <div class="tab-pane fade" id="author-tab-coverimgset">
                    <div class="box-body">

                        <form class="set-cover-form text-center mini-upload">
                            <div class="">
                                <h4>选择封面图</h4>
                                <p class="muted-2-color">
                                    请在下方上传图片，请选择深色图片，支持jpg、png，大小不能超过<?php echo _pz("up_max_size") ?>M，建议尺寸800x400</p>
                                <div class="cover-upload box-body">
                                    <div class="cover-preview radius8 relative">
                                        <div class="preview-container preview abs-center"><img style="width: 100%;" src="<?php echo ZIB_STYLESHEET_DIRECTORY_URI . '/img/thumbnail-sm.svg' ?>"></div>
                                    </div>
                                </div>

                                <div class="">
                                    <label>
                                        <a class="but padding-lg jb-yellow"><i class="fa fa-cloud-upload mr10"></i>选择图片</a>
                                        <input class="hide" type="file" zibupload="image_upload" accept="image/gif,image/jpeg,image/jpg,image/png" name="image_upload" action="image_upload" multiple="false">
                                    </label>
                                    <button type="button" action="info.upload" zibupload="submit" class="but jb-blue author-submit padding-lg" name="submit"><i class="fa fa-check mr10"></i>确认修改</button>
                                    <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                                    <?php wp_nonce_field('upload_cover', 'upload_cover_nonce') ?>
                                    <input type="hidden" name="action" value="user_upload_cover">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="author-tab-securityset">
                    <div class="theme-box">
                        <?php do_action('zib_oauth_set', $user_id, $_d) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }

add_action('zib_oauth_set', 'zib_oauth_email_set', 9, 2);
function zib_oauth_email_set($user_id, $user_data)
{

    $email = $user_data['email'];

    $ajax_url = add_query_arg(array('tab' => 'email', 'action' => 'user_set_modal'), admin_url('admin-ajax.php'));

    $con = '';
    if ($email) {
        $con .= '<span class="badg oauth-but"><i class="fa fa-envelope mr10"></i>已绑定邮箱 ' . esc_attr($email) . '</span>';
        $con .= '<a class="but c-yellow-2" href="javascript:;" tab-id="tab-bind-email" data-toggle="modal" data-target="#user_set_modal" data-remote="' . esc_url($ajax_url) . '">修改邮箱</a>';
    } else {
        $con .= '<a class="but c-yellow-2 oauth-but" href="javascript:;" tab-id="tab-bind-email" data-toggle="modal" data-target="#user_set_modal" data-remote="' . esc_url($ajax_url) . '"><i class="fa fa-envelope"></i> 绑定邮箱帐号</a>';
    }

    $con = '<div>' . $con . '</div>';
    $title = '<div class="title-h-left"><b>绑定邮箱</b></div>';
    $title .= '<div class="muted-2-color mb20">绑定邮箱帐号，及时接收订单、审核等重要信息</div>';

    echo '<div class="box-body">' . $title . $con . '</div>';
}


if (_pz('user_bind_option', false, 'bind_phone')) {
    add_action('zib_oauth_set', 'zib_oauth_phone_set', 9, 2);
}
function zib_oauth_phone_set($user_id, $user_data)
{

    $phone = get_user_meta($user_id, 'phone_number', true);
    $ajax_url = add_query_arg(array('tab' => 'phone', 'action' => 'user_set_modal'), admin_url('admin-ajax.php'));

    $con = '';
    if ($phone) {
        $con .= '<span class="badg oauth-but"><i class="fa fa-phone mr10"></i>已绑定手机 ' . esc_attr($phone) . '</span>';
        $con .= '<a class="but c-blue-2" href="javascript:;" tab-id="tab-bind-phone" data-toggle="modal" data-target="#user_set_modal" data-remote="' . esc_url($ajax_url) . '">修改手机</a>';
    } else {
        $con .= '<a class="but c-blue-2 oauth-but" href="javascript:;" tab-id="tab-bind-phone" data-toggle="modal" data-target="#user_set_modal" data-remote="' . esc_url($ajax_url) . '"><i class="fa fa-phone"></i> 绑定手机</a>';
    }

    $con = '<div>' . $con . '</div>';
    $title = '<div class="title-h-left"><b>绑定手机</b></div>';
    $title .= '<div class="muted-2-color mb20">绑定手机号，提高账户安全性</div>';

    echo '<div class="box-body">' . $title . $con . '</div>';
}


add_action('zib_oauth_set', 'zib_passwordold_set');
function zib_passwordold_set($user_id)
{
    $oauth_new = get_user_meta($user_id, 'oauth_new', true);
    $subtitle = $oauth_new ? '您还未设置过密码，请在此设置新密码' : '定期修改密码有助于账户安全';

    $ajax_url = add_query_arg(array('tab' => 'change_password', 'action' => 'user_set_modal'), admin_url('admin-ajax.php'));
    $text = $oauth_new ? '设置账户密码' : '修改账户密码';
    $con = '<a class="but c-yellow oauth-but" href="javascript:;" tab-id="tab-change-password" data-toggle="modal" data-target="#user_set_modal" data-remote="' . esc_url($ajax_url) . '"><i class="fa fa-unlock-alt" aria-hidden="true"></i> ' . $text . '</a>';
    $con = '<div>' . $con . '</div>';
    $title = '<div class="title-h-left"><b>账户密码</b></div>';
    $title .= '<div class="muted-2-color mb20">' . $subtitle . '</div>';

    echo '<div class="box-body">' . $title . $con . '</div>';
}

add_action('zib_oauth_set', 'zib_oauth_set_modal');
function zib_oauth_set_modal($user_id)
{
    $args = array(
        'id' => 'user_set_modal',
        'style' => 'max-width: 400px;',
    );

    echo  zib_get_blank_modal($args);
}

add_action('zib_oauth_set', 'zib_oauth_set', 9);
function zib_oauth_set($user_id)
{
    if (!$user_id) return;
    $con = '';
    $rurl = get_author_posts_url($user_id) . '?page=user';
    $args = array();
    $args[] = array(
        'name' => 'QQ',
        'type' => 'qq',
        'class' => 'c-blue',
        'name_key' => 'nickname',
        'icon' => 'fa-qq',
    );
    $args[] = array(
        'name' => '微信',
        'type' => 'weixin',
        'class' => 'c-green',
        'name_key' => 'nickname',
        'icon' => 'fa-weixin',
    );
    $args[] = array(
        'name' => '微博',
        'type' => 'weibo',
        'class' => 'c-red',
        'name_key' => 'screen_name',
        'icon' => 'fa-weibo em12',
    );
    $args[] = array(
        'name' => 'GitHub',
        'type' => 'github',
        'class' => '',
        'name_key' => 'name',
        'icon' => 'fa-github em12',
    );
    $args[] = array(
        'name' => '码云',
        'type' => 'gitee',
        'class' => 'c-red-2',
        'name_key' => 'name',
        'icon' => '',
    );
    $args[] = array(
        'name' => '百度',
        'type' => 'baidu',
        'class' => 'c-blue-2',
        'name_key' => 'username',
        'icon' => '',
    );

    $args[] = array(
        'name' => '支付宝',
        'type' => 'alipay',
        'class' => 'c-blue',
        'name_key' => 'username',
        'icon' => '',
    );

    foreach ($args as $arg) {
        $name = $arg['name'];
        $type = $arg['type'];
        $class = $arg['class'];
        $name_key = $arg['name_key'];
        $icon = '<i class="fa ' . $arg['icon'] . '"></i>';
        if ($type == 'alipay') {
            $icon = zib_svg('alipay', '0 0 1024 1024', 'em12 icon');
            if (wp_is_mobile()) continue; //移动端不显示支付宝
        }
        if ($type == 'baidu') $icon = zib_svg('baidu', '0 0 1024 1024', 'em12 icon');
        if ($type == 'gitee') $icon = zib_svg('gitee');
        if (_pz('oauth_' . $type . '_s') && !_pz('social')) {
            $oauth_info = get_user_meta($user_id, 'oauth_' . $type . '_getUserInfo', true);
            $oauth_id = get_user_meta($user_id, 'oauth_' . $type . '_openid', true);
            if ($oauth_info && $oauth_id) {
                $con .= '<a data-toggle="tooltip" href="javascript:;" openid="' . esc_attr($oauth_id)  . '" title="解绑' . $name . '帐号" user-id="' . $user_id . '" untying-type="' . $type . '" class="oauth-untying but ' . $class . ' hollow">' . $icon . ' 已绑定' . $name . (!empty($oauth_info[$name_key]) ? ' ' . esc_attr($oauth_info[$name_key])  : '帐号') . '</a>';;
            } else {
                $con .= '<a title="绑定' . $name . '帐号" href="' . esc_url(home_url('/oauth/' . $type . '?rurl=' . $rurl)) . '" class="but ' . $class . ' hollow">' . $icon . ' 绑定' . $name . '帐号</a>';
            }
        }
    }
    if (!$con) return;
?>
    <div class="box-body oauth-set">
        <div class="title-h-left"><b>
                绑定社交帐号
            </b></div>
        <div class="muted-2-color mb20">绑定社交帐号之后，您可以更快速的一键登录本站</div>
        <?php echo $con ?>
    </div>
<?php }



function zib_posts_avatar_box($args = array())
{
    $defaults = array(
        'user_id' => '',
        'show_info' => true,
        'show_posts' => true,
        'show_img_bg' => false,
        'show_img' => true,
        'show_name' => true,
        'show_tag' => true,
        'show_button' => true,
        'show_payvip_button' => false,
        'limit' => 6,
        'orderby' => 'views'
    );

    $args = wp_parse_args((array) $args, $defaults);
    if (!$args['user_id']) {
        $user_id = get_the_author_meta('ID');
    } else {
        $user_id = $args['user_id'];
    }
    if (!$user_id) return;
    $cuid = get_current_user_id();

    if (!is_user_logged_in() || $cuid != $user_id) {
        $args['show_button'] = false;
    }
    $avatar = zib_get_data_avatar($user_id);
    $cover = '<img class="lazyload fit-cover" src="' . zib_default_thumb() . '" data-src="' . get_user_cover_img($user_id) . '">';
    $vip_level = zib_get_user_vip_level($user_id);
    $vip_icon = '';
    $vip_but = '';
    if ($vip_level) {
        $vip_icon = zibpay_get_vip_icon($vip_level, "em12 mr6");
    } elseif ($cuid && $user_id == $cuid && $args['show_payvip_button']) {
        $vip_but = '<a class="pay-vip but jb-red radius4 payvip-icon em09" href="javascript:;">' . zib_svg('vip_1', '0 0 1024 1024', 'em12 mr10') . '开通会员 尊享会员权益</a>';
    }
?>
    <div class="article-author article-author zib-widget relative">
        <?php if ($args['show_img_bg']) {
            echo '<div class="avatar-img-bg">';
            echo $cover;
            echo '</div>';
        } ?>
        <ul class="list-inline avatar-info radius8">
            <li>
                <div class="avatar-img avatar-lg"><?php echo $avatar; ?></div>
            </li>
            <li>
                <dl>
                    <?php if ($args['show_name']) { ?>
                        <dt class="avatar-name clearfix">
                            <?php
                            echo $vip_icon . '<a href="' . esc_url(get_author_posts_url($user_id)) . '">' . esc_textarea((get_the_author_meta('display_name', $user_id))) . '</a>';
                            echo zib_get_user_follow('focus-color em09 ml10 follow', $user_id);
                            ?>
                        </dt>
                        <?php echo ($args['show_payvip_button'] && $vip_but) ? '<dt>' . $vip_but . '</dt>' : ''; ?>
                    <?php } ?>
                    <?php if ($args['show_tag']) { ?>
                        <dt class="author-tag">
                            <?php zib_avatar_metas($user_id); ?>
                        </dt>
                    <?php } ?>
                    <?php if ($args['show_info']) { ?>
                        <dt class="author-desc muted-3-color em09">
                            <?php if (_pz('yiyan_avatar_desc')) {
                                echo '<div class="yiyan"></div>';
                            } else {
                                echo get_user_desc($user_id);
                            }
                            ?>
                        </dt>
                    <?php } ?>
                    <?php if ($args['show_button']) { ?>
                        <div class="more-button box-body nobottom">
                            <?php
                            if (!is_page_template('pages/newposts.php')) {
                                echo zib_get_write_posts_button('but jb-purple mr10', '发布文章', '');
                            }
                            ?>
                            <?php echo '<a class="but jb-blue" href="' . esc_url(get_author_posts_url($user_id)) . '">个人中心</a>'; ?>
                        </div>
                    <?php } ?>
                </dl>
            </li>
        </ul>
        <?php if ($args['show_posts']) {
            if ($args['show_img']) {
                echo '<ul data-scroll="x">';
                echo '<div class="list-inline more-posts scroll-x mini-scrollbar">';
                zib_avatar_posts($user_id, $args['limit'], $args['orderby'], $args['show_img']);
                echo '</div>';
                echo '</ul>';
            } else {
                echo '<ul class="more-posts-noimg">';
                zib_avatar_posts($user_id, $args['limit'], $args['orderby'], $args['show_img']);
                echo '</ul>';
            }
        };
        ?>
    </div>
<?php }

function zib_avatar_posts($user_id, $count = 6, $orderby = 'views', $show_img = true)
{
    global $post;
    if (!$user_id) {
        $user_id = get_the_author_meta('ID');
    }
    $args = array(
        'post__not_in'        => array($post->ID),
        'author'                => $user_id,
        'showposts' => $count,
        'ignore_sticky_posts' => 1
    );

    if ($orderby !== 'views') {
        $args['orderby'] = $orderby;
    } else {
        $args['orderby'] = 'meta_value_num';
        $args['meta_query'] = array(
            array(
                'key' => 'views',
                'order' => 'DESC'
            )
        );
    }

    $new_query = new WP_Query($args);
    while ($new_query->have_posts()) {
        $new_query->the_post();
        $title = get_the_title() . get_the_subtitle(false);
        if ($show_img) {
            //$author = get_the_author();
            $time_ago = zib_get_time_ago(get_the_time('U'));
            $info = '<item>' . $time_ago . '</item><item class="pull-right">' . zib_svg('view') . ' ' . get_post_view_count($before = '', $after = '') . '</item>';
            $img = zib_post_thumbnail('', 'fit-cover', true);
            $img = $img ? $img : zib_default_thumb();
            echo '<li class="box-body">';
            $card = array(
                'type' => 'style-3',
                'class' => 'mb10',
                'img' => $img,
                'alt' => $title,
                'link' => array(
                    'url' => get_permalink(),
                    'target' => '',
                ),
                'text1' => $title,
                'text2' => zib_str_cut($title, 0, 45, '...'),
                'text3' => $info,
                'lazy' => true,
                'height_scale' => 70,
            );
            zib_graphic_card($card, true);
            echo '</li>';
        } else {
            echo '<li><a class="icon-circle text-ellipsis" href="' . get_permalink() . '">' . get_the_title() . get_the_subtitle() . '</a></li>';
        }
    };
    wp_reset_query();
    wp_reset_postdata();
}
