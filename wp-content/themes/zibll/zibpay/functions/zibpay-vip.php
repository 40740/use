<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-09-29 13:18:50
 * @LastEditTime: 2021-01-06 23:31:20
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

/**
 * @description: 用户中心的会员内容
 * @param {*}
 * @return {*}
 */
function zibpay_user_vip_tab_con($user_id)
{
?>
    <div class="tab-pane fade in active" id="author-tab-vip">
        <div class="theme-box user-pay">
            <div class="box-body">
                <?php
                echo zibpay_user_vip_box($user_id);
                ?>
            </div>
        </div>
    </div>
<?php
}

/**
 * @description: 判断用户可以购买的会员类型
 * @param {*}
 * @return {*}
 */
function zibpay_is_pay_vip_type($user_id = 0)
{
    if (!$user_id) $user_id = get_current_user_id();
    $vip_level = get_user_meta($user_id, 'vip_level', true);
    $vip_exp_date = get_user_meta($user_id, 'vip_exp_date', true);
    $zero1 = current_time("Y-m-d h:i:s");

    $type = array(
        'pay' => false,
        'renew' => false,
        'upgrade' => false
    );
    if ($vip_level && (strtotime($zero1) < strtotime($vip_exp_date) || $vip_exp_date == 'Permanent')) {
        //如果已经是会员
        if ($vip_exp_date != 'Permanent' && _pz('vip_opt', true, 'vip_renew')) {
            //续费
            if (_pz('pay_user_vip_' . $vip_level . '_s', true)) {
                $type['renew'] = $vip_level;
            }
        }
        if ($vip_level < 2 && _pz('vip_opt', true, 'vip_upgrade') && _pz('pay_user_vip_2_s', true)) {
            //升级
            if ($vip_exp_date == 'Permanent') {
                $type['upgrade'] = 'Permanent';
            } else {
                $type['upgrade'] = 'unit';
            }
        }
    } else {
        if (_pz('pay_user_vip_1_s', true)) {
            $type['pay'] = 1;
        }
        if (_pz('pay_user_vip_2_s', true)) {
            $type['pay'] = 2;
        }
    }
    return $type;
}

/**
 * @description: 用户中心的会员卡片
 * @param {*}
 * @return {*}
 */
function zibpay_user_vip_box($user_id)
{
    $vip_level = get_user_meta($user_id, 'vip_level', true);
    $vip_exp_date = get_user_meta($user_id, 'vip_exp_date', true);
    $zero1 = current_time("Y-m-d h:i:s");
    $vip_name = array(1 => _pz('pay_user_vip_1_name'), 2 => _pz('pay_user_vip_2_name'));
    if ($vip_level) {
        if (strtotime($zero1) < strtotime($vip_exp_date) || $vip_exp_date == 'Permanent') {

            $vip_desc = $vip_exp_date == 'Permanent' ? '永久有效' : '到期时间：' . date("Y年m月d日", strtotime($vip_exp_date));
            echo '<div class="title-h-left"><b>我的会员</b></div>';
            echo '<div class="muted-2-color c-red">已开通' . $vip_name[$vip_level] . '，' . $vip_desc . '</div>';
            echo '<div class="row gutters-5 mt10 mb20">';
            echo '<div class="col-sm-8">';
            echo zibpay_get_viped_card($vip_level);
            echo '</div>';
            echo '</div>';
            //已经开通会员且还未到期
            $renew_card = false;
            $upgrade_card = false;
            if ($vip_exp_date != 'Permanent' && _pz('vip_opt', true, 'vip_renew')) {
                //续费会员
                $renew_card = zibpay_get_vip_card($vip_level, array('type' => 'renew'));
            }
            if ($vip_level < 2 && _pz('vip_opt', true, 'vip_upgrade')) {
                //如果当前会员等级小于2，则显示升级
                $upgrade_card = zibpay_get_vip_card(2, array('type' => 'upgrade'));
            }

            if ($renew_card || $upgrade_card) {
                $title = '续费或升级';
                if (!$upgrade_card) $title = '续费会员';
                if (!$renew_card) $title = '升级会员';
                echo '<div class="title-h-left mt20"><b>' . $title . '</b></div>';
                echo '<div class="row gutters-5 mt10">';
                if ($renew_card) {
                    echo '<div class="col-sm-6">';
                    echo $renew_card;
                    echo '</div>';
                }
                if ($upgrade_card) {
                    echo '<div class="col-sm-6">';
                    echo $upgrade_card;
                    echo '</div>';
                }
                echo '</div>';
            }
        } else {
            echo '<div class="title-h-left"><b>续费会员</b></div>';
            echo '<div class="muted-2-color c-red">您的' . _pz('pay_user_vip_' . $vip_level . '_name') . '已过期，过期时间：' . date("Y年m月d日", strtotime($vip_exp_date)) . '</div>';
            echo '<div class="row gutters-5 mt10">';
            echo '<div class="col-sm-6">';
            echo zibpay_get_vip_card(1);
            echo '</div>';
            echo '<div class="col-sm-6">';
            echo zibpay_get_vip_card(2);
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<div class="title-h-left"><b>开通会员</b></div><div class="muted-2-color c-red">' . _pz('pay_user_vip_desc') . '</div>';
        echo '<div class="row gutters-5 mt10">';
        echo '<div class="col-sm-6">';
        echo zibpay_get_vip_card(1);
        echo '</div>';
        echo '<div class="col-sm-6">';
        echo zibpay_get_vip_card(2);
        echo '</div>';
        echo '</div>';
    }
}

/**
 * @description: 会员续费商品明细
 * @param int $vip_level 会员等级
 * @return {*}
 */
function zibpay_get_vip_renew_product($vip_level)
{
    //获取优惠类型
    $renew_type = _pz('vip_opt', 'discount', 'vip_renew_price_type');
    //活动商品数组
    if ($renew_type == 'customize') {
        $renew_product_args = (array)_pz('vip_opt', 'discount', 'vip_' . $vip_level . '_renew_product');
    } else {
        $renew_product_args = (array)_pz('vip_opt', '', 'vip_' . $vip_level . '_product');
    }
    $renew_product = array();
    $i = 1;
    foreach ($renew_product_args as $vip_product) {
        $price = round($vip_product['price'], 2);
        if (!$price) {
            continue;
        }
        $discount_tag = '';
        if ($renew_type == 'discount') {
            $vip_renew_discount = round(_pz('vip_opt', 8, 'vip_renew_discount'), 1);
            $discount_tag = $vip_renew_discount . '折优惠';
            $price = round(($price * $vip_renew_discount / 10), 2);
        } elseif ($renew_type == 'reduce') {
            $vip_renew_reduce = round(_pz('vip_opt', 10, 'vip_renew_reduce'), 1);
            $price = round(($price - $vip_renew_reduce), 2);
            if ($price <= 0) {
                $price = 0.01;
            }
            $discount_tag = '立减' . (round($vip_product['price'], 2) - $price) . '元';
        }
        $time = (int)$vip_product['time'];
        $time_tag = $time ? $time . '个月' : '永久';
        if ($price <= 0.01) {
            $price = 0.01;
        }
        $renew_product[$i] = array(
            'renew_type' => $renew_type,
            'price' => $price,
            'show_price' => ($price < $vip_product['price'] ? $vip_product['price'] : ''),
            'tag' => ($price < $vip_product['price'] ? $discount_tag : ''),
            'time' => $time,
            'time_tag' => $time_tag,
            'tag_class' => !empty($vip_product['tag_class']) ? $vip_product['tag_class'] : ''
        );
        $i++;
    }
    return $renew_product;
}

/**
 * @description: 获取会员升级的商品
 * @param int $user_id 用户id
 * @param {*} $upgrade_type 升级类型
 * @return {*}
 */
function zibpay_get_vip_upgrade_product($user_id = 0, $upgrade_type = false)
{
    if (!$user_id) $user_id = get_current_user_id();
    $vip_exp_date = get_user_meta($user_id, 'vip_exp_date', true);

    if (!$upgrade_type  || !in_array($upgrade_type, array('Permanent', 'permanent', 'unit'))) {
        $is_pay_type = zibpay_is_pay_vip_type($user_id);
        $upgrade_type = $is_pay_type['upgrade'];
    }

    $vip_upgrade_product = _pz('vip_opt', '', 'vip_upgrade_product');
    $upgrade_product = array();
    if (in_array($upgrade_type, array('Permanent', 'permanent'))) {
        //永久会员升级
        $time_left = 'Permanent';
        $upgrade_product[1] = array(
            'price' => round($vip_upgrade_product['permanent_price'], 2),
            'show_price' => $vip_upgrade_product['permanent_show_price'],
            'tag' => $vip_upgrade_product['permanent_tag'],
            'tag_class' => $vip_upgrade_product['permanent_tag_class'],
            'time_tag' => '永久会员',
            'time' => 'Permanent',
        );
    } else {
        //月费会员升级
        $time_left = (strtotime($vip_exp_date) - strtotime(current_time('Y-m-d h:i:s'))) / 84600;
        if ($time_left < 1) {
            $time_left = 1;
        } else {
            $time_left = round($time_left);
        }
        $price = round(($time_left * $vip_upgrade_product['unit_price']), 2);

        $upgrade_product[1] = array(
            'price' => $price,
            'show_price' => '',
            'tag' => $vip_upgrade_product['unit_tag'],
            'tag_class' => $vip_upgrade_product['unit_tag_class'],
            'time_tag' => $time_left . '天',
            'time' => $time_left,
        );
        if (!empty($vip_upgrade_product['jump_s'])) {
            //如果允许跨越升级
            $upgrade_product[2] = array(
                'price' => round($vip_upgrade_product['jump_price'], 2),
                'show_price' => round($vip_upgrade_product['jump_show_price'], 2),
                'tag' => $vip_upgrade_product['jump_tag'],
                'tag_class' => $vip_upgrade_product['jump_tag_class'],
                'time_tag' => '永久会员',
                'time' => 'Permanent',
            );
        }
    }
    return $upgrade_product;
}

/**
 * @description: 会员升级或者续费的购买模态框内容
 * @param {*}
 * @return {*}
 */
function zibpay_get_pay_userviped_content()
{
    global $current_user;
    $user_id = $current_user->ID;
    $vip_level = zib_get_user_vip_level($user_id);

    if (!$vip_level) return;
    //公共参数
    $mark = _pz('pay_mark', '￥');
    $vip_more = '<div class="muted-2-color mb10 em09">' . _pz('pay_user_vip_more') . '</div>';
    $pay_button = zibpay_get_initiate_pay_button();
    $blog_name = get_bloginfo('name');
    $_POST_vip_level = !empty($_POST['vip_level']) ? $_POST['vip_level'] : 1;

    //准备续费产品
    $renew_tab = '';
    $renew_tab_con = '';
    $is_pay_type = zibpay_is_pay_vip_type($user_id);
    if ($is_pay_type['renew']) {
        $renew_tab = '<li class="relative ' . ($vip_level == $_POST_vip_level ? ' active' : '') . '">
                    <a class="" data-toggle="tab" href="#tab-payvip-' . $vip_level . '">' . zibpay_get_vip_card_mini($vip_level, 'renew') . '</a><div class="abs-right active-icon"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
                </li>';
        $renew_product_args =  zibpay_get_vip_renew_product($vip_level);
        $renew_product = '';
        foreach ($renew_product_args as $product_i => $vip_product) {
            $price = $vip_product['price'];
            $show_price = $vip_product['show_price'];
            $show_price = $show_price ? '<span class="original-price ml10 relative">' . $mark . $show_price . '' : '</span>';
            $price = '<div class="product-price c-red">' . $mark . $price . $show_price . '</div>';

            $vip_tag = $vip_product['tag'];
            $vip_tag = $vip_tag ? '<div class="abs-right vip-tag ' . $vip_product['tag_class'] . '">' . $vip_tag . '</div>' : '';

            $vip_time = '<div class="muted-2-color">' . $vip_product['time_tag'] . '</div>';

            $renew_product .= '<label>';
            $renew_product .= '<input class="hide vip-product-input" type="radio" name="vip_product_id" value="renewvip_' . $vip_level . '_' . $product_i . '"' . ($product_i == 1 ? ' checked="checked"' : '') . '>';
            $renew_product .= '<div class="zib-widget vip-product relative text-center product-box">';
            $renew_product .= $vip_tag . $price . $vip_time;
            $renew_product .= '</div>';
            $renew_product .= '</label>';
        }

        $vip_icon = '<div class="payvip_icon mb10"><p>' . zibpay_get_vip_card_icon($vip_level) . '</p>' . _pz('pay_user_vip_' . $vip_level . '_name') . '</div>';
        $vip_equity = '<ul class="payvip_equity mt10">' . _pz('vip_opt', '', 'pay_user_vip_' . $vip_level . '_equity') . '</ul>';
        $renew_tab_con = '<div class="tab-pane fade' . ($vip_level == $_POST_vip_level ? ' active in' : '') . '" id="tab-payvip-' . $vip_level . '">';
        $renew_tab_con .= '<form>';
        $renew_tab_con .= '<div class="row">';
        $renew_tab_con .= '<div class="col-sm-5 text-center theme-box">' . $vip_icon . $vip_equity . '</div>';
        $renew_tab_con .= '<div class="col-sm-7">';
        $renew_tab_con .= '<div class="mb10">' . $renew_product . '</div>';
        $renew_tab_con .= $vip_more . $pay_button;
        $renew_tab_con .= '</div>';
        $renew_tab_con .= '</div>';
        $renew_tab_con .= '<input type="hidden" name="order_name" value="' . '续费' . _pz('pay_user_vip_' . $vip_level . '_name') . '-' . $blog_name . '">';
        $renew_tab_con .= '<input type="hidden" name="order_type" value="4">';
        $renew_tab_con .= '<input type="hidden" name="action" value="initiate_pay">';
        $renew_tab_con .= '</form>';
        $renew_tab_con .= '</div>';
    }
    //续费结束

    //准备升级产品
    $upgrade_desc = '';
    $upgrade_tab_con = '';
    $upgrade_tab = '';
    if ($is_pay_type['upgrade']) {
        $upgrade_tab = '<li class="relative ' . (2 == $_POST_vip_level ? ' active' : '') . '">
                            <a class="" data-toggle="tab" href="#tab-payvip-2">' . zibpay_get_vip_card_mini(2, 'upgrade') . '</a><div class="abs-right active-icon"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
                        </li>';

        $upgrade_product_html = '';
        //如果用户会员小于2级，则显示升级
        $upgrade_product_args = zibpay_get_vip_upgrade_product($user_id, $is_pay_type['upgrade']);
        //$upgrade_product_html = json_encode($upgrade_product );
        foreach ($upgrade_product_args as $product_i => $upgrade_product) {
            $price = $upgrade_product['price'];
            $show_price = $upgrade_product['show_price'];
            $show_price = $show_price ? '<span class="original-price ml10 relative">' . $mark . $show_price . '' : '</span>';
            $price = '<div class="product-price c-red">' . $mark . $price . $show_price . '</div>';

            $vip_tag = $upgrade_product['tag'];
            $vip_tag = $vip_tag ? '<div class="abs-right vip-tag ' . $upgrade_product['tag_class'] . '">' . $vip_tag . '</div>' : '';

            $vip_time = '<div class="muted-2-color">' . $upgrade_product['time_tag'] . '</div>';

            $upgrade_product_html .= '<label>';
            $upgrade_product_html .= '<input class="hide vip-product-input" type="radio" name="vip_product_id" value="upgradevip_2_' . $product_i . '"' . ($product_i == 1 ? ' checked="checked"' : '') . '>';
            $upgrade_product_html .= '<div class="zib-widget vip-product relative text-center product-box">';
            $upgrade_product_html .= $vip_tag . $price . $vip_time;
            $upgrade_product_html .= '</div>';
            $upgrade_product_html .= '</label>';
        }

        $vip_icon = '<div class="payvip_icon mb10"><p>' . zibpay_get_vip_card_icon(2) . '</p>' . _pz('pay_user_vip_2_name') . '</div>';
        $vip_equity = '<ul class="payvip_equity mt10">' . _pz('vip_opt', '', 'pay_user_vip_2_equity') . '</ul>';
        $upgrade_tab_con = '<div class="tab-pane fade' . (2 == $_POST_vip_level ? ' active in' : '') . '" id="tab-payvip-2">';
        $upgrade_tab_con .= '<form>';
        $upgrade_tab_con .= '<div class="row">';
        $upgrade_tab_con .= '<div class="col-sm-5 text-center theme-box">' . $vip_icon . $vip_equity . '</div>';
        $upgrade_tab_con .= '<div class="col-sm-7">';
        $upgrade_tab_con .= '<div class="mb10">' . $upgrade_product_html . '</div>';
        $upgrade_tab_con .= $vip_more . $pay_button;
        $upgrade_tab_con .= '</div>';
        $upgrade_tab_con .= '</div>';
        $upgrade_tab_con .= '<input type="hidden" name="order_name" value="' . '升级' . _pz('pay_user_vip_2_name') . '-' . $blog_name . '">';
        $upgrade_tab_con .= '<input type="hidden" name="order_type" value="4">';
        $upgrade_tab_con .= '<input type="hidden" name="action" value="initiate_pay">';
        $upgrade_tab_con .= '</form>';
        $upgrade_tab_con .= '</div>';
    }

    //构建HTML
    $html = '';
    $avatar = zib_get_data_avatar($current_user->ID);
    $user_name = $current_user->display_name;
    $vip_desc = '<span class="badg c-yellow vip-expdate-tag">' . zib_get_user_vip_exp_date_text($user_id) . '</span>';
    $html .= '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">' . zib_svg('close') . '</button>';
    $html .= '<ul class="list-inline user-box"><li><div class="avatar-img">' . $avatar . '</div></li><li><b>' . $user_name . zib_svg('vip_' . $vip_level, '0 0 1024 1024', 'em12 ml6') . '</b><div class="em09">' . $vip_desc . '</div></li></ul>';
    $html .= '<ul class="list-inline mt10 theme-box vip-cardminis">' . $renew_tab . $upgrade_tab . '</ul>';
    $html .= '<div class="tab-content mt10">' . $renew_tab_con . $upgrade_tab_con . '</div>';

    $html = '<div class="box-body payvip-modal">' . $html . '</div>';
    return $html;
}

/**
 * @description: 购买会员的模态框构建
 * @param {*}
 * @return {*}
 */
function zibpay_pay_uservip_modal()
{
    if (!is_user_logged_in()) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '请先登录')));
        exit();
    }

    $vip_level = !empty($_POST['vip_level']) ? $_POST['vip_level'] : 1;

    global $current_user;
    $avatar = zib_get_data_avatar($current_user->ID);
    $user_vip_level = zib_get_user_vip_level($current_user->ID);
    $user_name = $current_user->display_name;

    if ($user_vip_level) {
        $con = zibpay_get_pay_userviped_content($user_vip_level, $current_user->ID);
        if (!$con) {
            echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '获取出错')));
        } else {
            echo (json_encode(array('error' => 0, 'html' => $con)));
        }
        exit();
    }

    $vip_desc = _pz('pay_user_vip_desc');

    $user_info = '';
    $vip_more = '<div class="muted-2-color mb10 em09">' . _pz('pay_user_vip_more') . '</div>';
    $mark = _pz('pay_mark', '￥');
    $mark = '<span class="pay-mark">' . $mark . '</span>';
    $payment = zibpay_get_default_payment();

    $tab_c = '';
    $tab_t = '';
    $con = '';

    for ($vi = 1; $vi <= 2; $vi++) {
        if (!_pz('pay_user_vip_' . $vi . '_s', true)) {
            continue;
        }
        $card_args = array();
        $tab_t .= '<li class="relative ' . ($vip_level == $vi ? ' active' : '') . '">
                        <a class="" data-toggle="tab" href="#tab-payvip-' . $vi . '">' . zibpay_get_vip_card_mini($vi) . '</a><div class="abs-right active-icon"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
                    </li>';

        $vip_icon = '<div class="payvip_icon mb10"><p>' . zibpay_get_vip_card_icon($vi) . '</p>' . _pz('pay_user_vip_' . $vi . '_name') . '</div>';
        $vip_equity = '<ul class="payvip_equity mt10">' . _pz('vip_opt', '', 'pay_user_vip_' . $vi . '_equity') . '</ul>';

        $vip_product_args = (array)_pz('vip_opt', '', 'vip_' . $vi . '_product');

        $vip_product_html = '';
        $product_i = 0;

        foreach ($vip_product_args as $vip_product) {
            $price = round($vip_product['price'], 2);
            if (!$price) {
                continue;
            }

            $show_price = round($vip_product['show_price'], 2);
            $show_price = $show_price ? '<span class="original-price ml10 relative">' . $mark . $show_price . '' : '</span>';

            $price = '<div class="product-price c-red">' . $mark . $price . $show_price . '</div>';
            $vip_tag = $vip_product['tag'];
            $vip_tag = $vip_tag ? '<div class="abs-right vip-tag ' . (!empty($vip_product['tag_class']) ? $vip_product['tag_class'] : '') . '">' . $vip_tag . '</div>' : '';

            $vip_time = (int)$vip_product['time'];
            if ($vip_time) {
                $vip_time = $vip_time . '个月';
            } else {
                $vip_time = '永久';
            }
            $vip_time = '<div class="muted-2-color">' . $vip_time . '</div>';

            $vip_product_html .= '<label>';
            $vip_product_html .= '<input class="hide vip-product-input" type="radio" name="vip_product_id" value="payvip_' . $vi . '_' . $product_i . '"' . ($product_i == 0 ? ' checked="checked"' : '') . '>';
            $vip_product_html .= '<div class="zib-widget vip-product relative text-center product-box">';
            $vip_product_html .= $vip_tag . $price . $vip_time;
            $vip_product_html .= '</div>';
            $vip_product_html .= '</label>';
            $product_i++;
        }

        $order_name = get_bloginfo('name') . '-开通' . _pz('pay_user_vip_' . $vi . '_name');
        $payvip_form = '<input type="hidden" name="order_name" value="' . $order_name . '">
        <input type="hidden" name="order_type" value="4">
        <input type="hidden" name="action" value="initiate_pay">';

        $pay_button = zibpay_get_initiate_pay_button();
        $tab_c .= '<div class="tab-pane fade' . ($vip_level == $vi ? ' active in' : '') . '" id="tab-payvip-' . $vi . '">
                        <form>
                            <div class="row">
                                <div class="col-sm-5 text-center theme-box">' . $vip_icon . $vip_equity . '</div>
                                <div class="col-sm-7"><div class="mb10">' . $vip_product_html . '</div>' . $vip_more . $pay_button . '</div>
                            </div>
                            ' . $payvip_form . '
                        </form>
                    </div>';
    }

    $con .= '<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><svg class="ic-close" viewBox="0 0 1024 1024"><path d="M573.44 512.128l237.888 237.696a43.328 43.328 0 0 1 0 59.712 43.392 43.392 0 0 1-59.712 0L513.728 571.84 265.856 819.712a44.672 44.672 0 0 1-61.568 0 44.672 44.672 0 0 1 0-61.568L452.16 510.272 214.208 272.448a43.328 43.328 0 0 1 0-59.648 43.392 43.392 0 0 1 59.712 0l237.952 237.76 246.272-246.272a44.672 44.672 0 0 1 61.568 0 44.672 44.672 0 0 1 0 61.568L573.44 512.128z"></path></svg></button>';
    $con .= '<ul class="list-inline user-box"><li><div class="avatar-img">' . $avatar . '</div></li><li><b>' . $user_name . '</b><div class="c-red em09">' . $vip_desc . '</div></li></ul>';
    $con .= '<ul class="list-inline mt10 theme-box vip-cardminis">' . $tab_t . '</ul>';
    $con .= '<div class="tab-content mt10">' . $tab_c . '</div>';

    $con = '<div class="box-body payvip-modal">' . $con . '</div>';

    echo (json_encode(array('error' => 0, 'html' => $con)));
    exit();
}
add_action('wp_ajax_pay_vip', 'zibpay_pay_uservip_modal');
add_action('wp_ajax_nopriv_pay_vip', 'zibpay_pay_uservip_modal');

/**付款成功后后更新用户数据 */
function zibpay_uservip_paysuccess($values)
{
    $pay_order = $values;
    if (empty($pay_order->user_id) || empty($pay_order->product_id) || $pay_order->order_type != 4) return false;

    $vip_product_id = explode("_", $pay_order->product_id);
    if (!isset($vip_product_id[0]) || !isset($vip_product_id[1]) || !isset($vip_product_id[2]) || $vip_product_id[0] != 'vip') return false;

    $pay_vip_level = (int) $vip_product_id[1];
    $pay_vip_product = (int) $vip_product_id[2];

    $pay_vip_action = $vip_product_id[3];

    //获取用户本来的会员等级
    $user_id = $pay_order->user_id;
    $user_vip_level = zib_get_user_vip_level($user_id);
    $user_vip_exp_date = get_user_meta($pay_order->user_id, 'vip_exp_date', true);
    $new_date = current_time('Y-m-d h:i:s');

    if ($pay_vip_action == 'renew') {
        //续费
        $renew_product_args =  zibpay_get_vip_renew_product($pay_vip_level);
        $pay_vip_time = $renew_product_args[$pay_vip_product]['time'];
        if ($pay_vip_time == 'Permanent') {
            //永久会员选项
            $new_vip_exp_date = 'Permanent';
        } else {
            //续费根据用户现有时间追加
            $new_vip_exp_date = date("Y-m-d 23:59:59", strtotime("+" . $pay_vip_time . "months", strtotime($user_vip_exp_date)));
        }
    } elseif ($pay_vip_action == 'upgrade') {
        //升级
        $upgrade_product_args =  zibpay_get_vip_upgrade_product($user_id);
        $pay_vip_time = $upgrade_product_args[$pay_vip_product]['time'];
        $new_vip_exp_date = $pay_vip_time == 'Permanent' ? 'Permanent' : $user_vip_exp_date;
    } else {
        //购买会员的商品选项
        $pay_product_args = (array)_pz('vip_opt', '', 'vip_' . $pay_vip_level . '_product');
        $pay_vip_time = (int)$pay_product_args[$pay_vip_product]['time'];

        if ($pay_vip_time == 0) {
            //永久会员选项
            $new_vip_exp_date = 'Permanent';
        } else {
            $new_vip_exp_date = date("Y-m-d 23:59:59", strtotime("+" . $pay_vip_time . "months", strtotime($new_date)));
        }
    }
    update_user_meta($pay_order->user_id, 'vip_exp_date', $new_vip_exp_date);
    update_user_meta($pay_order->user_id, 'vip_level', $pay_vip_level);
}
add_action('payment_order_success', 'zibpay_uservip_paysuccess', 9);



/**
 * @description: 获取VIP用户徽章卡片
 * @param {*}
 * @return {*}
 */
function zibpay_get_viped_card($level = 1, $args = array())
{
    $defaults = array(
        'type' => 'auto',
    );

    $args = wp_parse_args((array) $args, $defaults);

    $icon =  zibpay_get_vip_card_icon($level);
    $img =  '<div class="vip-img abs-right">' . $icon . '</div>';
    $name = '<div class="vip-name mb10"><span class="mr6">' . $icon . '</span>' . _pz('pay_user_vip_' . $level . '_name') . '</div>';

    $button = '<a class="but jb-blue radius payvip-button">开通' . _pz('pay_user_vip_' . $level . '_name') . '</a>';

    $ba_icon = '<div class="abs-center vip-baicon">' . $icon . '</div>';
    $vip_equity = '<ul class="mb10">' . _pz('vip_opt', '', 'pay_user_vip_' . $level . '_equity') . '</ul>';

    $card = '<div class="vip-card level-' . $level . ' ' . zibpay_get_vip_theme($level) . '" vip-level="' . $level . '">
    ' . $ba_icon . $name . $vip_equity . $img . '
    </div>';
    return $card;
}

function zibpay_get_vip_card($level = 1, $args = array())
{
    if (!_pz('pay_user_vip_' . $level . '_s', true))  return;
    $defaults = array(
        'type' => 'auto',
    );

    $args = wp_parse_args((array) $args, $defaults);

    if ($args['type'] == 'renew') {
        $button_text = '续费';
        $desc = _pz('vip_opt', '', 'vip_renew_desc');
    } elseif ($args['type'] == 'upgrade') {
        $button_text = '升级';
        $desc = _pz('vip_opt', '', 'vip_upgrade_desc');
    } else {
        $button_text = '开通';
        $desc = '';
    }

    $icon =  zibpay_get_vip_card_icon($level);

    $vip_name =  _pz('pay_user_vip_' . $level . '_name');
    $action_class = is_user_logged_in() ? ' pay-vip' : ' signin-loader';
    $img =  '<div class="vip-img abs-right">' . $icon . '</div>';
    $name = '<div class="vip-name mb10"><span class="mr6">' . $icon . '</span>' . $vip_name . '</div>';
    $name .= $desc ? '<div class="mb6">' . $desc . '</div>' : '';
    $button = '<a class="but jb-blue radius payvip-button">' . $button_text . $vip_name . '</a>';

    $ba_icon = '<div class="abs-center vip-baicon">' . $icon . '</div>';
    $vip_equity = '<ul class="mb10">' . _pz('vip_opt', '', 'pay_user_vip_' . $level . '_equity') . '</ul>';

    $card = '<div class="vip-card pointer level-' . $level . ' ' . zibpay_get_vip_theme($level) . $action_class . '" vip-level="' . $level . '">
    ' . $ba_icon . $name . $vip_equity . $button . $img . '
    </div>';
    return $card;
}






function zibpay_get_vip_card_mini($level = 1, $type = '')
{

    $tax = '';
    if ($type == 'renew') {
        $tax = '续费';
    } elseif ($type == 'upgrade') {
        $tax = '升级';
    }
    $icon =  zibpay_get_vip_card_icon($level);
    $name = '<div class="vip-icon">' . $icon . '</div><div class="vip-name">' . $tax . _pz('pay_user_vip_' . $level . '_name') . '</div>';

    $ba_icon = '<div class="abs-center vip-baicon">' . $icon . '</div>';

    $card = '<div class="vip-card vip-cardmini level-' . $level . ' ' . zibpay_get_vip_theme($level) . '">
    ' . $ba_icon . $name . '
    </div>';
    return $card;
}

function zibpay_get_payvip_button($level = 1, $class = 'but jb-yellow', $text = '立即开通')
{
    $button = '<a class="pay-vip ' . $class . '" href="" vip-level="' . $level . '">' . $text . '</a>';
    return $button;
}

function zibpay_get_vip_card_icon($level = 1, $class = "icon em12", $tip = false)
{
    $icon = $level == 1 ? '<i class="fa fa-star-o" aria-hidden="true"></i>' : '<i class="fa fa-diamond" aria-hidden="true"></i>';
    return $icon;
}

function zibpay_get_vip_icon($level = 1, $class = "em12 ml3", $tip = 1)
{
    if (!$level) return;
    $icon = zib_svg('vip_' . $level, '0 0 1024 1024', $class);
    return $tip ? '<span data-toggle="tooltip" title="' . _pz('pay_user_vip_' . $level . '_name') . '">' . $icon . '</span>' : '<span class="vip-texticon">' . $icon . _pz('pay_user_vip_' . $level . '_name') . '</span>';
}

function zibpay_get_payvip_icon($user_id = 0, $class = '', $text = '开通会员')
{
    if (!$user_id || (!_pz('pay_user_vip_1_s', true) && !_pz('pay_user_vip_2_s', true))) return;

    $current_user_id = get_current_user_id();
    $vip_level = zib_get_user_vip_level($user_id);
    if ($vip_level) {
        return zibpay_get_vip_icon($vip_level);
    } elseif ($user_id == $current_user_id) {
        $button = '<a class="pay-vip but jb-red radius4 payvip-icon ' . $class . '" href="javascript:;">' . zib_svg('vip_1', '0 0 1024 1024', 'em12 mr10') . $text . '</a>';
        return $button;
    }
}

function zibpay_get_vip_theme($level = 1)
{
    $icon = $level == 1 ? 'vip-theme1' : 'vip-theme2';
    return $icon;
}

/**
 * @description: 获取用户会员等级
 * @param {*}
 * @return {*}
 */
function zib_get_user_vip_level($user_id = 0)
{
    if (!$user_id) $user_id = get_current_user_id();
    if (!$user_id) return false;
    //update_user_meta($user_id, 'vip_level', 0);
    //update_user_meta($user_id, 'vip_exp_date', '');
    $vip_level = (int)get_user_meta($user_id, 'vip_level', true);
    if (!$vip_level) return false;
    /**如果对应的会员等级关闭则返回false */
    if (!_pz('pay_user_vip_' . $vip_level . '_s', true)) return false;
    $vip_exp_date = get_user_meta($user_id, 'vip_exp_date', true);
    $zero1 = current_time("Y-m-d h:i:s");
    //对比vip时间是否过期
    if ($vip_level && $vip_exp_date == 'Permanent') return $vip_level;
    return ($vip_level && (strtotime($zero1) < strtotime($vip_exp_date))) ? $vip_level : false;
}

/**
 * @description: 获取用户VIP到期时间的文案
 * @param {*}
 * @return {*}
 */
function zib_get_user_vip_exp_date_text($user_id = 0)
{

    if (!$user_id) $user_id = get_current_user_id();
    if (!$user_id) return false;
    $vip_level = (int)get_user_meta($user_id, 'vip_level', true);
    /**如果对应的会员等级关闭则返回false */
    if (!_pz('pay_user_vip_' . $vip_level . '_s', true)) return false;

    $vip_exp_date = get_user_meta($user_id, 'vip_exp_date', true);
    $zero1 = current_time("Y-m-d h:i:s");
    if (!$vip_exp_date) return false;

    if ($vip_exp_date == 'Permanent') return '永久会员';
    return ((strtotime($zero1) < strtotime($vip_exp_date))) ? date("Y年m月d日", strtotime($vip_exp_date)) : '会员已过期';
}


/**
 * @description: 获取网站的会员数量
 * @param {*}
 * @return {*}
 */
function zib_get_vip_user_count($vip_level = 0)
{

    $meta_query = array();
    $meta_query['relation'] = 'AND';
    if ($vip_level == 1) {
        $meta_query[] = array(
            'key'     => 'vip_level',
            'value'   => 1,
            'compare' => '='
        );
    } elseif ($vip_level == 2) {
        $meta_query[] = array(
            'key'     => 'vip_level',
            'value'   => 2,
            'compare' => '='
        );
    } elseif (!$vip_level) {
        $meta_query[] = array(
            'relation' => 'OR',
            array(
                'key'     => 'vip_level',
                'value'   => 1,
                'compare' => '='
            ),
            array(
                'key'     => 'vip_level',
                'value'   => 2,
                'compare' => '='
            ),
        );
    } else {
        return false;
    }
    $meta_query[] = array(
        'relation' => 'OR',
        array(
            'key'     => 'vip_exp_date',
            'value'   => 'Permanent',
            'compare' => '='
        ),
        array(
            'key'     => 'vip_exp_date',
            'value'   => current_time("Y-m-d h:i:s"),
            'compare' => '>='
        )
    );
    $args = array(
        'meta_query' => $meta_query,
        'count_total '      => true
    );
    $user_query = new WP_User_Query($args);
    $all_count = $user_query->get_total();
    return $all_count ? $all_count : 0;
}
