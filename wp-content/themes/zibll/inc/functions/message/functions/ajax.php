<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-11-03 16:09:18
 * @LastEditTime: 2021-01-05 22:51:32
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

/**
 * @description: 未读消息翻页
 * @param {*}
 * @return {*}
 */
function zib_ajax_user_news_msg()
{
    $html =  zib_get_user_news_msg(get_current_user_id());
    echo '<body style="display:none;"><main><div class="ajaxpager" id="user_msg_news">' . $html . '</div></main></body>';
    exit;
}
add_action('wp_ajax_user_news_msg', 'zib_ajax_user_news_msg');


/**
 * @description: 其它消息类型的TAB内容
 * @param {*}
 * @return {*}
 */
function zib_ajax_user_msg()
{
    $cat = !empty($_REQUEST['cat']) ? $_REQUEST['cat'] : '';

    $msg_cat = zib_get_msg_cat();

    $where = array(
        'status' => 0,
        'type' => $msg_cat[$cat]
    );
    $ajax_id = $cat ? 'user_msg_' . $cat : '';
    $ajax_id = $ajax_id ? ' id="' . $ajax_id . '"' : '';
    $html = zib_get_user_msg_lists('', $where);
    echo '<body style="display:none;"><main><div class="ajaxpager"' . $ajax_id . '>' . $html . '</div></main></body>';
    exit;
}
add_action('wp_ajax_user_msg', 'zib_ajax_user_msg');


/**
 * @description: AJAX获取消息设置
 * @param {*}
 * @return {*}
 */
function zib_ajax_user_msg_set()
{
    $user_id = get_current_user_id();
    if (!_pz('message_user_set', true) || !$user_id) {
        echo (zib_get_ajax_error_html(array('error' => 1, 'ys' => 'danger', 'msg' => '暂未提供此功能')));
        exit;
    }

    $msg_set = (array)get_user_meta($user_id, 'message_shield', true);

    $but_args = array();
    $but_args[] = array(
        'checked' => in_array('posts', $msg_set),
        'neme' => 'posts',
        'title' => '文章消息',
        'label' => '接收文章、评论、收藏等相关消息'
    );
    $but_args[] = array(
        'checked' => in_array('like', $msg_set),
        'neme' => 'like',
        'title' => '点赞关注',
        'label' => '接收点赞、关注等相关消息'
    );
    $but_args[] = array(
        'checked' => in_array('system', $msg_set),
        'neme' => 'system',
        'title' => '系统消息',
        'label' => '接收订单、活动、等系统消息'
    );

    $set = '';
    $set .= '<div class="box-body">';
    $set .= '<div class="title-h-left em12"><b>消息推送设置</b></div>';
    $set .= '</div>';

    foreach ($but_args as $but) {
        $checked = $but['checked'] ? '' : ' checked="checked"';
        $set .= '<div class="box-body">';
        $set .= '<span class="form-checkbox">';
        $set .= '<input' . $checked . ' name="' . $but['neme'] . '" id="setid-' . $but['neme'] . '" type="checkbox">';
        $set .= '<label for="setid-' . $but['neme'] . '"> ' . $but['label'] . '</label>';
        $set .= '</span>';
        $set .= '</div>';
    }

    $set .= '<div class="box-body">';
    $set .= '<input type="hidden" name="user_id" value="' . $user_id . '">';
    $set .= '<input type="hidden" name="action" value="message_shield">';
    $set .= wp_nonce_field('user_msg_set', '_wpnonce', false, false);  //安全验证
    $set .= '<button type="button" zibajax="submit" class="but jb-blue padding-lg mt10" name="submit"><i class="fa fa-check mr10"></i>确认提交</button>';
    $set .= '</div>';

    $con = '';
    $con = '<div class="ajax-item"><form>' . $set . '</form></div>';
    $con .= '<div class="ajax-pag hide"><div class="next-page ajax-next"><a href="#"></a></div></div>';

    $html = '<div class="ajaxpager" id="user_msg_set">' . $con . '</div>';
    echo '<body style="display:none;"><main>' . $html . '</main></body>';
    exit;
}
add_action('wp_ajax_user_msg_set', 'zib_ajax_user_msg_set');


/**
 * @description: ajax将用户保存用户设置
 * @param {*}
 * @return {*}
 */
function zib_ajax_user_message_shield()
{

    if (!_pz('message_user_set', true)) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '暂未提供此功能')));
        exit;
    }

    $user_id = get_current_user_id();
    if (!$user_id) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '登录失效，请刷新页面')));
        exit;
    }
    //安全验证
    if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'user_msg_set')) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '安全验证失败，请刷新后再试')));
        exit();
    }

    $shield = array();
    $shield_type = array('posts', 'like', 'system');

    foreach ($shield_type as $type) {
        if (empty($_REQUEST[$type])) {
            $shield[] = $type;
        }
    }
    update_user_meta($user_id, 'message_shield', $shield);

    echo (json_encode(array('msg' => '设置已保存', 'shield' => $shield)));
    exit;
}
add_action('wp_ajax_message_shield', 'zib_ajax_user_message_shield');


/**
 * @description: AJAX获取消息内容
 * @param {*}
 * @return {*}
 */
function zib_ajax_user_msg_content()
{

    $user_id = get_current_user_id();
    $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : '';

    $msg = ZibMsg::get_row(array('id' => $id));
    $con = '';
    $receive_user_args = zibmsg_get_receive_user_args($user_id);
    if ($msg && $user_id && in_array($msg->receive_user, (array)$receive_user_args)) {
        //判断是自己的消息
        $con .= zib_get_msg_content($msg, 'ajax-item');
        $con .= '<div class="ajax-pag hide"><div class="next-page ajax-next"><a href="#"></a></div></div>';
        //执行添加已阅读用户
        ZibMsg::add_readed_user($id, $user_id);
    } else {
        $con = zib_get_ajax_null('内容获取出错', 70, 'null.svg');
    }

    $html = '<div class="ajaxpager" id="user_msg_content">' . $con . '</div>';
    echo '<body style="display:none;"><main>' . $html . '</main></body>';
    exit;
}
add_action('wp_ajax_user_msg_content', 'zib_ajax_user_msg_content');



/**
 * @description: ajax将用户消息全部标记为已读
 * @param {*}
 * @return {*}
 */
function zib_ajax_user_msg_all_readed()
{

    $user_id = !empty($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
    if (!$user_id) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '参数传入错误')));
        exit;
    }
    if ($user_id != get_current_user_id() && !is_super_admin()) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '权限不足')));
        exit;
    }

    //安全验证
    if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'msg_readed')) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '安全验证失败，请稍候再试')));
        exit();
    }

    $where = array(
        'receive_user' => zibmsg_get_receive_user_args($user_id),
        'status' => 0,
        'no_readed_user' => $user_id
    );

    $msg = ZibMsg::user_all_readed($where, $user_id);

    echo (json_encode(array('msg' => 1, 'reload' => 1, '已将全部消息标为已读')));
    exit;
}
add_action('wp_ajax_msg_all_readed', 'zib_ajax_user_msg_all_readed');



/**
 * @description: ajax发送私信
 * @param {*}
 * @return {*}
 */
function zib_ajax_send_private()
{

    if (!_pz('message_s', true) || !_pz('private_s', true)) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '管理员已关闭此功能')));
        exit;
    }

    //验证消息是否为空
    if (empty($_POST['receive'])) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '内容不能为空')));
        exit;
    }
    if (!trim(strip_tags($_POST['receive']))) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '内容不合法')));
        exit;
    }

    //判断频繁操作
    if (isset($_COOKIE['send_private_time'])) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '操作过于频繁，请稍候再试')));
        exit();
    }
    //设置浏览器缓存限制提交的间隔时间
    $expire = time() + 3;
    setcookie('send_private_time', time(), $expire, '/', '', false);

    //安全验证
    if (!wp_verify_nonce($_POST['send_private_nonce'], 'send_private')) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '安全验证失败，请稍候再试')));
        exit();
    }

    //登录用户验证
    $user_id = !empty($_POST['send_user']) ? $_POST['send_user'] : '';
    if (!$user_id || empty($_POST['receive_user'])) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '参数传入错误')));
        exit;
    }
    if ($user_id != get_current_user_id() && !is_super_admin()) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '权限不足')));
        exit;
    }

    //黑名单验证
    $is_blacklist = Zib_Private::is_blacklist($user_id, $_POST['receive_user']);
    if ($is_blacklist) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '消息发送失败，对方已将您添加至黑名单')));
        exit;
    }

    $msg_args = array(
        'send_user' => $user_id,
        'receive_user' => $_POST['receive_user'],
        'content' => $_POST['receive'],
        'parent' => '',
        'status' => '',
        'meta' => '',
        'other' => '',
    );

    $msg = Zib_Private::add($msg_args);

    if (!$msg) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '操作失败')));
        exit;
    }
    $html = Zib_Private::get_msg_box($msg, $user_id);
    echo (json_encode(array('msg' => '消息已发送', 'html' => $html)));
    exit;
}
add_action('wp_ajax_send_private', 'zib_ajax_send_private');


/**
 * @description: ajax获取私信窗口的模态框
 * @param {*}
 * @return {*}
 */
function zib_ajax_private_window_modal()
{

    if (!_pz('message_s', true) || !_pz('private_s', true)) {
        echo '<div class="text-center c-red" style="padding: 80px 0; ">管理员已关闭此功能</div>';
        exit;
    }

    $receive_user = !empty($_REQUEST['receive_user']) ? $_REQUEST['receive_user'] : '';
    if (!$receive_user) {
        echo '<div class="text-center c-red" style="padding: 80px 0; ">参数传入错误</div>';
        exit;
    }

    $send_user = get_current_user_id();

    $html = '<button class="close ml3" data-dismiss="modal">' . zib_svg('close', '0 0 1024 1024', 'ic-close') . '</button>';
    $html .= Zib_Private::get_window($send_user, $receive_user);

    echo '<div class="modal-body">' . $html . '</div>';

    exit;
}
add_action('wp_ajax_private_window_modal', 'zib_ajax_private_window_modal');




/**
 * @description: AJAX获取用户私信tab
 * @param {*}
 * @return {*}
 */
function zib_ajax_user_msg_private($user_id = '')
{
    if (!_pz('message_s', true) || !_pz('private_s', true)) {
        echo (zib_get_ajax_error_html(array('error' => 1, 'ys' => 'danger', 'msg' => '暂未提供此功能')));
        exit;
    }
    if (!$user_id) $user_id = get_current_user_id();
    if (!$user_id) {
        echo (zib_get_ajax_error_html(array('error' => 1, 'ys' => 'danger', 'msg' => '权限不足')));
        exit;
    }

    $con = '';
    $private_count = zibmsg_get_user_msg_count($user_id, 'private', '', true);

    $lists = '';
    if ($private_count >= 2) {
        $lists = zib_get_msg_all_readed($user_id, 'private', 'border-bottom padding-h10');
    }
    $lists .= Zib_Private::get_chat_lists($user_id);

    $con .= '<div class="col-sm-4 scroll-y mini-scrollbar ajaxpager" id="user_chat_lists" style="max-height: 550px;">' . $lists . '</div>';

    $submit_text = '<i class="fa fa-send-o"></i>发送';
    $wondos = '<div class="private-window post_ajax_trigger"><div class="private-window-header mb10 text-center">消息内容</div><div class="private-window-content mb10 opacity5 relative"><div class="abs-center em09 muted-color separator">请选择用户</div></div><div class="private-window-footer"><form class="from-private"><p><textarea placeholder="" class="form-control" rows="2" disabled="disabled"></textarea></p><div class="pull-right"><button id="submit" class="but c-blue" disabled="disabled">' . $submit_text . '</button></div></form></div></div>';
    $wondos .= '<div class="private-window post_ajax_loader" style="display: none;"><div class="private-window-header mb10 text-center"><i class="loading mr10"></i>加载中...</div><div class="placeholder mb10 placeholder" style="height: 400px;"></div><div class="private-window-footer"><form class="from-private"><p><textarea placeholder="" class="form-control" rows="2" disabled="disabled"></textarea></p><div class="pull-right"><button id="submit" class="but c-blue" disabled="disabled">' . $submit_text . '</button></div></form></div></div>';

    $con .= '<div class="col-sm-8 ajaxpager" id="user_private_window">' . $wondos . '</div>';

    $con = '<div class="row ajax-item msg-private">' . $con . '</div>';
    //不显示下一页按钮
    $con .= '<div class="ajax-pag hide"><div class="next-page ajax-next"><a href="#"></a></div></div>';

    $html = '<div class="ajaxpager" id="user_msg_private">' . $con . '</div>';
    echo '<body style="display:none;"><main>' . $html . '</main></body>';
    exit;
}
add_action('wp_ajax_user_msg_private', 'zib_ajax_user_msg_private');



/**
 * @description: AJAX获取私信消息列表翻页
 * @param {*}
 * @return {*}
 */
function zib_ajax_user_private_lists()
{
    $receive_user = !empty($_REQUEST['receive_user']) ? $_REQUEST['receive_user'] : '';
    if (!$receive_user) {
        echo (zib_get_ajax_error_html(array('error' => 1, 'ys' => 'danger', 'msg' => '参数传入错误')));
        exit;
    }
    //安全验证
    if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'user_private_lists')) {
        echo zib_get_ajax_error_html((array('error' => 1, 'ys' => 'danger', 'msg' => '安全验证失败，请稍候再试')));
        exit;
    }

    $send_user = get_current_user_id();
    if (!$send_user) {
        echo (zib_get_ajax_error_html(array('error' => 1, 'ys' => 'danger', 'msg' => '权限不足')));
        exit;
    }

    //验证结束
    $msg = Zib_Private::get_msg_lists($send_user, $receive_user);

    $html = '<div class="private-window-content">' . $msg . '</div>';
    echo '<body style="display:none;"><main>' . $html . '</main></body>';
    exit;
}
add_action('wp_ajax_user_private_lists', 'zib_ajax_user_private_lists');



/**
 * @description: 消息中心ajax获取私信窗口
 * @param {*}
 * @return {*}
 */

function zib_ajax_private_window()
{

    if (!_pz('message_s', true) || !_pz('private_s', true)) {
        echo (zib_get_ajax_error_html(array('error' => 1, 'ys' => 'danger', 'msg' => '暂未提供此功能')));
        exit;
    }
    $receive_user = !empty($_REQUEST['receive_user']) ? $_REQUEST['receive_user'] : '';
    if (!$receive_user) {
        echo (zib_get_ajax_error_html(array('error' => 1, 'ys' => 'danger', 'msg' => '参数传入错误')));
        exit;
    }
    //安全验证
    if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'private_window')) {
        echo zib_get_ajax_error_html((array('error' => 1, 'ys' => 'danger', 'msg' => '安全验证失败，请稍候再试')));
        exit;
    }

    $send_user = get_current_user_id();
    if (!$send_user) {
        echo (zib_get_ajax_error_html(array('error' => 1, 'ys' => 'danger', 'msg' => '权限不足')));
        exit;
    }
    $con = Zib_Private::get_window($send_user, $receive_user);
    $con = '<div class="ajax-item">' . $con . '</div>';
    $con .= '<div class="ajax-pag hide"><div class="next-page ajax-next"><a href="#"></a></div></div>';
    $html = '<div class="ajaxpager" id="user_private_window">' . $con . '</div>';
    echo '<body style="display:none;"><main>' . $html . '</main></body>';

    exit;
}
add_action('wp_ajax_private_window', 'zib_ajax_private_window');



/**
 * @description: ajax 私信加入黑名单
 * @param {*}
 * @return {*}
 */
function zib_ajax_private_blacklist()
{

    $user_id = !empty($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
    $receive_user = !empty($_REQUEST['receive_user']) ? $_REQUEST['receive_user'] : '';
    if (!$user_id || !$receive_user) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '参数传入错误')));
        exit;
    }
    if ($user_id != get_current_user_id() && !is_super_admin()) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '权限不足')));
        exit;
    }

    //安全验证
    if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'private_set')) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '安全验证失败，请稍候再试')));
        exit();
    }

    $private_blacklist = get_user_meta($user_id, 'private_blacklist', true);
    $private_blacklist = $private_blacklist ? $private_blacklist : array();

    if (in_array($receive_user, $private_blacklist)) {
        //移除黑名单
        $h = array_search($receive_user, $private_blacklist);
        unset($private_blacklist[$h]);
        update_user_meta($user_id, 'private_blacklist', $private_blacklist);
        echo (json_encode(array('text' => '加入黑名单', 'msg' => '已从黑名单移除')));
        exit;
    } else {
        //添加黑名单
        $private_blacklist[] = $receive_user;
        update_user_meta($user_id, 'private_blacklist', $private_blacklist);
        echo (json_encode(array('text' => '移除黑名单', 'msg' => '已加入黑名单，不再接受此用户消息')));
        exit;
    }

    exit;
}
add_action('wp_ajax_private_blacklist', 'zib_ajax_private_blacklist');


/**
 * @description: ajax 私信加入黑名单
 * @param {*}
 * @return {*}
 */
function zib_ajax_clear_user_private()
{

    $user_id = !empty($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
    $receive_user = !empty($_REQUEST['receive_user']) ? $_REQUEST['receive_user'] : '';
    if (!$user_id || !$receive_user) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '参数传入错误')));
        exit;
    }
    if ($user_id != get_current_user_id() && !is_super_admin()) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '权限不足')));
        exit;
    }

    //安全验证
    if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'private_set')) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '安全验证失败，请稍候再试')));
        exit();
    }

    echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '暂未开放此功能')));
    exit;
}
add_action('wp_ajax_clear_user_private', 'zib_ajax_clear_user_private');
