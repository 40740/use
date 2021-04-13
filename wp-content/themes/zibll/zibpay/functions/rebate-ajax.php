<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-10-28 16:11:06
 * @LastEditTime: 2020-12-22 22:07:23
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */


/**
 * @description: 获取用户佣金明细列表
 * @param {*}
 * @return {*}
 */
function zibpay_ajax_rebate_user_detail()
{
    $user_id = get_current_user_id();
    if (!$user_id) return;
    global $wpdb;
    //准备查询参数
    $user_id = !empty($_REQUEST['user_id']) ? $_REQUEST['user_id'] : $user_id;
    $paged = !empty($_REQUEST['paged']) ? $_REQUEST['paged'] : 1;
    $ice_perpage = !empty($_REQUEST['ice_perpage']) ? $_REQUEST['ice_perpage'] : 10;
    $offset = $ice_perpage * ($paged - 1);

    $rebate_status = isset($_REQUEST['rebate_status']) ? 'and rebate_status=' . (int)$_REQUEST['rebate_status'] : '';

    $db_order = $wpdb->get_results("SELECT * FROM $wpdb->zibpay_order WHERE `status` = 1 and `referrer_id` = $user_id $rebate_status order by pay_time DESC limit $offset,$ice_perpage");
    $count_all = $wpdb->get_var("SELECT COUNT(referrer_id) FROM $wpdb->zibpay_order WHERE `status` = 1 and `referrer_id` = $user_id $rebate_status order by pay_time DESC limit $offset,$ice_perpage");

    $html = '';
    $lists = '';

    if ($db_order) {
        foreach ($db_order as $order) {
            $order_num = $order->order_num;

            $pay_time = $order->pay_time;

            $class = 'order-type-' . $order->order_type;
            $mark = '￥';
            $rebate_status = $order->rebate_status ? '<span class="c-blue">已提现</span>' : '<span class="c-yellow">未提现</span>';
            $pay_doc = '<span class="pull-right em12"><span class="pay-mark ml10">佣金：' . $mark . '</span>' . $order->rebate_price . '</span>';
            $pay_doc .= '购买时间：' . $pay_time;

            $lists .= '<div class="zib-widget ajax-item ' . $class . '">';
            $lists .= '<div class="meta-time muted-color">订单号：' . $order_num . '</div>';
            $lists .= '<div class="meta-time em09 muted-2-color">提现状态：' . $rebate_status . '</div>';
            $lists .= '<div class="meta-time em09 muted-2-color">' . $pay_doc . '</div>';
            $ajax_url = esc_url(add_query_arg('action', 'rebate_detail', admin_url('admin-ajax.php')));
            if (isset($_REQUEST['rebate_status'])) {
                $ajax_url = esc_url(add_query_arg('rebate_status', $_REQUEST['rebate_status'], $ajax_url));
            }
            $lists .= '</div>';
        }
        $lists .= zibpay_get_ajax_next_paging($count_all, $paged, $ice_perpage, $ajax_url);
    } else {
        $lists .= zib_get_ajax_null('暂无订单', 60, 'null-order.svg');
    }

    $html .= '<div class="ajaxpager" id="rebate_tab_detail">' . $lists . '</div>';
    echo '<body style="display:none;"><main>' . $html . '</main></body>';
    exit;
}
add_action('wp_ajax_rebate_detail', 'zibpay_ajax_rebate_user_detail');

/**
 * @description: AJAX获取用户提现记录列表
 * @param {*}
 * @return {*}
 */
function zibpay_ajax_rebate_user_withdraw_detail()
{
    $user_id = get_current_user_id();
    if (!$user_id) return;
    global $wpdb;
    //准备查询参数
    $user_id = !empty($_REQUEST['user_id']) ? $_REQUEST['user_id'] : $user_id;
    $paged = !empty($_REQUEST['paged']) ? $_REQUEST['paged'] : 1;
    $ice_perpage = !empty($_REQUEST['ice_perpage']) ? $_REQUEST['ice_perpage'] : 10;
    $offset = $ice_perpage * ($paged - 1);

    $msg_get_args = array(
        'send_user' => $user_id,
        'type' => 'withdraw',
    );
    if (isset($_REQUEST['status'])) $msg_get_args['status'] = $_REQUEST['status'];
    $db_msg = ZibMsg::get($msg_get_args, 'modified_time', $offset, $ice_perpage);
    $count_all = ZibMsg::get_count($msg_get_args);
    $html = '';
    $lists = '';

    if ($db_msg) {
        foreach ($db_msg as $msg) {
            //准备参数
            $meta = @maybe_unserialize($msg->meta);
            $price = $meta['withdraw_price'];
            $create_time = date("Y-m-d H:i", strtotime($msg->create_time));
            $status = '<span class="badg c-yellow mr10 em09">待处理</span>';
            if ($msg->status == 1) {
                $status = '<span class="badg c-blue mr10 em09">已提现</span>';
            } elseif ($msg->status == 2) {
                $status = '<span class="badg c-red mr10 em09">已拒绝</span>';
            }
            //折叠
            $mag_collapse = '';
            $mag_collapse .= '<div id="msg_collapse_' . $msg->id . '" class="collapse ml6">';
            $mag_collapse .= '<div class="muted-3-color em09">';
            $mag_collapse .= '<div class="mt10">提现金额： <span class="ml10">￥ ' . $meta['withdraw_price'] . '</span></div>';
            $mag_collapse .= '<div class="mt10">提现单量： <span class="ml10">' . $meta['withdraw_count'] . '笔订单</span></div>';
            $mag_collapse .= '<div class="mt10">申请时间： <span class="ml10">' . $msg->create_time . '</span></div>';
            $mag_collapse .= !empty($meta['withdraw_message']) ? '<div class="mt10">申请留言： <span class="ml10">' . $meta['withdraw_message'] . '</span></div>' : '';
            if ($msg->status) {
                $mag_collapse .= '<div class="mt10">处理结果： <span class="ml10">' . $status . '</span></div>';
                $mag_collapse .= '<div class="mt10">处理时间： <span class="ml10">' . $msg->modified_time . '</span></div>';
                $mag_collapse .= !empty($meta['admin_message']) ? '<div class="mt10">处理反馈： <span class="ml10">' . $meta['admin_message'] . '</span></div>' : '';
            }
            $mag_collapse .= '</div>';
            $mag_collapse .= '</div>';

            //开始构建列表
            $lists .= '<div class="ajax-item border-bottom" style="padding:8px 0;">';
            $lists .= '<div data-toggle="collapse" data-target="#msg_collapse_' . $msg->id . '" class="collapsed pointer meta-time muted-color ">' . $status . $create_time . '<div class="pull-right em12 mt6">￥' . $price . '<i class="fa fa-angle-down ml10"></i></div></div>';
            $lists .= $mag_collapse;
            $lists .= '</div>';

            $ajax_url = esc_url(add_query_arg('action', 'withdraw_detail', admin_url('admin-ajax.php')));
        }
        $lists .= zibpay_get_ajax_next_paging($count_all, $paged, $ice_perpage, $ajax_url);
    } else {
        $lists .= zib_get_ajax_null('暂无提现记录', 60, 'null-order.svg');
    }

    $html .= '<div class="ajaxpager" id="rebate_tab_withdraw">' . $lists . '</div>';
    echo '<body style="display:none;"><main>' . $html . '</main></body>';
    exit;
}
add_action('wp_ajax_withdraw_detail', 'zibpay_ajax_rebate_user_withdraw_detail');


/**
 * @description: AJAX申请提现模态框
 * @param {*}
 * @return {*}
 */
function zibpay_ajax_modal_apply_withdraw()
{
    $user_id = get_current_user_id();
    if (!$user_id) return;

    //文案
    $text_details = _pz('pay_rebate_withdraw_text_details');

    //统计
    $rebate_price_effective = zibpay_get_user_rebate_price($user_id, 'effective');
    $rebate_count_effective = zibpay_get_user_rebate_count($user_id, 'effective');
    if (!$rebate_count_effective) {
        echo '<div class="text-center modal-body"><p class="em09 muted-3-color separator" style="line-height:160px">暂无佣金</p></div>';
        exit;
    }

    //判断是否有正在提现的申请
    $withdraw_ing = (array)zibpay_get_user_withdraw_ing($user_id);
    if (!empty($withdraw_ing['meta']['withdraw_price'])) {
        $html = '';
        $html .= '<button class="close" data-dismiss="modal"><i data-svg="close" data-class="ic-close" data-viewbox="0 0 1024 1024"></i></button>';
        $html .= '<h4 class="mb20">提现申请</h4>';
        $html .= '<div class="muted-color mb20">' . $text_details . '</div>';
        $html .= '<div class="c-red mb10">您有正在处理中的提现申请，请耐心等待</div>';
        $html .= '<div class="mb10">提现金额：<b class="badg c-blue">￥' . $withdraw_ing['meta']['withdraw_price'] . '</b></div>';
        echo '<div class="modal-body"><div>' . $html . '</div></div>';
        exit;
    }

    global $wpdb;
    $withdraw_orders = $wpdb->get_results($wpdb->prepare("SELECT id FROM {$wpdb->zibpay_order} WHERE referrer_id = %s and status=1 and rebate_status=%d", $user_id, 0));
    $withdraw_orders = array_column($withdraw_orders, 'id');
    //提现限制
    $lowest_money = (int)_pz('pay_rebate_withdraw_lowest_money');

    $html = '';

    $card = '';
    $card .= '<div class="mb10">佣金总额：<b class="badg c-blue">￥' . $rebate_price_effective . '</b></div>';
    $card .= '<div class="mb20">佣金笔数：<b class="badg">' . $rebate_count_effective . '笔</b></div>';

    $but = '';

    if ($rebate_price_effective >= $lowest_money) {
        $but .= '<input type="hidden" name="user_id" value="' . $user_id . '">';
        $but .= '<input type="hidden" name="action" value="apply_withdraw">';
        $but .= '<input type="hidden" name="withdraw_orders" value="' . esc_attr(json_encode($withdraw_orders)) . '">';

        $but .= '<button type="button" class="but mr10" data-dismiss="modal">取消</button>';
        $but .= '<a class="but c-yellow mr10 rewards-tabshow" href="javascript:;">收款设置</a>';
        $but .= '<button type="button" zibajax="submit" class="but c-blue">提交申请</button>';
        $but = '<form><div class="mr6 mb10"><input type="text" name="message" placeholder="给管理员留言" class="form-control"></div><div class="text-right">' . $but . '<div></form>';
    } else {
        $but .= '<div class="c-red mb10">您当前的佣金低于' . $lowest_money . '元，暂时不能申请提现</div>';
        $but .= '<div class="text-right"><a class="but c-yellow mr10 rewards-tabshow" href="javascript:;">收款设置</a><div>';
    }

    $html .= '<button class="close" data-dismiss="modal"><i data-svg="close" data-class="ic-close" data-viewbox="0 0 1024 1024"></i></button>';
    $html .= '<h4 class="mb20">提现申请</h4>';
    $html .= '<div class="muted-color mb20">' . $text_details . '</div>';

    $html .= $card;
    $html .= $but;

    echo '<div class="modal-body"><div>' . $html . '</div></div>';
    exit;
}
add_action('wp_ajax_apply_withdraw_modal', 'zibpay_ajax_modal_apply_withdraw');


/**
 * @description: Ajax获取用户中心主内容
 * @param {*}
 * @return {*}
 */
function zibpay_ajax_show_author_tab_rebate()
{
    $con = '';
    $con .= '<div class="ajax-item">' . zibpay_user_content_rebate() . '</div>';
    $con .= '<div class="ajax-pag hide"><div class="next-page ajax-next"><a href="#"></a></div></div>';
    echo '<body><main><div class="ajaxpager" id="author-tab-rebate">' . $con . '</div></main></body>';
    exit;
}
add_action('wp_ajax_author_tab_rebate', 'zibpay_ajax_show_author_tab_rebate');


/**
 * @description: ajax处理用户提现申请
 * @param {*}
 * @return {*}
 */
function zibpay_ajax_apply_withdraw()
{
    $user_id = get_current_user_id();
    if (!$user_id || empty($_POST['user_id']) || $_POST['user_id'] != $user_id) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '处理出错，请刷新后重试')));
        exit();
    };

    //判断是否有正在提现的申请
    $withdraw_ing = (array)zibpay_get_user_withdraw_ing($user_id);
    if (!empty($withdraw_ing['meta']['withdraw_price'])) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '您的申请已提交，请耐心等待')));
        exit();
    };

    $weixin = get_user_meta($user_id, 'rewards_wechat_image_id', true);
    $alipay = get_user_meta($user_id, 'rewards_alipay_image_id', true);
    if (!$weixin && !$alipay) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '请先完成收款设置')));
        exit();
    };

    $rebate_price_effective = zibpay_get_user_rebate_price($user_id, 'effective');
    $rebate_count_effective = zibpay_get_user_rebate_count($user_id, 'effective');

    //添加提现消息
    $withdraw_message = !empty($_POST['message']) ? esc_attr($_POST['message'])  : '';
    // 佣金处理链接
    $process_url = add_query_arg(array('page' => 'zibpay_withdraw', 'status' => '0'), admin_url('admin.php'));
    //准备通知消息
    $msg_con = '';
    $msg_con .= '用户：' .  get_userdata($user_id)->display_name . '，正在申请佣金提现' . "<br />";
    $msg_con .= '提现金额：￥' . $rebate_price_effective . "<br />";
    $msg_con .= '提现订单量：' . $rebate_count_effective . '笔佣金订单' . "<br />";
    $msg_con .= '申请时间：' . current_time("Y-m-d H:i:s") . "<br />";
    $msg_con .= "<br />";
    $msg_con .= $withdraw_message ? '用户留言：' . "<br />" . $withdraw_message . "<br /><br />" : '';
    $msg_con .= '您可以点击下方按钮快速处理此申请' . "<br />";
    $msg_con .= '<a target="_blank" style="margin-top: 20px;" class="but jb-blue" href="' . esc_url($process_url) . '">立即处理</a>' . "<br />";

    $msg_args = array(
        'send_user' => $user_id,
        'receive_user' => 'admin',
        'type' => 'withdraw',
        'title' => '有新的佣金提现申请待处理-用户：' .  get_userdata($user_id)->display_name . '，金额：￥' . $rebate_price_effective,
        'content' => $msg_con,
        'other' => '',
    );

    //创建通知消息的mate数据
    $msg_args_meta = array();
    $msg_args_meta['withdraw_price'] = $rebate_price_effective;
    $msg_args_meta['withdraw_count'] = $rebate_count_effective;
    $msg_args_meta['withdraw_message'] = $withdraw_message;
    if (!empty($_POST['withdraw_orders'])) {
        $withdraw_orders = @json_decode(wp_unslash($_POST['withdraw_orders']), true);
        $msg_args_meta['withdraw_orders'] = $withdraw_orders;
    }
    $msg_args['meta'] = $msg_args_meta;

    //创建消息
    $add_msg = ZibMsg::add($msg_args);
    if (!$add_msg) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '提交失败，请稍候再试')));
        exit();
    }
    //添加处理挂钩
    do_action('user_apply_withdraw', $add_msg);

    echo (json_encode(array('msg' => '申请成功，等待管理员处理')));
    exit();
}
add_action('wp_ajax_apply_withdraw', 'zibpay_ajax_apply_withdraw');
