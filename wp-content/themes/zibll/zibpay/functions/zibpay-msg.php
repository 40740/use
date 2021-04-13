<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-11-10 17:48:49
 * @LastEditTime: 2021-01-01 13:57:36
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

/**购买给用户发送邮件、消息 */
add_action('payment_order_success', 'zibpay_mail_payment_order');
function zibpay_mail_payment_order($values)
{

    /**根据订单号查询订单 */
    $pay_order = (array) $values;
    $user_id = $pay_order['user_id'];

    $udata = get_userdata($user_id);
    if (!$user_id || !$udata) return;

    $user_name = $udata->display_name;

    $pay_price = $pay_order['pay_price'];
    $pay_time = $pay_order['pay_time'];
    $blog_name = get_bloginfo('name');

    $_link = get_author_posts_url($user_id) . '?page=pay';
    $m_title = '订单支付成功-金额：￥' . $pay_price . '，订单号[' . $pay_order['order_num'] . ']';
    $title = '[' . $blog_name . '] ' . $m_title;

    $message = '您好！ ' . $user_name . "<br />";;
    $message .= '您在【' . $blog_name . '】购买的商品已支付成功' . "<br />";
    $message .= '订单号：' . $pay_order['order_num'] . "<br />";;
    $message .= '支付金额：￥' . $pay_price . "<br />";;
    $message .= '付款时间：' . $pay_time . "<br />";;
    $message .= "<br />";;
    $message .= '您可以打开下方链接查看订单详情' . "<br />";;
    $message .= '<a target="_blank" style="margin-top: 20px" href="' . esc_url($_link) . '">' . $_link . '</a>' . "<br />";

    $msg_arge = array(
        'send_user' => 'admin',
        'receive_user' => $user_id,
        'type' => 'pay',
        'title' => $m_title,
        'content' => $message,
        'meta' => '',
        'other' => '',
    );
    //创建新消息
    if (_pz('message_s', true)) {
        ZibMsg::add($msg_arge);
    }

    if (_pz('email_payment_order', true)) {
        /**获取用户邮箱 */
        $user_email = !empty($udata->user_email) ? $udata->user_email : '';
        /**如果没有 email或者email无效则终止*/
        if (!$user_email || stristr($user_email, '@no')) {
            return false;
        }
        /**发送邮件 */
        @wp_mail($user_email, $title, $message);
    }
}


/**购买给管理员发送邮件、消息 */
add_action('payment_order_success', 'zibpay_mail_payment_order_to_admin');
function zibpay_mail_payment_order_to_admin($values)
{
    /**根据订单号查询订单 */
    $pay_order = (array) $values;
    $user_id = $pay_order['user_id'];

    $udata = get_userdata($user_id);

    $user_name = !empty($udata->display_name) ? $udata->display_name : '';
    /**获取用户邮箱 */
    $user_email = !empty($udata->user_email) ? $udata->user_email : '';
    /**如果和管理员邮箱相同则不发送*/
    if ($user_email == get_option('admin_email')) {
        return false;
    }

    $pay_price = $pay_order['pay_price'];
    $pay_time = $pay_order['pay_time'];
    $blog_name = get_bloginfo('name');
    $order_type = zibpay_get_pay_type_name($pay_order['order_type']);

    $m_title = '有新的订单已支付-' . $order_type . '，金额：￥' . $pay_price . '，订单号[' . $pay_order['order_num'] . ']';
    $title = '[' . $blog_name . '] ' . $m_title;

    $message = '您的网站【' . $blog_name . '】有新的订单已支付！' . "<br />";
    $message .= '订单号：' . $pay_order['order_num'] . "<br />";
    $message .= '商品类型：' . $order_type . "<br />";
    $message .= '支付金额：￥' . $pay_price . "<br />";
    $message .= '收款方式：' . $pay_order['pay_type'] . "<br />";
    if ($user_name) {
        $message .= '购买用户：<a href="' . get_author_posts_url($user_id) . '" rel="noopener" target="_blank">' . $user_name . '</a>' . "<br />";
    }
    $message .= '付款时间：' . $pay_time . "<br />";

    if (!empty($pay_order['referrer_id']) && !empty($pay_order['rebate_price'])) {
        $message .= '推荐人：' . get_userdata($pay_order['referrer_id'])->display_name . "<br />";
        $message .= '推荐佣金：￥' . $pay_order['rebate_price'] . "<br />";
    }
    $message .= "<br />";;
    $message .= '您可以打开下方链接查看订单详情' . "<br />";
    $_link = admin_url('admin.php?page=zibpay_order_page&s=' . $pay_order['order_num']);
    $message .= '<a target="_blank" style="margin-top: 20px" href="' . esc_url($_link) . '">' . $_link . '</a>' . "<br />";

    $msg_arge = array(
        'send_user' => 'admin',
        'receive_user' => 'admin',
        'type' => 'pay',
        'title' => $m_title,
        'content' => $message,
        'meta' => '',
        'other' => '',
    );
    //创建新消息
    if (_pz('message_s', true)) {
        ZibMsg::add($msg_arge);
    }

    if (_pz('email_payment_order_to_admin', true)) {
        /**发送邮件 */
        @wp_mail(get_option('admin_email'), $title, $message);
    }
}


/**
 * @description: 订单支付成功给推荐人发邮件
 * @param array $values 订单数组
 * @return {*}
 */
add_action('payment_order_success', 'zibpay_mail_payment_order_to_referrer');
function zibpay_mail_payment_order_to_referrer($values)
{

    $pay_order = (array) $values;
    $referrer_id = $pay_order['referrer_id'];
    $rebate_price = $pay_order['rebate_price'];
    //如果没有推荐人或者返利金额则退出
    if (!$referrer_id || $rebate_price < 0.1) return false;
    $all_rebate_price = zibpay_get_user_rebate_price($referrer_id);

    $udata = get_userdata($referrer_id);
    if (!$udata) return;

    $user_name = $udata->display_name;
    $pay_time = $pay_order['pay_time'];
    $blog_name = get_bloginfo('name');

    $m_title = '恭喜您！获得一笔推广佣金：￥' . $rebate_price . '元';
    $title = '[' . $blog_name . '] ' . $m_title;

    $message = '您好！ ' . $user_name . "<br />";

    $message .= '恭喜您！在【' . $blog_name . '】获得一笔推荐佣金' . "<br />";
    $message .= '订单号：' . $pay_order['order_num'] . "<br />";
    $message .= '佣金金额：￥' . $rebate_price . "<br />";
    $message .= '时间：' . $pay_time . "<br />";
    $message .= "<br />";;
    $message .= '您当前的佣金总额：￥ <span style="font-size: 30px;color: #e95c86;">' . $all_rebate_price . '</span>' . "<br />";
    $message .= "<br />";;
    $message .= '您可以打开下方链接查看佣金详情' . "<br />";
    $_link = get_author_posts_url($referrer_id) . '?page=user';
    $message .= '<a target="_blank" style="margin-top: 20px" href="' . esc_url($_link) . '">' . $_link . '</a>' . "<br />";

    $msg_arge = array(
        'send_user' => 'admin',
        'receive_user' => $referrer_id,
        'type' => 'pay',
        'title' => $m_title,
        'content' => $message,
        'meta' => '',
        'other' => '',
    );
    //创建新消息
    if (_pz('message_s', true)) {
        ZibMsg::add($msg_arge);
    }
    if (_pz('email_payment_order_to_referrer', true)) {
        $user_email = $udata->user_email;

        /**判断邮箱状态 */
        if (!is_email($user_email) || stristr($user_email, '@no')) return false;

        /**发送邮件 */
        @wp_mail($user_email, $title, $message);
    }
}



/** 用户申请佣金提现给管理员发送邮件 */
if (_pz('email_apply_withdraw_to_admin', true)) {
    add_action('user_apply_withdraw', 'zibpay_mail_apply_withdraw_to_admin');
}
/**
 * @description: 用户申请佣金提现给管理员发送邮件
 * @param array $values 订单数组
 * @return {*}
 */
function zibpay_mail_apply_withdraw_to_admin($data)
{

    $blog_name = get_bloginfo('name');
    $title = '[' . $blog_name . '] ' . $data['title'];
    $message = $data['content'];

    /**发送邮件 */
    @wp_mail(get_option('admin_email'), $title, $message);
}


/**
 * @description: 处理用户提现之后创建新的用户消息
 * @param {*}
 * @return {*}
 */
add_action('withdraw_process', 'zib_withdraw_new_msg', 10, 2);
function zib_withdraw_new_msg($values, $up_status)
{
    $id = $values['id'];
    if (!$up_status || !$id) return false;
    //准备参数
    $msg_db = ZibMsg::get_row(array('id' => $id));

    $title = '已处理完成';
    if ($values['status'] == 2) {
        $title = '被拒绝';
    }
    $meta = (array)$msg_db->meta;
    $withdraw_price = !empty($meta['withdraw_price']) ? $meta['withdraw_price'] : "";
    //准备消息
    $msg_con = '';
    $msg_con .= '您好！您于' . date("Y年m月d日 H:i", strtotime($msg_db->create_time)) . ' 发起的提现申请' . $title . "<br />";
    $msg_con .= '提现金额：￥' . $withdraw_price . "<br />";
    $msg_con .= '申请时间：' . $msg_db->create_time . "<br />";
    $msg_con .= '处理时间：' . $msg_db->modified_time . "<br />";
    $msg_con .= "<br />";
    $msg_con .= $values['msg'] . "<br />";
    $msg_con .= "如有疑问请与管理员联系";

    $msg_arge = array(
        'send_user' => 'admin',
        'receive_user' => $msg_db->send_user,
        'type' => 'withdraw_reply',
        'title' => '您的提现申请' . $title . '，提现金额：￥' . $withdraw_price,
        'content' => $msg_con,
        'parent' => $values['id'],
        'meta' => '',
        'other' => '',
    );
    //创建新消息
    if (zib_msg_is_allow_receive($msg_db->send_user, 'withdraw_reply')) {
        ZibMsg::add($msg_arge);
    }
    do_action('withdraw_process_newmsg', $msg_arge, $msg_db);
}

/**
 * @description: 处理用户提现之后给用户发送邮件
 * @param array $data 消息数组
 * @return {*}
 */
add_action('withdraw_process_newmsg', 'zibpay_mail_withdraw_process');
function zibpay_mail_withdraw_process($data)
{
    if (!_pz('email_withdraw_process', true)) return false;
    $user_id = $data['receive_user'];
    $udata = get_userdata($user_id);
    /**判断邮箱状态 */
    $user_email = $udata->user_email;
    if (!is_email($user_email) || stristr($user_email, '@no')) return false;

    $blog_name = get_bloginfo('name');
    $title = '[' . $blog_name . '] ' . $data['title'];

    $message = $udata->display_name . "<br />" . $data['content'];

    /**发送邮件 */
    @wp_mail($udata->user_email, $title, $message);
}
