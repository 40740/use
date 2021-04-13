<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-09-29 13:18:37
 * @LastEditTime: 2021-01-04 15:54:15
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */
$functions = array(
    'class/message-class',
    'class/private-class',
    'functions/ajax',
    'functions/user',
    'functions/new',
);

foreach ($functions as $function) {
    require_once plugin_dir_path(__FILE__) . $function . '.php';
}
//后台
if (is_admin() && _pz('message_s')) {
    require_once plugin_dir_path(__FILE__) . 'functions/admin.php';
}

/**
 * @description: 初始化消息数据库
 * @param {*}
 * @return {*}
 */
add_action('admin_init', 'zibmsg_create_db');
function zibmsg_create_db()
{
    ZibMsg::create_db();
}

/**
 * @description: 获取前台用户的所有收件人数组
 * @param {*}
 * @return {*}
 */
function zibmsg_get_receive_user_args($user_id = '')
{
    if (!$user_id) $user_id = get_current_user_id();
    $user_args = array($user_id, 'all');
    //会员消息
    $vip = zib_get_user_vip_level($user_id);
    if ($vip) {
        $user_args[] = 'vip';
        $user_args[] = 'vip' . $vip;
    }
    //管理员消息
    if (is_super_admin()) $user_args[] = 'admin';
    return $user_args;
}


function zibmsg_nav_radius_button($but = '', $user_id = 0)
{
    if (!$user_id) $user_id = get_current_user_id();
    if (!$user_id) return $but;
    if (!_pz('message_s')) return $but;

    $badge = zibmsg_get_user_msg_count($user_id, '', 'top');
    $icon = '<span class="toggle-radius msg-icon"><i class="fa fa-bell-o" aria-hidden="true"></i></span>';
    $icon .= $badge;

    $list_args = [];
    $href = get_author_posts_url($user_id);
    $list_args[] = [
        'href' => add_query_arg('message', 'posts', $href),
        'badge' => zibmsg_get_user_msg_count($user_id, 'posts'),
        'name' => '<i class="fa fa-comments fa-fw" aria-hidden="true"></i>文章/评论',
    ];
    $list_args[] = [
        'href' => add_query_arg('message', 'like', $href),
        'badge' => zibmsg_get_user_msg_count($user_id, 'like'),
        'name' => '<i class="fa fa-heart fa-fw" aria-hidden="true"></i>点赞/关注',
    ];
    $list_args[] = [
        'href' => add_query_arg('message', 'system', $href),
        'badge' => zibmsg_get_user_msg_count($user_id, 'system'),
        'name' => '<i class="fa fa-tachometer fa-fw" aria-hidden="true"></i>系统通知',
    ];
    if (_pz('private_s', true)) {
        $list_args[] = [
            'href' => add_query_arg('message', 'private', $href),
            'badge' => zibmsg_get_user_msg_count($user_id, 'private'),
            'name' => '<i class="fa fa-envelope fa-fw" aria-hidden="true"></i>私信消息',
        ];
    }
    $list = '';
    foreach ($list_args as $li) {
        if ($li['badge']) {
            $list .= '<li><a class="padding-h10" href="' . esc_url($li['href']) . '">' . $li['name'] . $li['badge'] . '</a></li>';
        }
    }
    $icon_a = '<a href="' . esc_url(add_query_arg('message', 'news', $href)) . '" class="msg-news-icon mr10">' . $icon . '</a>';
    if ($list) {
        $html = '<div class="dropdown pull-right hover-show msg-news-dropdown">' . $icon_a . '<ul class="dropdown-menu hover-show-con">' . $list . '</div>';
        return '<div class="pull-right">' . $but . '</div>' . $html;
    } else {
        return $icon_a . $but;
    }
}
if (in_array('nav_menu', (array)_pz('message_icon_show', array('nav_menu', 'm_nav_user')))) {
    add_filter('zib_nav_radius_button', 'zibmsg_nav_radius_button', 10, 2);
}


/**
 * @description: 获取用户通知消息的图标
 * @param {*}
 * @return {*}
 */
function zibmsg_get_user_icon($user_id = '', $class = '')
{
    if (!$user_id) $user_id = get_current_user_id();
    if (!$user_id) return;
    if (!_pz('message_s')) return;

    $badge = zibmsg_get_user_msg_count($user_id, '', 'top');

    $icon = '<span class="toggle-radius msg-icon"><i class="fa fa-bell-o" aria-hidden="true"></i></span>';
    $icon .= $badge;

    $href = get_author_posts_url($user_id);
    $href = add_query_arg(array('message' => 'news'), $href);
    $icon_a = '<a href="' . $href . '" class="msg-news-icon ' . $class . '">' . $icon . '</a>';
    return $icon_a;
}

/**
 * @description: 将数量保存到全局函数
 * @param {*}
 * @return {*}
 */
add_action('set_current_user', 'zibmsg_set_user_msg_count');
function zibmsg_set_user_msg_count()
{
    if (!_pz('message_s', true)) return;
    global $current_user;
    if (!$current_user->ID)  return;
    $cat = zib_get_msg_cat();
    $msg_count = array();
    $msg_count['all'] = zibmsg_get_user_msg_count($current_user->ID, '', '', true);
    foreach ($cat as $kay => $v) {
        $msg_count[$kay] = zibmsg_get_user_msg_count($current_user->ID, $kay, '', true);
    }
    $current_user->msg_count = $msg_count;
}

/**
 * @description: 获取用户消息数量的徽章
 * @param {*}
 * @return {*}
 */
function zibmsg_get_user_msg_count($user_id, $cat = '', $class = '', $show_count = false)
{
    if (!_pz('message_s', true)) return;
    global $current_user;
    if (!$user_id) $user_id = $current_user->ID;
    if (!$user_id) return;

    //首先根据全局函数查找
    $cat =  $cat ? $cat : 'all';
    $count = 0;
    $msg_count = isset($current_user->msg_count) ? (array)$current_user->msg_count : '';
    $count = isset($msg_count[$cat]) ? $msg_count[$cat] : '';

    if (!$count) {
        //准备查询参数
        $get_count = array(
            'receive_user' => zibmsg_get_receive_user_args($user_id),
            'status' => 0,
            'no_readed_user' => $user_id
        );

        $msg_cat = zib_get_msg_cat();
        if ($cat && $cat != 'all') $get_count['type'] = $msg_cat[$cat];

        $count = ZibMsg::get_count($get_count);
        if (!_pz('private_s', true) && $cat == 'all') {
            //如果没有开启私信，获取全部未读消息则要减去私信数量
            $get_count['type'] = 'private';
            $count = $count - ZibMsg::get_count($get_count);
        }
    }

    if ($show_count) return $count;

    $class = $class ? ' class="' . $class . '"' : '';
    $badge = $count ? '<badge' . $class . '>' . $count . '</badge>' : '';
    return $badge;
}

/**
 * @description: 消息发件人名称格式化
 * @param {*}
 * @return array
 */
function zib_get_msg_send_user_text($user_id)
{
    if ($user_id == 'admin') return '系统管理员';
    $udata = get_userdata($user_id);

    if ($udata) {
        return '<a href="' . get_author_posts_url($user_id) . '">' . $udata->display_name . '</a>';
    }

    return $user_id;
}

/**
 * @description: 消息收件人名称格式化
 * @param {*}
 * @return array
 */
function zib_get_msg_receive_user_text($receive_user)
{
    $sys_receive = array(
        'all'  => '所有用户',
        'vip'  => '所有VIP会员',
        'vip1'  => _pz('pay_user_vip_1_name', '一级会员'),
        'vip2'  => _pz('pay_user_vip_2_name', '二级会员'),
    );

    if (!empty($sys_receive[$receive_user])) return $sys_receive[$receive_user];

    if (is_numeric($receive_user)) {
        $udata = get_userdata($receive_user);
        if ($udata) {
            return '<a href="' . get_author_posts_url($receive_user) . '">' . $udata->display_name . '</a>';
        }
    }
    return $receive_user;
}

/**
 * @description: 消息附加信息
 * @param array $msg 消息的全部数组
 * @return array
 */
function zib_get_msg_dec($msg)
{
    $msg = (array)$msg;

    $msg_type  = zib_get_msg_type_text($msg['type']);
    $create_time  = $msg['create_time'];

    $html = '';
    $html .= '<span data-toggle="tooltip" title="' . $create_time . '" data-placement="bottom"><i class="fa fa-clock-o mr3" aria-hidden="true"></i>' . zib_get_time_ago($create_time) . '</span>';
    $html .= '<span data-toggle="tooltip" title="消息类型" data-placement="bottom"><i class="fa fa-bell-o mr3 ml10" aria-hidden="true"></i>' . $msg_type . '</span>';

    return $html;
}


/**
 * @description: 获取通知消息的列表盒子
 * @param array $msg 消息的全部数组
 * @return {*}
 */
function zib_get_msg_box($msg, $class = '', $user_id = '')
{
    $msg = (array)$msg;
    $meta = @maybe_unserialize($msg['meta']);

    $title  = $msg['title'];
    $create_time  = $msg['create_time'];
    $send_user = $msg['send_user'];
    $img = zibmsg_get_msg_img($msg);

    if (!strstr($msg['readed_user'], '[' . $user_id . ']')) {
        $img .= '<badge class="top">NEW</badge>';
    }

    //准备返回按钮的参数
    $back_id = !empty($_REQUEST['cat']) ? $_REQUEST['cat'] : 'news';
    $back_id = 'user_msg_' . $back_id;
    //准备url参数
    $ajax_query_arg = array(
        'action' => 'user_msg_content',
        'id' => $msg['id'],
        'back_id' => $back_id,
    );

    $ajax_url = esc_url(add_query_arg($ajax_query_arg, admin_url('admin-ajax.php')));

    $dec = zib_get_msg_dec($msg);

    $html = '';
    $html .= '<ul class="list-inline relative msg-list">';
    $html .= '<li>';
    $html .= '<a href="javascript:;" class="msg-img" ajax-tab="#user_msg_content" data-ajax="' . $ajax_url . '" ajax-replace="true">' . $img . '</a>';
    $html .= '</li>';
    $html .= '<li><dl>';
    $html .= '<dt class=""><a href="javascript:;" ajax-tab="#user_msg_content" data-ajax="' . $ajax_url . '" ajax-replace="true">' . $title . '</a></dt>';
    $html .= '<dd class="mt6 em09 muted-2-color text-ellipsis">' . $dec . '</dd>';
    $html .= '</dl></li>';
    $html .= '</ul>';

    $html = '<div class="border-bottom box-body ' . $class . '">' . $html . '</div>';
    return $html;
}


/**
 * @description: 获取通知消息的内容
 * @param array $msg 消息的全部数组
 * @return {*}
 */
function zib_get_msg_content($msg, $class = '')
{
    $msg = (array)$msg;

    $title  = $msg['title'];

    $content  = ZibMsg::get_content($msg);

    $send_user = zib_get_msg_send_user_text($msg['send_user']);
    $img = zibmsg_get_msg_img($msg);
    $dec = zib_get_msg_dec($msg);

    //准备返回按钮的参数
    $back_but = '';
    $back_id = !empty($_REQUEST['back_id']) ? '#' . $_REQUEST['back_id'] : '#user_msg_news';

    $back_but = '<a href="javascript:;" data-onclick="[href=\'' . $back_id . '\']" class="focus-color"><i class="fa fa-angle-left"></i> 返回列表</a>';

    $con = '';
    $con .= '<div class="box-body nopw-sm border-bottom">';
    $con .= '<dt class="em12">' . $title . '</dt>';
    $con .= '<dd class="mt10 muted-2-color"><span class="msg-img">' . $img . '</span>' . $send_user . '</dd>';

    $con .= '</div>';

    $con .= '<div class="box-body nopw-sm border-bottom">';
    $con .= $content;

    $con .= '</div>';
    $con .= '<div class="box-body nopw-sm">';
    $con .= $back_but;
    $con .= '<div class="muted-2-color pull-right">' . $dec . '</div>';
    $con .= '</div>';
    $html = '<div class="msg-content ' . $class . '">' . $con . '</div>';
    return $html;
}

/**
 * @description: 判断是否接收消息
 * @param int $user_id 用户ID
 * @param string $type 消息类型
 * @return {*}
 */
function zib_msg_is_allow_receive($user_id, $type = '')
{
    if (!_pz('message_s', true)) return false;
    if (!_pz('message_user_set', true)) return true;
    if ($type && in_array($type, (array)_pz('message_close_msg_type'))) return false;

    $type_args = zib_get_msg_cat();
    $message_shield = (array)get_user_meta($user_id, 'message_shield', true);
    if ($message_shield) {
        foreach ($message_shield as $shield) {
            if (!empty($type_args[$shield]) && in_array($type, (array)$type_args[$shield])) {
                return false;
            }
        }
    }
    return true;
}

/**F
 * @description: 消息type分类
 * @param {*}
 * @return array
 */
function zib_get_msg_cat()
{
    $cat_type = array();
    $cat_type['posts'] = array('posts', 'comment', 'favorite');
    $cat_type['like'] = array('like', 'followed');
    $cat_type['system'] = array('system', 'promotion', 'vip', 'withdraw_reply', 'withdraw', 'pay');
    $cat_type['private'] = 'private';
    return $cat_type;
}


/**
 * @description: 消息类型名称格式化
 * @param {*}
 * @return array
 */
function zib_get_msg_type_text($type)
{
    $type_args = array(
        'favorite' => '文章收藏',
        'posts' => '文章',
        'comment' => '评论',
        'like' => '点赞',
        'followed' => '关注',
        'system' => '系统',
        'withdraw_reply' => '提现',
        'withdraw' => '提现',
        'private' => '私信',
        'pay' => '订单',
        'vip' => '会员',
        'vip1' => '会员',
        'vip2' => '会员',
        'promotion' => '活动',
    );

    return !empty($type_args[$type]) ? $type_args[$type] . '消息' : '其它消息';
}

/**
 * @description: 获取通知消息的图标
 * @param array $msg 消息的全部数组
 * @return {*}
 */
function zibmsg_get_msg_img($msg, $class = '')
{
    $msg = (array)$msg;
    $meta = @maybe_unserialize($msg['meta']);
    $src = ZIB_STYLESHEET_DIRECTORY_URI . '/img/thumbnail-sm.svg';

    if (!empty($meta['customize_icon'])) {
        return '<img class="fit-cover lazyload ' . $class . '" src="' . $src  . '" data-src="' .  $meta['customize_icon'] . '">';
    }
    $msg_type  = $msg['type'];
    $send_user = $msg['send_user'];
    if (is_numeric($send_user)) {
        $udata = get_userdata($send_user);
        if ($udata) {
            return  zib_get_data_avatar($udata->ID);
        }
    }

    $img_uri  = ZIB_STYLESHEET_DIRECTORY_URI . '/img/';
    $img_src = $img_uri . 'msg-system.svg';

    $img_args = array(
        'withdraw_reply' => $img_uri . 'msg-withdraw.svg',
        'withdraw' => $img_uri . 'msg-withdraw.svg',
        'posts' => $img_uri . 'msg-posts.svg',
        'favorite' => $img_uri . 'msg-posts.svg',
        'comment' => $img_uri . 'msg-comment.svg',
        'like' => $img_uri . 'msg-like.svg',
        'followed' => $img_uri . 'msg-followed.svg',
        'private' => $img_uri . 'msg-private.svg',
        'pay' => $img_uri . 'msg-pay.svg',
        'promotion' => $img_uri . 'msg-promotion.svg',
        'vip' => $img_uri . 'msg-vip.svg',
    );
    $img_src = !empty($img_args[$msg_type]) ? $img_args[$msg_type] : $img_src;
    $img = '<img class="fit-cover lazyload ' . $class . '" src="' . $src . '" data-src="' . $img_src . '">';
    return $img;
}

/**
 * @description: 消息类型信息数组
 * @param {*}
 * @return {*}
 */
function zib_msg_cats()
{
    $cat_type = array();
    $img_uri  = ZIB_STYLESHEET_DIRECTORY_URI . '/img/';

    $cat_type['posts'] = array(
        array(
            'type'  => 'posts',
            'name'  => '文章',
            'icon'  => $img_uri . 'msg-posts.svg',
        ),
        array(
            'type'  => 'comment',
            'name'  => '评论',
            'icon'  => $img_uri . 'msg-comment.svg',
        ),
        array(
            'type'  => 'favorite',
            'name'  => '收藏',
            'icon'  => $img_uri . 'msg-posts.svg',
        ),
    );

    $cat_type['like'] = array(
        array(
            'type'  => 'like',
            'name'  => '点赞',
            'icon'  => $img_uri . 'msg-like.svg',
        ),
        array(
            'type'  => 'followed',
            'name'  => '关注',
            'icon'  => $img_uri . 'msg-followed.svg',
        ),
    );

    $cat_type['system'] = array(
        array(
            'type'  => 'system',
            'name'  => '系统',
            'icon'  => $img_uri . 'msg-system.svg',
        ),
        array(
            'type'  => 'promotion',
            'name'  => '活动',
            'icon'  => $img_uri . 'msg-pay.svg',
        ),
        array(
            'type'  => 'withdraw_reply',
            'name'  => '提现',
            'icon'  => $img_uri . 'msg-withdraw.svg',
        ),
        array(
            'type'  => 'withdraw',
            'name'  => '提现',
            'icon'  => $img_uri . 'msg-withdraw.svg',
        ),
        array(
            'type'  => 'pay',
            'name'  => '订单',
            'icon'  => $img_uri . 'msg-private.svg',
        ),
        array(
            'type'  => 'vip',
            'name'  => '会员',
            'icon'  => $img_uri . 'msg-vip.svg',
        ),
    );

    $cat_type['private'] = array(
        array(
            'type'  => 'private',
            'name'  => '私信',
            'icon'  => $img_uri . 'msg-posts.svg',
        ),
    );
    return $cat_type;
}
