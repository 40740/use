<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-09-29 13:18:50
 * @LastEditTime: 2020-12-20 14:53:27
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

/**挂钩到用户中心 */

if (_pz('pay_show_user')) {
    add_action('author_info_tab', 'zibpay_user_info_tab');
    add_action('author_info_tab_con', 'zibpay_user_info_tab_con');
}
function zibpay_user_info_tab($user_id)
{

    if (_pz('pay_user_vip_1_s') || _pz('pay_user_vip_2_s')) {
        echo '<li class="active"><a class="muted-2-color but hollow" data-toggle="tab" href="#author-tab-vip"><i class="fa fa-diamond hide-sm fa-fw" aria-hidden="true"></i>VIP会员</a></li>';
        echo '<li class=""><a class="muted-2-color but hollow" data-toggle="tab" data-ajax="" href="#author-tab-pay"><i class="fa fa-shopping-cart hide-sm fa-fw" aria-hidden="true"></i>支付订单</a></li>';
    } else {
        echo '<li class="active"><a class="muted-2-color but hollow" data-toggle="tab" href="#author-tab-pay"><i class="fa fa-shopping-cart hide-sm fa-fw" aria-hidden="true"></i>支付订单</a></li>';
    }
}


function zibpay_user_info_tab_con($user_id)
{
    if (_pz('pay_user_vip_1_s') || _pz('pay_user_vip_2_s')) {
        zibpay_user_vip_tab_con($user_id);
    }
?>
    <div class="tab-pane fade<?php echo (_pz('pay_user_vip_1_s') || _pz('pay_user_vip_2_s')) ? '' : ' active in'; ?>" id="author-tab-pay">
        <div class="theme-box user-pay">
            <div class="box-body">
                <div class="title-h-left"><b>统计</b></div>
            </div>
            <div class="box-body notop nobottom user-pay-order">
                <?php echo zibpay_get_user_pay_statistical($user_id); ?>
            </div>
        </div>
        <div class="theme-box user-pay">
            <div class="box-body notop">
                <div class="title-h-left"><b>订单</b></div>
            </div>
            <div class="box-body notop nobottom user-pay-statistical ajaxpager" id="user_order_lists">
                <div class="post_ajax_loader">
                    <h4 class="item-excerpt placeholder k1"></h4>
                    <p class="placeholder k2"></p><i class="placeholder s1"></i><i class="placeholder s1 ml10"></i>
                    <h4 class="item-excerpt placeholder k1"></h4>
                    <p class="placeholder k2"></p><i class="placeholder s1"></i><i class="placeholder s1 ml10"></i>
                    <h4 class="item-excerpt placeholder k1"></h4>
                    <p class="placeholder k2"></p><i class="placeholder s1"></i><i class="placeholder s1 ml10"></i>
                </div>
                <span class="post_ajax_trigger"><a ajax-href="<?php echo esc_url(add_query_arg('action', 'user_pay_order', admin_url('admin-ajax.php'))); ?>" class="ajax_load ajax-next ajax-open">1545645623231123</a></span>
            </div>
        </div>
    </div>
<?php }

/**挂钩_GET参数打开tab */
function zib_pay_user_url_show_tab()
{
    if (!empty($_GET['page']) && $_GET['page'] == 'pay') {
        $_GET['page'] = 'user';
    }
}
add_action('wp_footer', 'zib_pay_user_url_show_tab');

/**
 * 用户订单金额统计
 */
function zibpay_get_user_pay_price($user_id, $type = '', $order_type = '')
{
    global $wpdb;
    $sum = 0;
    $order_type = $order_type ? 'AND `order_type` = ' . $order_type : '';
    if ($type == 'order_price') {
        $sum = $wpdb->get_var("SELECT SUM(order_price) FROM $wpdb->zibpay_order WHERE `status` = 1 and `user_id` = $user_id $order_type");
    } elseif ($type == 'pay_price') {
        $sum = $wpdb->get_var("SELECT SUM(pay_price) FROM $wpdb->zibpay_order WHERE `status` = 1 and `user_id` = $user_id $order_type");
    }
    return $sum ? $sum : 0;
}

/**
 * 用户订单数量统计
 */
function zibpay_get_user_order_count($user_id, $type = '')
{
    global $wpdb;
    if ($type) {
        $count = $wpdb->get_var("SELECT COUNT(user_id) FROM $wpdb->zibpay_order WHERE `status` = 1 and `user_id` = $user_id AND `order_type` = $type ");
    } else {
        $count = $wpdb->get_var("SELECT COUNT(user_id) FROM $wpdb->zibpay_order WHERE `status` = 1 and `user_id` = $user_id ");
    }
    return $count ? $count : 0;
}
/**
 * 用户中心统计信息
 */
function zibpay_get_user_pay_statistical($user_id)
{

    $count_all = zibpay_get_user_order_count($user_id);
    $count_t1 = zibpay_get_user_order_count($user_id, 1);
    $count_t2 = zibpay_get_user_order_count($user_id, 2);

    $sumprice_all = zibpay_get_user_pay_price($user_id, 'pay_price');
    $sumprice_t1 = zibpay_get_user_pay_price($user_id, 'pay_price', 1);
    $sumprice_t2 = zibpay_get_user_pay_price($user_id, 'pay_price', 2);

    $obj = array();
    $obj[] = array(
        '全部订单' => $count_all,
        '付费阅读' => $count_t1,
        '付费资源' => $count_t2,
        'unit' => '',
    );
    $obj[] = array(
        '支付金额' => $sumprice_all,
        '付费阅读' => $sumprice_t1,
        '付费资源' => $sumprice_t2,
        'unit' => '￥',
    );
    $con = '<div class="row">';
    foreach ($obj as  $val) {

        $con .= '<div class="col-sm-6">
            <div class="zib-widget pay-box">
                <div class="statistical-header">
                ' . array_keys($val)[0] . '
                </div>
                <div class="statistical-con">
                <span class="pay-mark">' . $val['unit'] . '</span>' . array_values($val)[0] . '
                </div>
                <div class="statistical-bottom muted-2-color">
                <span class="pay-mark">' . array_keys($val)[1] . '：' . $val['unit'] . '</span>' . array_values($val)[1] . '
                <span class="pay-mark ml10">' . array_keys($val)[2] . '：' . $val['unit'] . '</span>' . array_values($val)[2] . '
                </div>
            </div>
        </div>';
    };
    $con .= '</div>';

    return  $con;
}


/**
 * @description: 获取用户支付订单列表
 * @param int $user_id 用户ID：默认为当前登录ID
 * @param int $paged 获取的页码
 * @param int $ice_perpage 每页加载数量
 * @return {*}
 */
function zibpay_get_user_order($user_id = '', $paged = 1, $ice_perpage = 10)
{

    $user_id = $user_id ? $user_id : get_current_user_id();
    if (!$user_id || zibpay_get_post_down_array('down')) return;
    //准备查询参数
    $paged = !empty($_REQUEST['paged']) ? $_REQUEST['paged'] : $paged;
    $ice_perpage = !empty($_REQUEST['ice_perpage']) ? $_REQUEST['ice_perpage'] : $ice_perpage;
    $offset = $ice_perpage * ($paged - 1);

    global $wpdb;
    $db_order = $wpdb->get_results("SELECT * FROM $wpdb->zibpay_order WHERE `status` = 1 and `user_id` = $user_id  order by pay_time DESC limit $offset,$ice_perpage");
    $lists = '';
    if ($db_order) {
        $count_all = zibpay_get_user_order_count($user_id);
        $mark = _pz('pay_mark', '￥');

        foreach ($db_order as $order) {

            $order_num = $order->order_num;
            $order_price = $order->order_price;

            $pay_price = $order->pay_price;
            $pay_time = $order->pay_time;
            $post_id = $order->post_id;
            $order_type_name = zibpay_get_pay_type_name($order->order_type);
            $pay_mate = get_post_meta($post_id, 'posts_zibpay', true);
            $order_price = !empty($pay_mate['pay_original_price']) ? $pay_mate['pay_original_price'] : $order_price;

            $class = 'order-type-' . $order->order_type;

            $posts_title = get_the_title($post_id);
            $pay_title = !empty($pay_mate['pay_title']) ? $pay_mate['pay_title'] : $posts_title;
            $pay_title = '<a target="_blank" href="' . get_permalink($post_id) . '">' . $pay_title . '</a>';
            $pay_doc = '付款时间：' . $pay_time;
            $pay_doc .= '<span class="pull-right em12"><span class="pay-mark">价格：' . $mark . '</span>' . $order_price . '<span class="pay-mark ml10">实付金额：' . $mark . '</span>' . $pay_price . '</span>';

            $pay_num = '订单号：' . $order_num;

            $lists .= '<div class="zib-widget pay-box ajax-item ' . $class . '">';
            $lists .= $order_type_name ? '<div class="pay-tag abs-center">' . $order_type_name . '</div>' : '';
            $lists .= '<dl>';
            $lists .= '<div>' . $pay_title . '</div>';
            $lists .= '<div class="meta-time em09 muted-2-color">' . $pay_num . '</div>';
            $lists .= '<dd class="meta-time em09 muted-2-color">' . $pay_doc . '</dd>';
            $lists .= '</dl>';
            $lists .= '</div>';
        }

        // 显示下一页按钮
        $ajax_url = esc_url(add_query_arg('action', 'user_pay_order', admin_url('admin-ajax.php')));
        $lists .= zibpay_get_ajax_next_paging($count_all, $paged, $ice_perpage, $ajax_url);
    } else {
        $lists .= zib_get_ajax_null('暂无支付订单', 40, 'null-order.svg');
    }

    $html = $lists;
    return $html;
}
