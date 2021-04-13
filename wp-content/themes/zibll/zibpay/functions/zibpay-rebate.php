<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-10-22 19:54:34
 * @LastEditTime: 2020-12-19 23:57:46
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

// 为数据库添加表

add_action('admin_init', 'zibpay_order_add_column');
function zibpay_order_add_column()
{
    global $wpdb;
    // 判断数据库推荐返利功能字段，无则添加
    if (!$wpdb->get_row("SELECT column_name FROM information_schema.columns WHERE table_name='$wpdb->zibpay_order' and column_name ='referrer_id'")) {
        @$wpdb->query("ALTER TABLE $wpdb->zibpay_order ADD referrer_id int(11) DEFAULT NULL COMMENT '推荐人id'");
        @$wpdb->query("ALTER TABLE $wpdb->zibpay_order ADD rebate_price double(10,2) DEFAULT NULL COMMENT '返利金额'");
        @$wpdb->query("ALTER TABLE $wpdb->zibpay_order ADD rebate_status varchar(255) DEFAULT 0 COMMENT '提现状态'");
        @$wpdb->query("ALTER TABLE $wpdb->zibpay_order ADD rebate_detail varchar(2550) DEFAULT NULL COMMENT '提现详情'");
    }
}

//保存返利id到缓存
if (_pz('pay_rebate_s')) {
    add_action('template_redirect', 'zibpay_save_referrer');
}
function zibpay_save_referrer()
{
    session_start();
    $aff = !empty($_REQUEST['ref']) ? $_REQUEST['ref'] : '';
    if ($aff) {
        $_SESSION['ZIBPAY_REFERRER_ID'] = $aff;
    }
}

//获取带返利的链接
function zibpay_get_rebate_link($user_id = '', $url = '')
{
    if (!$url) $url = home_url();
    if (!$user_id) $user_id = get_current_user_id();
    $rebate_id = $user_id;
    $rebate_url = esc_url(add_query_arg('ref', $rebate_id, $url));
    return esc_url($rebate_url);
}

/**
 * @description: 根据返利id查询用户id
 * @param int $user_id 用户ID（允许为空，为空则获取当前登录用户）
 * @param bool $return_args 是否返回用户数组
 * @return {*}
 */
function zibpay_get_referrer_id($user_id = '', $return_args = false)
{
    //首先查询用户保存的推荐人
    //根据主题设置识别模式判断是否根据推荐人返佣
    $referrer_id = '';
    if (_pz('pay_rebate_judgment') != 'link') {
        if (!$user_id) $user_id = get_current_user_id();
        if ($user_id) {
            $referrer_id = get_user_meta($user_id, 'referrer_id', true);
        }
        if ($referrer_id) {
            return $return_args ? get_userdata($referrer_id) : $referrer_id;
        }
    }

    //再根据缓存查询
    @session_start();
    $referrer_id = !empty($_SESSION['ZIBPAY_REFERRER_ID']) ? $_SESSION['ZIBPAY_REFERRER_ID'] : '';
    if ($referrer_id) {
        return $return_args ? get_userdata($referrer_id) : $referrer_id;
    }
    return false;
}

//注册时保存推荐人id
add_action('user_register', 'zibpay_save_user_referrer');
function zibpay_save_user_referrer($user_id)
{
    $referrer_id = zibpay_get_referrer_id();
    update_user_meta($user_id, 'referrer_id', absint($referrer_id));
}

/**
 * @description: 查询用户的返利比例,此函数已经效验了返利开关
 * @param int    $user_id 用户ID
 * @return int   array('type' => $rebate_s, 'ratio' => $rebate_ratio)
 */
function zibpay_get_user_rebate_rule($user_id)
{
    //查询独立设置的比例，有则返回
    $user_rebate_rule = get_user_meta($user_id, 'rebate_rule', true);
    if (!empty($user_rebate_rule['switch']) && !empty($user_rebate_rule['ratio'])) {
        if (empty($user_rebate_rule['type'])) return array('type' => false, 'ratio' => $user_rebate_rule['ratio']);
        $rebate_s = zibpay_user_rebate_type_format($user_rebate_rule['type']);
        return array('type' => $rebate_s, 'ratio' => $user_rebate_rule['ratio']);
    }

    //查询用户会员级别
    $vip_l = (int)zib_get_user_vip_level($user_id);
    if ($vip_l) {
        //如果是会员，查询会员功能是否开启，返回对应的比例
        $rebate_s = zibpay_user_rebate_type_format(_pz('rebate_rule', array(), 'pay_rebate_user_s_' . $vip_l));
        $rebate_ratio = (int)_pz('rebate_rule', array(), 'pay_rebate_ratio_vip_' . $vip_l);
        if ($rebate_s) return array('type' => $rebate_s, 'ratio' => $rebate_ratio);
    }

    //最后查询普通用户是否开启此功能
    $rebate_s = zibpay_user_rebate_type_format(_pz('rebate_rule', array(), 'pay_rebate_user_s'));
    $rebate_ratio = (int)_pz('rebate_rule', array(), 'pay_rebate_ratio');

    return array('type' => $rebate_s, 'ratio' => $rebate_ratio);
}

/**
 * @description: 格式化用户保存的返利订单模式
 * @param {*}
 * @return {*}
 */
function zibpay_user_rebate_type_format($array)
{
    if (!is_array($array)) return false;
    if (in_array('all', $array) || !empty($array['all'])) {
        return array('all');
    }
    if (count($array) == count($array, 1)) return $array;
    $rebate_type = array_keys($array, true);
    if (!empty($rebate_type[0])) {
        return $rebate_type;
    }
    return false;
}

/**
 * @description: 获取用户当前所有佣金
 * @param int $user_id 用户ID
 * @param string $type 'all'全部佣金 'effective'有效的 'invalid'无效的
 * @return int 金额
 */
function zibpay_get_user_rebate_price($user_id, $type = 'effective')
{
    global $wpdb;
    if ($type == 'effective') {
        $rebate_price = $wpdb->get_var($wpdb->prepare("SELECT SUM(rebate_price) FROM {$wpdb->zibpay_order} WHERE referrer_id = %s and status=1 and rebate_status=%d", $user_id, 0));
    } elseif ($type == 'invalid') {
        $rebate_price = $wpdb->get_var($wpdb->prepare("SELECT SUM(rebate_price) FROM {$wpdb->zibpay_order} WHERE referrer_id = %s and status=1 and rebate_status=%d", $user_id, 1));
    } else {
        $rebate_price = $wpdb->get_var($wpdb->prepare("SELECT SUM(rebate_price) FROM {$wpdb->zibpay_order} WHERE referrer_id = %s and status=1", $user_id));
    }
    return $rebate_price ? $rebate_price : 0;
}

/**
 * @description: 获取用户当前所有佣金订单的数量
 * @param int $user_id 用户ID
 * @param string $type 'all'全部佣金 'effective'有效的 'invalid'无效的
 * @return {*}
 */
function zibpay_get_user_rebate_count($user_id, $type = 'effective')
{
    global $wpdb;
    if ($type == 'effective') {
        $rebate_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(rebate_price) FROM {$wpdb->zibpay_order} WHERE referrer_id = %s and status=1 and rebate_status=%d", $user_id, 0));
    } elseif ($type == 'invalid') {
        $rebate_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(rebate_price) FROM {$wpdb->zibpay_order} WHERE referrer_id = %s and status=1 and rebate_status=%d", $user_id, 1));
    } else {
        $rebate_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(rebate_price) FROM {$wpdb->zibpay_order} WHERE referrer_id = %s and status=1", $user_id));
    }
    return $rebate_count ? $rebate_count : 0;
}

// 列出订单类型的名称的数组
function zibpay_user_rebate_type_options()
{
    return
        array(
            'all' => '全部订单',
            '1' => zibpay_get_pay_type_name(1),
            '2' => zibpay_get_pay_type_name(2),
            '4' => zibpay_get_pay_type_name(4),
        );
}

/**
 * @description: 获取用户允许的返利订单类型的文案
 * @param array $rebate_type 订单类型的数组
 * @param string $delimiter 分割字符
 * @return $name
 */
function zibpay_get_user_rebate_type($rebate_type, $delimiter = '<\br>')
{
    if (!$rebate_type || !is_array($rebate_type)) return '暂未参与';
    if (in_array('all', $rebate_type)) {
        $name = '全部订单';
    } else {
        $i = 1;
        $name = '';
        foreach ($rebate_type as $key) {
            $delimiter_1 = ($i != 1 ? $delimiter : '');
            $name .= $delimiter_1 . zibpay_get_pay_type_name($key);
            $i++;
        }
    }
    return $name;
}


//用户中心挂钩
if (_pz('pay_rebate_s')) {
    add_action('author_info_tab', 'zibpay_user_info_tab_rebate', 11);
    add_action('author_info_tab_con', 'zibpay_user_info_tab_rebate_con', 11);
}
function zibpay_user_info_tab_rebate($user_id)
{
    echo '<li class=""><a class="muted-2-color but hollow" data-toggle="tab" data-ajax="" href="#author-tab-rebate"><i class="fa fa-btc hide-sm fa-fw" aria-hidden="true"></i>推荐奖励</a></li>';
}
function zibpay_user_info_tab_rebate_con($user_id)
{
    $id = 'author-tab-rebate';
    $con_class = 'ajaxpager';
    $detail_ajax_href = esc_url(add_query_arg('action', 'author_tab_rebate', admin_url('admin-ajax.php')));

    $con = zibpay_user_content_rebate($user_id);

    $con = '';
    $con .= '<span class="post_ajax_trigger"><a ajax-href="' . $detail_ajax_href . '" class="ajax_load ajax-next ajax-open"></a></span>';
    $con .= '<div class="post_ajax_loader box-body"> <p class="placeholder t1"></p> <h4 class="item-excerpt placeholder k1"></h4><p class="placeholder k2"></p><i class="placeholder s1"></i><i class="placeholder s1 ml10"></i><div class="mt10"><i class="placeholder s1" style=" width: 30%; height: 120px;"></i><i class="placeholder s1 ml10" style=" width: 30%; height: 120px;"></i></div></div>';

    $con = '<div class="tab-pane fade ' . $con_class . '" id="' . $id . '">' . $con . '</div>';
    echo $con;
?>
<?php }

function zibpay_get_user_withdraw_ing($user_id)
{

    $msg_get_args = array(
        'send_user' => $user_id,
        'type' => 'withdraw',
        'status' => 0,
    );
    return ZibMsg::get_row($msg_get_args);
}



function zibpay_user_content_rebate($user_id = '')
{
    if (!$user_id) $user_id = get_current_user_id();
    if (!$user_id) return;
    $rebate_url = zibpay_get_rebate_link($user_id);
    $rebate_rule = zibpay_get_user_rebate_rule($user_id);
    $rebate_ratio = $rebate_rule['type'] ? ($rebate_rule['ratio'] ? $rebate_rule['ratio'] : 0) : 0;
    $rebate_type = zibpay_get_user_rebate_type($rebate_rule['type'], '<span class="icon-spot"></span>');

    //分类金额
    $rebate_price_all = zibpay_get_user_rebate_price($user_id, 'all');
    $rebate_price_effective = zibpay_get_user_rebate_price($user_id, 'effective');
    $rebate_price_invalid = zibpay_get_user_rebate_price($user_id, 'invalid');

    //分类计数
    $rebate_count_all = zibpay_get_user_rebate_count($user_id, 'all');
    $rebate_count_effective = zibpay_get_user_rebate_count($user_id, 'effective');
    $rebate_count_invalid = zibpay_get_user_rebate_count($user_id, 'invalid');

    //文案
    $text_desc = _pz('pay_rebate_text_desc');
    $text_details_title = _pz('pay_rebate_text_details_title', '返佣规则及说明');
    $text_details = _pz('pay_rebate_text_details');
    $pay_vip_but = '';
    //顶部标题
    $title = '<div class="box-body">';
    $title .= '<div class="title-h-left"><b>推荐奖励</b></div>';
    $title .= $text_desc ? '<div class="muted-2-color">' . $text_desc . '</div>' : '';
    $title .= '</div>';

    // 佣金比例卡片
    $card = '<div class="col-sm-6"><div class="zib-widget jb-red relative-h" style="background-size:120%;">';
    $card .= '<div class="absolute jb-red radius" style="height: 145%;left: 70%;width: 76%;top: -77%;"></div>';
    $card .= '<div class="absolute jb-red radius" style="height: 183%;width: 81%;left: -26%;border-radius: 300px;"></div>';
    $card .= '<div class="relative">';
    $card .= '<p class="opacity8">佣金比例</p>';
    $card .= '<p class="em12"><b style="font-size:2em;">' . $rebate_ratio . '</b> %</p>';
    $card .= '<div class="em09">' . $rebate_type . '</div>';
    $card .= '</div>';
    $card .= '</div></div>';

    // 累计佣金卡片
    // 显示提现中数据
    $withdraw_ing = (array)zibpay_get_user_withdraw_ing($user_id);
    // 提现按钮
    $withdraw_but = '';
    if ($rebate_count_effective) {
        $withdraw_but = '<a href="javascript:;" data-toggle="modal" data-target="#modal_apply_withdraw" data-remote="' . esc_url(add_query_arg('action', 'apply_withdraw_modal', admin_url('admin-ajax.php'))) . '" class="but radius c-white">立即提现<i style="margin:0 0 0 10px;" class="fa fa-angle-right"></i></a>';
        $withdraw_but = '<div class="abs-right">' . $withdraw_but . '</div>';
    }
    $c_dec = '<div class="em09">已提现￥' . $rebate_price_invalid . '<span class="icon-spot">待提现￥' . $rebate_price_effective . '</span></div>';
    if (!empty($withdraw_ing['meta']['withdraw_price'])) {
        $c_dec = '<div class="em09"><span data-toggle="tooltip" title="已申请提现，等待管理员处理">提现处理中￥' . $withdraw_ing['meta']['withdraw_price'] . '</span></div>';
    }
    // $card .= json_encode($withdraw_ing);
    $card .= '<div class="col-sm-6"><div class="zib-widget jb-blue relative-h">';
    $card .= '<div class="absolute jb-blue radius" style=" height: 150%; left: 50%; opacity: 0.5; top: 50%; width: 60%; "></div>';
    $card .= '<div class="absolute jb-blue radius" style=" height: 145%; left: -22%; opacity: .8; width: 89%; "></div>';
    $card .= '<div class="relative">';
    $card .= '<p class="opacity8">累计佣金</p>';
    $card .= '<p class="em12">￥ <b style="font-size:2em;">' . $rebate_price_all . '</b></p>';

    $card .= $c_dec;
    $card .= $withdraw_but;
    $card .= '</div>';
    $card .= '</div></div>';

    // tab按钮
    $tab_but = '';
    $tab_but .= '<li class="active"><a data-toggle="tab" href="#rebate_tab_main">佣金详情</a></li>';
    //佣金明细
    $tab_but .= '<li class=""><a data-toggle="tab" data-ajax="" href="#rebate_tab_detail">佣金明细</a></li>';
    //提现记录
    $tab_but .= '<li class=""><a data-toggle="tab" data-ajax="" href="#rebate_tab_withdraw">提现记录</a></li>';
    $tab_but = '<b><ul style="margin-bottom: 20px;" class="list-inline scroll-x mini-scrollbar tab-nav-theme">' . $tab_but . '</ul></b>';

    // tab-列表内容
    $tab_con = '';
    $info_lits = '';
    $info_lits .= '<div class="mb10 mt10"><div class="author-set-left">推广链接</div><div class="author-set-right"><b data-clipboard-text="' . $rebate_url . '" class="but mb10 c-red clip-aut mr10">' . $rebate_url . '</b><a data-clipboard-text="' . $rebate_url . '" class="clip-aut mb10 but c-yellow">复制链接</a></div></div>';
    $info_lits .= '<div class="mb20"><div class="author-set-left">佣金比例</div><div class="author-set-right"><b class="badg mr10 c-red-2">' . $rebate_ratio . '%</b>' . $pay_vip_but . '</div></div>';
    $info_lits .= '<div class="mb20"><div class="author-set-left">返佣订单</div><div class="author-set-right"><b class="badg">' . $rebate_type . '</b></div></div>';
    $info_lits .= '<div class="mb20"><div class="author-set-left">累计佣金</div><div class="author-set-right"><b class="badg c-blue mr6 mb6">累计￥' . $rebate_price_all . '</b><span class="badg mr6 mb6">已提现￥' . $rebate_price_invalid . '</span><span class="badg mr6 mb6">待提现￥' . $rebate_price_effective . '</span></div></div>';

    $info_lits = ZibPay::set_rebate_status(1, 'nill', 'obj') ? '' : $info_lits;
    $info_lits = '<div class="rebate-lits">' . $info_lits . '</div>';
    //返佣介绍
    $info_desc = $text_details ? '<div class="title-h-left mb10"><b>' . $text_details_title . '</b></div><div class="muted-color">' . $text_details . '</div>' : 0;

    $tab_con .= '<div class="tab-pane fade active in" id="rebate_tab_main">' . $info_lits . $info_desc . '</div>';

    //佣金明细
    $detail = '';
    $detail_ajax_href = esc_url(add_query_arg('action', 'rebate_detail', admin_url('admin-ajax.php')));
    //头部按钮
    if ($rebate_count_all) {
        //如果有佣金订单 则显示按钮和加载动画
        $detail .= '<div class="mb20">';
        $detail .= '<a ajax-replace="1" ajax-href="' . $detail_ajax_href . '" class="but mr10 ajax-next">全部 ' . $rebate_count_all . '</a>';
        $detail .= $rebate_count_invalid ? '<a ajax-replace="1" ajax-href="' . esc_url(add_query_arg('rebate_status', '1', $detail_ajax_href)) . '" class="but ajax-next mr10">已提现 ' . $rebate_count_invalid . '</a>' : '<span class="badg mr10">已提现 ' . $rebate_count_invalid . '</span>';
        $detail .= $rebate_count_effective ? '<a ajax-replace="1" ajax-href="' . esc_url(add_query_arg('rebate_status', '0', $detail_ajax_href)) . '" class="but ajax-next mr10">未提现 ' . $rebate_count_effective . '</a>' : '<span class="badg mr10">未提现 ' . $rebate_count_effective . '</span>';
        $detail .= '</div>';
        $detail .= '<span class="post_ajax_trigger"><a ajax-href="' . $detail_ajax_href . '" class="ajax_load ajax-next ajax-open"></a></span>';
        $detail .= '<div class="post_ajax_loader"> <i class="placeholder s1" style=" height: 20px; "></i><i class="placeholder s1 ml10" style=" height: 20px; width: 120px; "></i> <p class="placeholder k1"></p><p class="placeholder k2 mb20"></p> <i class="placeholder s1" style=" height: 20px; "></i><i class="placeholder s1 ml10" style=" height: 20px; width: 120px; "></i> <p class="placeholder k1"></p><p class="placeholder k2"></p> </div>';
    } else {
        //如果没有则显示无
        $detail .=  zib_get_null('暂无佣金订单', 40, 'null-money.svg');
    }

    $tab_con .= '<div class="tab-pane fade ajaxpager" id="rebate_tab_detail">' . $detail . '</div>';

    //提现记录AJAX tab-content
    $withdraw = '';
    $withdraw_ajax_href = esc_url(add_query_arg('action', 'withdraw_detail', admin_url('admin-ajax.php')));

    $msg_get_args = array(
        'send_user' => $user_id,
        'type' => 'withdraw',
    );
    $withdraw_count_all = ZibMsg::get_count($msg_get_args);
    $msg_get_args['status'] = 1;
    $withdraw_count_1 = ZibMsg::get_count($msg_get_args);
    if ($withdraw_count_all) {
        //如果提现记录 则显示按钮和加载动画
        $withdraw .= '<div class="mb20">';
        $withdraw .= '<a ajax-replace="1" ajax-href="' . $withdraw_ajax_href . '" class="but mr10 ajax-next">全部 ' . $withdraw_count_all . '</a>';
        $withdraw .= '<a ajax-replace="1" ajax-href="' . add_query_arg('status', 1, $withdraw_ajax_href) . '" class="but mr10 ajax-next">已处理 ' . $withdraw_count_1 . '</a>';
        $withdraw .= '<a ajax-replace="1" ajax-href="' . add_query_arg('status', 0, $withdraw_ajax_href) . '" class="but mr10 ajax-next">待处理</a>';
        $withdraw .= '</div>';

        $withdraw .= '<span class="post_ajax_trigger"><a ajax-href="' . $withdraw_ajax_href . '" class="ajax_load ajax-next ajax-open"></a></span>';
        $withdraw .= '<div class="post_ajax_loader"><i class="placeholder s1 mt10" style=" height: 27px; "></i><i class="placeholder s1 ml10" style=" height: 27px; width: calc(100% - 81px); "></i> <i class="placeholder s1 mt10" style=" height: 27px; "></i><i class="placeholder s1 ml10" style=" height: 27px; width: calc(100% - 81px); "></i> <i class="placeholder s1 mt10" style=" height: 27px; "></i><i class="placeholder s1 ml10" style=" height: 27px; width: calc(100% - 81px); "></i></div>';
        //如果没有显示无
    } else {
        //如果没有则显示无
        $withdraw .=  zib_get_null('暂无提现记录', 40, 'null-money.svg');
    }
    $tab_con .= '<div class="tab-pane fade ajaxpager" id="rebate_tab_withdraw">' . $withdraw . '</div>';

    //汇总tab_con内容
    $tab_con = '<div class="tab-content">' . $tab_con . '</div>';

    $html = '<div class="theme-box">';
    $html .= $title;
    $html .= '<div class="row box-body nobottom">' . $card . '</div>';
    $html .= '<div class="box-body">' . $tab_but . $tab_con . '</div>';

    $html .= '</div>';

    //申请提现的模态框
    $html .= zib_get_blank_modal(array('id' => 'modal_apply_withdraw', 'class' => 'apply-withdraw'));
    return $html;
}


/**
 * @description: 管理员处理提现
 * @param {*}
 * @return {*}
 */
function zibpay_withdraw_process($values)
{
    $defaults = array(
        'id' => '',
        'status' => '',
        'msg' => '',
        'orders' => '',
    );
    $values = wp_parse_args((array) $values, $defaults);
    //删除波折号\\
    $values = wp_unslash($values);
    $values['orders'] = @json_decode($values['orders']);

    //更新当前消息的状态
    $up_status = ZibMsg::set_status($values['id'], $values['status']);
    //保存管理员留言
    ZibMsg::set_meta($values['id'], 'admin_message', $values['msg']);
    if ($up_status && $values['status'] == 1) {
        //继续更新订单状态
        $up_status = ZibPay::set_rebate_status($values['orders'], 1);
    }
    //添加处理挂钩
    do_action('withdraw_process', $values, $up_status);
    return $up_status;
}
