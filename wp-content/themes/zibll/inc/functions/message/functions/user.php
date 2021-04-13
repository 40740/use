<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-11-03 16:09:18
 * @LastEditTime: 2020-12-20 15:03:43
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

function zib_show_message_page()
{
    $message_s = _pz('message_s', true);
    if (!empty($_GET['message']) && !$message_s) {
        wp_safe_redirect(home_url(remove_query_arg('message')));
    }
}
add_action('template_redirect', 'zib_show_message_page');
/**
 * @description: 用户中心，显示消息页面的主函数
 * @param {*}
 * @return {*}
 */
function zib_author_message_content($user_id = '')
{
    if (!$user_id) $user_id = get_current_user_id();
    if (!$user_id) return;
?>
    <div class="overflow-hidden">
        <div class="row author-user author-msg">
            <div class="col-sm-3 author-user-but">
                <div class="zib-widget mb10">
                    <div class="text-center mb20 hidden-xs">
                        <div class="radius" style="width: 85px;height: 85px;margin: auto;">
                            <?php
                            $img_src = ZIB_STYLESHEET_DIRECTORY_URI . '/img//msg.svg';
                            $img = '<img class="fit-cover lazyload" src="' . $img_src . '">';
                            echo $img;
                            ?>
                        </div>
                    </div>
                    <ul class="list-inline scroll-x mini-scrollbar">
                        <?php
                        $badge = zibmsg_get_user_msg_count($user_id);
                        echo '<span class="hide"><a class="muted-2-color but hollow" data-toggle="tab" href="#user_msg_content" data-ajax="1"><i class="fa fa-bell fa-fw" aria-hidden="true"></i></a></span>';
                        echo '<li class="active"><a class="muted-2-color but hollow" data-toggle="tab" href="#user_msg_news"><i class="fa fa-bell fa-fw" aria-hidden="true"></i>未读消息' . $badge . '</a></li>';
                        echo zib_user_msg_tab('nav', $user_id);
                        ?>
                    </ul>
                </div>
            </div>
            <div class="col-sm-9 author-user-con">
                <div class="tab-content zib-widget" style="min-height:406px;">
                    <div class="tab-pane fade ajaxpager" id="user_msg_content">
                        <div class="post_ajax_loader box-body nopw-sm">
                            <p class="placeholder"></p>
                            <p class="placeholder t1"></p>
                            <p class="placeholder k1"></p>
                            <p class="placeholder k2" style="height:240px;"></p>
                            <i class="placeholder s1"></i><i class="placeholder s1 ml10"></i>
                        </div>
                    </div>
                    <div class="tab-pane fade in active ajaxpager" id="user_msg_news">
                        <?php
                        $count = (int)zibmsg_get_user_msg_count($user_id, '', '', true);
                        if ($count >= 2) {
                            echo zib_get_msg_all_readed($user_id);
                        }
                        echo zib_get_user_news_msg($user_id);
                        ?>
                    </div>
                    <?php echo zib_user_msg_tab('content', $user_id); ?>
                </div>
            </div>
        </div>
    </div>
<?php }

/**
 * @description: 构建全部标记为已读的ajax按钮
 * @param {*}
 * @return {*}
 */
function zib_get_msg_all_readed($user_id, $cat = '', $class = 'border-bottom box-body nopw-sm', $a_class = 'but', $text = '全部标为已读')
{

    $ajax_query_arg = array(
        'action' => 'msg_all_readed',
        'user_id' => $user_id,
        '_wpnonce' => wp_create_nonce('msg_readed'),  //安全验证
    );
    if ($cat) {
        $ajax_query_arg['cat'] = $cat;
    }
    $blacklist_url = add_query_arg($ajax_query_arg, admin_url('admin-ajax.php'));
    $but = '<a class="ajax-readed ' . $a_class . '" href="javascript:;" ajax-href="' . $blacklist_url . '">' . $text . '</a>';
    $html =  '<div class="' . $class . '">' . $but . '</div>';
    return $html;
}


/**
 * @description: 作者页面-文章排序的TAB构建
 * @param {*}
 * @return {*}
 */
function zib_user_msg_tab($type = 'nav', $user_id = '')
{
    $tabs = array();
    if (!$user_id) $user_id = get_current_user_id();
    $message_s = _pz('message_s', true);
    $private_s = _pz('private_s', true);
    if (!$message_s)  return;
    $ajax_url = (zib_random_true(3) && zibpay_get_post_down_array('down')) ? admin_url('user-ajax.php') : admin_url('admin-ajax.php');
    $badge_posts = zibmsg_get_user_msg_count($user_id, 'posts');
    $badge_like  = zibmsg_get_user_msg_count($user_id, 'like');
    $badge_system = zibmsg_get_user_msg_count($user_id, 'system');

    $tabs[] =  array(
        'name' => '<i class="fa fa-comments fa-fw" aria-hidden="true"></i>文章/评论' . $badge_posts,
        'action' => 'user_msg',
        'id' => 'user_msg_posts',
        'ajax_url' => add_query_arg('cat', 'posts', $ajax_url),
    );
    $tabs[] =  array(
        'name' => '<i class="fa fa-heart fa-fw" aria-hidden="true"></i>点赞/关注' . $badge_like,
        'action' => 'user_msg',
        'id' => 'user_msg_like',
        'ajax_url' => add_query_arg('cat', 'like', $ajax_url),
    );
    $tabs[] =  array(
        'name' => '<i class="fa fa-tachometer fa-fw" aria-hidden="true"></i>系统通知' . $badge_system,
        'action' => 'user_msg',
        'id' => 'user_msg_system',
        'ajax_url' => add_query_arg('cat', 'system', $ajax_url),
    );

    /**
    $tabs[] = array(
        'name' => '<i class="fa fa-gift fa-fw" aria-hidden="true"></i>活动消息',
        'action' => 'user_msg',
        'id' => 'user_msg_promotions',
        'ajax_url' => add_query_arg('cat', 'gift', $ajax_url),
    );
     */
    if ($private_s) {
        $badge_private = zibmsg_get_user_msg_count($user_id, 'private');
        $tabs[] =  array(
            'name' => '<i class="fa fa-envelope fa-fw" aria-hidden="true"></i>私信' . $badge_private,
            'action' => 'user_msg_private',
            'id' => 'user_msg_private',
            'ajax_url' => $ajax_url,
        );
    }
    if (_pz('message_user_set', true)) {
        $tabs[] = array(
            'name' => '<i class="fa fa-cog fa-fw" aria-hidden="true"></i>设置',
            'action' => 'user_msg_set',
            'id' => 'user_msg_set',
            'ajax_url' => $ajax_url,
        );
    }

    $args = array(
        'nav_class' => 'muted-2-color but hollow',
        'loader' => '<div class="border-bottom box-body nopw-sm"><ul class="list-inline relative msg-list"><li><a class="msg-img placeholder"></a></li><li><dl><dt class="placeholder k2" style=" width: 80%; "></dt><dd class="mt10"><i class="placeholder s1"></i><i class="placeholder s1 ml10"></i></dd></dl></li></ul></div><div class="border-bottom box-body nopw-sm"><ul class="list-inline relative msg-list"><li><a class="msg-img placeholder"></a></li><li><dl><dt class="placeholder k2" style=" width: 80%; "></dt><dd class="mt10"><i class="placeholder s1"></i><i class="placeholder s1 ml10"></i></dd></dl></li></ul></div><div class="border-bottom box-body nopw-sm"><ul class="list-inline relative msg-list"><li><a class="msg-img placeholder"></a></li><li><dl><dt class="placeholder k2" style=" width: 80%; "></dt><dd class="mt10"><i class="placeholder s1"></i><i class="placeholder s1 ml10"></i></dd></dl></li></ul></div><div class="border-bottom box-body nopw-sm"><ul class="list-inline relative msg-list"><li><a class="msg-img placeholder"></a></li><li><dl><dt class="placeholder k2" style=" width: 80%; "></dt><dd class="mt10"><i class="placeholder s1"></i><i class="placeholder s1 ml10"></i></dd></dl></li></ul></div>',
    );
    return zib_get_ajax_tab($type, $tabs, $args);
}

/**
 * @description: 获取用户未读消息的TAB主内容
 * @param {*}
 * @return {*}
 */
function zib_get_user_news_msg($user_id = '')
{
    global $current_user;
    if (!$user_id) $user_id = $current_user->ID;

    $where = array('status' => 0, 'no_readed_user' => $user_id);
    $ajax_url = admin_url('action', 'user_news_msg', 'admin-ajax.php');
    if (!_pz('private_s', true)) {
        $where['type'] = '<>|private';
    }
    return zib_get_user_msg_lists($user_id, $where, $ajax_url);
}

/**
 * @description: 获取消息列表
 * @param array $where 例如：array('id' => '10');
 * @return {*}
 */
function zib_get_user_msg_lists($user_id = '', $where, $ajax_url = '')
{
    global $current_user;
    if (!$user_id) $user_id = $current_user->ID;
    if (!$user_id) return;
    //准备查询参数
    $user_id = !empty($_REQUEST['user_id']) ? $_REQUEST['user_id'] : $user_id;
    $paged = !empty($_REQUEST['paged']) ? $_REQUEST['paged'] : 1;
    $ice_perpage = !empty($_REQUEST['ice_perpage']) ? $_REQUEST['ice_perpage'] : 10;
    $offset = $ice_perpage * ($paged - 1);

    $where['receive_user'] = zibmsg_get_receive_user_args($user_id);
    //获取数量和列表
    $count_all = ZibMsg::get_count($where);
    $db_msg = ZibMsg::get($where, 'modified_time', $offset, $ice_perpage);
    $html = '';
    $lists = '';

    if ($count_all && $db_msg) {
        foreach ($db_msg as $msg) {
            $lists .= zib_get_msg_box($msg, 'ajax-item nopw-sm', $user_id);
        }
        $ajax_url = home_url(add_query_arg(null, null));
        $lists .= zibpay_get_ajax_next_paging($count_all, $paged, $ice_perpage, $ajax_url);
    } else {
        $lists .= zib_get_ajax_null('暂无消息');
    }

    $html .= $lists;
    return $html;
}
