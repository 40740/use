<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-09-29 13:18:50
 * @LastEditTime: 2021-01-07 23:23:17
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

//引入函数文件
require_once plugin_dir_path(__FILE__) . 'class/order-class.php';
//require_once(get_theme_file_path('/framework/includes/class-posts-meta.php'));

foreach (array(
    'zibpay-ajax',
    'zibpay-download',
    'zibpay-user',
    'zibpay-vip',
    'zibpay-rebate',
    'ajax',
    'rebate-ajax',
    'zibpay-msg',
) as $php) {
    require_once plugin_dir_path(__FILE__) . 'functions/' . $php . '.php';
}

if (is_admin()) {
    require_once plugin_dir_path(__FILE__) . 'functions/admin/admin.php';
}
/**挂钩到主题启动 */
function zibpay_creat_table_order()
{
    ZibPay::create_db();
}
add_action('admin_head', 'zibpay_creat_table_order');

/**
 * 创建后台管理菜单
 */
function add_settings_menu()
{
    add_menu_page('Zibll商城', 'Zibll商城', 'administrator',  'zibpay_page', 'zibpay_page', 'dashicons-cart');
    add_submenu_page('zibpay_page', '商品明细', '商品明细', 'administrator', 'zibpay_product_page', 'zibpay_product_page');
    add_submenu_page('zibpay_page', '订单明细', '订单明细', 'administrator', 'zibpay_order_page', 'zibpay_order_page');
    add_submenu_page('zibpay_page', '返佣明细', '佣金明细', 'administrator', 'zibpay_rebate_page', 'zibpay_rebate_page');
    if (_pz('pay_rebate_s')) {
        add_submenu_page('zibpay_page', '佣金提现', '佣金提现', 'administrator', 'zibpay_withdraw', 'zibpay_withdraw_page');
    }
    add_submenu_page('zibpay_page', '会员管理', '会员管理', 'administrator', 'users.php', '');
}
add_action('admin_menu', 'add_settings_menu');

function zibpay_page()
{
    require_once get_stylesheet_directory() . '/zibpay/page/index.php';
}
function zibpay_order_page()
{
    require_once get_stylesheet_directory() . '/zibpay/page/order.php';
}
function zibpay_product_page()
{
    require_once get_stylesheet_directory() . '/zibpay/page/product.php';
}
function zibpay_rebate_page()
{
    require_once get_stylesheet_directory() . '/zibpay/page/rebate.php';
}
function zibpay_withdraw_page()
{
    require_once get_stylesheet_directory() . '/zibpay/page/withdraw.php';
}

/**
 * 排队插入JS文件
 */
add_action('admin_enqueue_scripts', 'zibpay_setting_scripts');
function zibpay_setting_scripts()
{
    if (isset($_GET['page']) && stristr($_GET['page'], "zibpay")) {
        wp_enqueue_style('zibpay_page', get_template_directory_uri() . '/zibpay/assets/css/pay-page.css');
        wp_enqueue_script('highcharts', get_template_directory_uri() . '/zibpay/assets/js/highcharts.js', array('jquery'));
        wp_enqueue_script('westeros', get_template_directory_uri() . '/zibpay/assets/js/westeros.min.js', array('jquery', 'highcharts'));
        wp_enqueue_script('zibpay_page', get_template_directory_uri() . '/zibpay/assets/js/pay-page.js', array('jquery', 'jquery_form'));
    }
}

/**文章内容底部插入产品 */
function zibpay_posts_pay_content($post)
{
    $pay_mate = get_post_meta($post->ID, 'posts_zibpay', true);
    $payment = _pz('default_payment', 'wechat');

    if (empty($pay_mate['pay_type']) || $pay_mate['pay_type'] == 'no') return;

    // 查询是否已经购买
    $paid = zibpay_is_paid($post->ID);

    if ($paid) {
        echo zibpay_posts_paid_box($pay_mate, $paid, $post->ID);
    } else {
        echo zibpay_posts_pay_box($pay_mate, $post->ID);
    }
}
add_action('zib_posts_content_after', 'zibpay_posts_pay_content');

/**文章已经付费盒子 */
function zibpay_posts_paid_box($pay_mate, $paid, $post_ID = '')
{

    $pay_doc = '';
    $pay_num = '';
    $paid_name = zibpay_get_paid_type_name($paid['paid_type']);
    $paid_name = '<b class="badg jb-red mr6" style="font-size: 12px; padding: 2px 10px; line-height: 1.4; "><i class="fa fa-check mr6" aria-hidden="true"></i>' . $paid_name . '</b>';
    $_thumb = zib_post_thumbnail('', 'fit-cover radius8');

    $order_type_name = zibpay_get_pay_type_name($pay_mate['pay_type']);
    $order_type_class = 'order-type-' . $pay_mate['pay_type'];

    $posts_title = get_the_title() . get_the_subtitle(false);
    $pay_title = !empty($pay_mate['pay_title']) ? $pay_mate['pay_title'] : $posts_title;
    $pay_title = '<b>' . $pay_title . '</b>';
    $mark = _pz('pay_mark', '￥');
    $mark = '<span class="pay-mark">' . $mark . '</span>';
    if ($paid['paid_type'] == 'paid' && !empty($paid['post_id'])) {
        //已经购买的状态
        $price = round($pay_mate['pay_price'], 2);
        $pay_doc = '付款时间：' . $paid['pay_time'];
        $pay_doc .= '<span class="pull-right em12">' . $mark . $price . '</span>';

        $pay_num = '订单号：' . zibpay_get_order_num_link($paid['order_num']);
        $pay_title = $paid_name . $pay_title;
    } elseif (strstr($paid['paid_type'], 'vip')) {
        //会员免费资源
        $hide = $paid['paid_type'] == 'vip2_free' ? 2 : 1;
        $original_price = empty($pay_mate['pay_original_price']) ? '' : $pay_mate['pay_original_price'];
        if ($original_price) {
            $original_price = '<span class="original-price mr10">' . $mark . $original_price . '</span>';
        }

        $price = round($pay_mate['pay_price'], 2);

        $price_con = $mark . $price;
        $vip_price = zibpay_get_posts_vip_price($pay_mate, $hide);
        $vip_price = $vip_price ? '<span class="ml10">' . $vip_price . '</span>' : '';

        $pay_title = '<div class="mb10">' . $pay_title . '</div>';

        $pay_title .= '<div class="pay-box-price"><b>' . $original_price . $price_con . $vip_price . '</b></div>';
        $pay_title .= '<div>' . $paid_name . '</div>';
    } elseif ($paid['paid_type'] == 'free') {
        $pay_title = '<div class="mb10">' . $pay_title . '</div>';
        if ($pay_mate['pay_type'] == '1') {
            $pay_title .= '<div class="badg">' . (!empty($pay_mate['pay_doc']) ? $pay_mate['pay_doc'] : '免费阅读资源') . '</div>';
        }
        $order_type_name = '免费资源';
    } else {
        $pay_title = '<div class="mb10">' . $pay_title . '</div><div>' . $paid_name . '</div>';
    }

    $pay_details = !empty($pay_mate['pay_details']) ? '<div class="pay-details">' . $pay_mate['pay_details'] . '</div>' : '';
    $pay_extra_hide = !empty($pay_mate['pay_extra_hide']) ? '<div class="pay-extra-hide">' . $pay_mate['pay_extra_hide'] . '</div>' : '';

    $dowmbox = '';
    if ($pay_mate['pay_type'] == '2') {
        $dowmbox = '<div class="hidden-box show"><div class="hidden-text"><i class="fa fa-download mr6" aria-hidden="true"></i>资源下载</div>' . zibpay_get_post_down_buts($pay_mate) . '</div>';
    }
    if ($paid['paid_type'] == 'free' && _pz('pay_free_logged_show') && !is_user_logged_in()) {
        $dowmbox = '';
        $pay_extra_hide = '';
        if (zib_is_close_sign()) {
            $pay_title .= '<div class="mt10"><span class="c-red px12"><i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;登录功能已关闭，暂时无法查看此资源</span></div>';
        } else {
            $pay_title .= '<div class="mt10"><a class="c-red signin-loader" href="javascript:;"><i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;免费资源，请登录后查看</a></div>';
        }
    } elseif (_pz('pay_down_alone_page') && $pay_mate['pay_type'] == '2') {
        $dowmbox = '';
        $pay_extra_hide = '';
        //$pay_details = '';
        $pay_doc .= '<div>
        <a target="_blank" href="' . zib_get_template_page_url('pages/download.php') . '?post=' . $post_ID . '" class="but jb-yellow mt10 padding-lg"><i class="fa fa-download mr10" aria-hidden="true"></i>资源下载</a>
        </div>';
    }
    $con = '<div class="zib-widget pay-box ' . $order_type_class . '" id="posts-pay">
                <div class="pay-tag abs-center">
                ' . $order_type_name . '
                </div>
						<ul class="list-inline">
						    <li>
						        <div class="pay-thumbnail">
						            ' . $_thumb . '
						        </div>
						    </li>
						    <li>
						        <dl>
                                <div class="">' . $pay_title . '</div>
						            <div class="meta-time em09 muted-2-color mt6">' . $pay_num . '</div>
						            <dd class="meta-time em09 muted-2-color">' . $pay_doc . '</dd>
						        </dl>
						    </li>
                        </ul>
                        ' . $dowmbox . $pay_extra_hide . $pay_details . '
            </div>';

    return $con;
}
function zibpay_posts_free_box($pay_mate, $paid)
{
}
/**文章付费盒子 */
function zibpay_get_pay_type_name($pay_type)
{
    $order_type_name = '';
    if ($pay_type == '1') {
        $order_type_name = '付费阅读';
    } elseif ($pay_type == '2') {
        $order_type_name = '付费资源';
    } elseif ($pay_type == '3') {
        $order_type_name = '产品购买';
    } elseif ($pay_type == '4') {
        $order_type_name = '购买会员';
    }
    return $order_type_name;
}

/**获取支付按钮html */
function zibpay_get_initiate_pay_button($text = '立即购买', $wechat_text = '微信购买', $alipay_text = '支付宝购买', $class = 'radius')
{

    $pay_wechat_sdk = _pz('pay_wechat_sdk_options');
    $pay_alipay_sdk = _pz('pay_alipay_sdk_options');
    $payment = zibpay_get_default_payment();

    $pay_button = '<button class="but jb-red initiate-pay ml10 mr6" pay_type="' . $payment . '"><i class="fa fa-angle-right mr6" aria-hidden="true"></i>' . $text . '</button>';

    if (wp_is_mobile() || _pz('pay_show_allbut')) {
        $pay_button = '';
        if ($pay_alipay_sdk && $pay_alipay_sdk != 'null') {
            $pay_button .= '<button class="but jb-blue initiate-pay ml10" pay_type="alipay"><i class="fa fa-angle-right mr6" aria-hidden="true"></i>' . $alipay_text . '</button>';
        }
        if ($pay_wechat_sdk && $pay_wechat_sdk != 'null') {
            $pay_button .= '<button class="but jb-green initiate-pay ml10 mr6" pay_type="wechat"><i class="fa fa-angle-right mr6" aria-hidden="true"></i>' . $wechat_text . '</button>';
        }
    }
    return $pay_button;
}

/**获取默认支付方式 */
function zibpay_get_default_payment()
{
    $payment = _pz('default_payment', 'wechat');
    $pay_wechat_sdk = _pz('pay_wechat_sdk_options');
    $pay_alipay_sdk = _pz('pay_alipay_sdk_options');
    if ($payment == 'wechat' && (!$pay_wechat_sdk || $pay_wechat_sdk == 'null')) $payment = 'alipay';
    if ($payment == 'alipay' && (!$pay_alipay_sdk || $pay_alipay_sdk == 'null')) $payment = 'wechat';

    return $payment;
}

function zibpay_get_posts_vip_price($pay_mate, $hide = 0)
{
    $mark = _pz('pay_mark', '￥');
    $mark = '<span class="em09">' . $mark . '</span>';
    $user_id = get_current_user_id();
    $action_class = '';
    if ($user_id) {
        if (!zib_get_user_vip_level($user_id)) {
            $action_class = ' pay-vip';
        }
    } else {
        $action_class = ' signin-loader';
    }
    $vip_price_con = '';
    $price = isset($pay_mate['pay_price']) ? round($pay_mate['pay_price'], 2) : 0;

    for ($vi = 1; $vi <= 2; $vi++) {
        if (!_pz('pay_user_vip_' . $vi . '_s', true) || $hide == $vi) {
            continue;
        }
        $vip_price = !empty($pay_mate['vip_' . $vi . '_price']) ? round($pay_mate['vip_' . $vi . '_price'], 2) : 0;
        //会员价格与正常价格取最小值
        $vip_price = $vip_price < $price ? $vip_price : $price;

        $vip_price = $vip_price ? $vip_price : '免费';
        $vip_icon = zib_svg('vip_' . $vi, '0 0 1024 1024', 'mr3') . _pz('pay_user_vip_' . $vi . '_name');
        $vip_price_con .= '<a href="javascript:;" class="ml10 vip-price but jb-vip' . $vi . $action_class . '" vip-level="' . $vi . '">' . $vip_icon . ' ' . $vip_price . '</a>';
    }
    return $vip_price_con;
}


/**
 * @description: 构建文章付费模块
 * @param array $pay_mate
 * @param int $post_id
 * @return {*}
 */
function zibpay_posts_pay_box($pay_mate, $post_id)
{
    $pay_mate = $pay_mate ? $pay_mate : get_post_meta($post_id, 'posts_zibpay', true);
    if (empty($pay_mate['pay_type']) || $pay_mate['pay_type'] == 'no') return;

    $_thumb = zib_post_thumbnail('', 'fit-cover radius8');

    $order_type_name = zibpay_get_pay_type_name($pay_mate['pay_type']);
    $order_type_class = 'order-type-' . $pay_mate['pay_type'];

    $cuont = '';
    if (_pz('pay_show_paycount', true)) {
        global $wpdb;
        $cuont = $wpdb->get_var("SELECT COUNT(id) FROM $wpdb->zibpay_order where post_id=$post_id and status=1");
        $cuont = !empty($pay_mate['pay_cuont']) ? round($pay_mate['pay_cuont'], 0) + $cuont : $cuont;
        $cuont = $cuont > 0 ? $cuont : 0;
        $cuont = '<span class="pay-cuont badg c-green hollow">已售 ' . $cuont . '</span>';
    }
    $mark = _pz('pay_mark', '￥');
    $mark = '<span class="pay-mark">' . $mark . '</span>';

    $order_name = get_bloginfo('name') . '-' . $order_type_name;
    $price = round($pay_mate['pay_price'], 2);

    $price_con = $mark . $price;
    //会员价格
    $vip_price = zibpay_get_posts_vip_price($pay_mate);
    $vip_price = $vip_price ? '<dd class="pay-box-price">' . $vip_price . '</dd>' : '';

    // 推荐返佣、让利功能
    if (_pz('pay_rebate_s')) {
        $referrer_id = zibpay_get_referrer_id();
        if ($referrer_id) {
            //查询到推荐人
            //返利规则
            $rebate_ratio = zibpay_get_user_rebate_rule($referrer_id);
            if (
                !empty($pay_mate['pay_rebate_discount'])
                && $rebate_ratio['type']
                && is_array($rebate_ratio['type'])
                && (in_array('all', $rebate_ratio['type']) || in_array($pay_mate['pay_type'], $rebate_ratio['type']))
            ) {
                // 设置标签文案
                $referrer_data = get_userdata($referrer_id);
                $discount_tag = _pz('pay_rebate_text_discount');
                $discount_tag =  str_replace('%discount%', $pay_mate['pay_rebate_discount'], $discount_tag);
                $discount_tag =  str_replace('%referrer_name%', $referrer_data->display_name, $discount_tag);
                $vip_price .= '<dt class="pay-box-price"><span class="badg jb-red px12">' . $discount_tag . '</span></dt>';
            };
        }
    }

    $original_price = empty($pay_mate['pay_original_price']) ? '' : $pay_mate['pay_original_price'];
    if ($original_price) {
        $original_price = '<span class="original-price mr10">' . $mark . $original_price . '</span>';
    }

    $pay_wechat_sdk = _pz('pay_wechat_sdk_options');
    $pay_alipay_sdk = _pz('pay_alipay_sdk_options');

    $posts_title = get_the_title() . get_the_subtitle(false);
    $pay_title = !empty($pay_mate['pay_title']) ? $pay_mate['pay_title'] : $posts_title;

    $pay_doc = !empty($pay_mate['pay_doc']) ? $pay_mate['pay_doc'] : '此内容为' . $order_type_name . '，请付费后查看';
    $pay_details = !empty($pay_mate['pay_details']) ? '<div class="pay-details">' . $pay_mate['pay_details'] . '</div>' : '';

    $remind = '';
    $pay_button = zibpay_get_initiate_pay_button();
    if (!is_user_logged_in()) {
        if (!_pz('pay_no_logged_in', true)) {
            if (zib_is_close_sign()) {
                $pay_button = '<span class="badg px12 c-yellow-2">登录功能已关闭，暂时无法购买，请与站长联系</span>';
            } else {
                $pay_button = '<a href="javascript:;" class="but jb-blue signin-loader ml10 mr6"><i class="fa fa-angle-right mr6" aria-hidden="true"></i>登录购买</a>';
            }
        } else {
            $remind =  '<div class="pay-extra-hide">' . _pz('pay_no_logged_remind') . '</div>';
        }
    }

    if ((!$pay_wechat_sdk || $pay_wechat_sdk == 'null') && (!$pay_alipay_sdk || $pay_alipay_sdk == 'null')) {
        $pay_button = '<span class="badg px12 c-yellow-2">暂时无法购买，请与站长联系</span>';
        if (is_super_admin()) {
            $pay_button = '<a href="' . zib_get_admin_csf_url('商城付费') . '" class="but c-red mr6">请先配置收款方式！</a>';
        }
    }

    $con = '<div class="zib-widget pay-box ' . $order_type_class . '" id="posts-pay">
        <div class="pay-tag abs-center">
        ' . $order_type_name . '
        </div>

        <form class="pay-form">

						<ul class="list-inline">
						    <li>
						        <div class="pay-thumbnail">
						            ' . $_thumb . '
						        </div>
						    </li>
						    <li>
						        <dl>
						        <dt class="">' . $pay_title . '</dt>
                                <dt class="pay-box-price">' . $original_price . $price_con . $cuont . '</dt>
                                ' . $vip_price . '
						        <dd class="meta-time em09 muted-2-color">' . $pay_doc . '</dd>
						        </dl>
						    </li>
                        </ul>' . $remind . '
						    <div class="pay-button">
						   		 ' . $pay_button . '
                            </div>
                            ' . $pay_details . '

            <input type="hidden" name="post_id" value="' . $post_id . '">
            <input type="hidden" name="order_name" value="' . $order_name . '">
            <input type="hidden" name="order_type" value="' . $pay_mate['pay_type'] . '">
            <input type="hidden" name="action" value="initiate_pay">
        </form>
    </div>';

    return $con;
}

/**扫码付款模态框 */
function zibpay_qrcon_pay_modal($args = array())
{
    $defaults = array(
        'class' => '',
        'payment' => 'wechat',
        'order_price' => '<i class="loading px12"></i>',
        'order_name' => '<i class="placeholder s1" style=" height: 18px; width: 60%; "></i>',
        'user_vip' => zib_get_user_vip_level(),
    );
    $args = wp_parse_args((array) $args, $defaults);

    $class = 'pay-payment ' . $args['payment'];
    $class .= ' ' . $args['class'];
    $pay_wechat_sdk = _pz('pay_wechat_sdk_options');
    $pay_alipay_sdk = _pz('pay_alipay_sdk_options');
    $pay_switch_button = '';
    if ($pay_alipay_sdk && $pay_alipay_sdk != 'null') {
        $pay_switch_button .= '<button class="but c-blue btn-block hollow t-alipay initiate-pay-switch" pay_type="alipay">切换支付宝付款</button>';
    }
    if ($pay_wechat_sdk && $pay_wechat_sdk != 'null') {
        $pay_switch_button .= '<button class="but c-green btn-block hollow t-wechat initiate-pay-switch" pay_type="wechat">切换微信付款</button>';
    }

    $alipay_sys = get_template_directory_uri() . '/zibpay/assets/img/alipay-sys.png';
    $wechat_sys = get_template_directory_uri() . '/zibpay/assets/img/wechat-sys.png';

    $qrcode_defaults = get_template_directory_uri() . '/zibpay/assets/img/pay-qrcode.png';

    $vip_tag = $args['user_vip'] ? '<span data-toggle="tooltip" title="' . _pz('pay_user_vip_' . $args['user_vip'] . '_name') . '" class="mr6">' . zibpay_get_vip_icon($args['user_vip']) . '</span>' : '';

    $con = '<div class="modal fade" id="modal_pay" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog" role="document">
            <div class="' . $class . '">
                <div class="modal-body modal-pay-body">
                    <div class="row-5 hide-sm">
                        <img class="pay-sys lazyload t-wechat" data-src="' . $alipay_sys . '">
                        <img class="pay-sys lazyload t-alipay" data-src="' . $wechat_sys . '">
                    </div>
                    <div class="row-5">
                    <div class="pay-qrcon">
                        <div class="qrcon">
                            <div class="pay-logo-header theme-box"><span class="pay-logo"></span><span class="pay-logo-name t-wechat">支付宝</span><span class="pay-logo-name t-alipay">微信支付</span></div>
                            <div class="pay-title em09 muted-2-color">' . $args['order_name'] . '</div>
                            <div>' . $vip_tag . '<span class="em09">￥</span><span class="pay-price em12">' . $args['order_price'] . '</span></div>
                            <div class="pay-qrcode">
                                <img src="' . $qrcode_defaults . '">
                            </div>
                        </div>
                    <div class="pay-switch">' . $pay_switch_button . '</div>
                    <div class="pay-notice"><div class="notice load">正在生成订单，请稍候</div></div>
                    </div>
				</div>
                </div>
            </div>
        </div>
    </div>';
    return $con;
}


/**前台载入js文件 */
function zibpay_load_scripts()
{
    // wp_enqueue_script('zibpay', get_template_directory_uri() . '/zibpay/assets/js/pay.min.js', array('jquery'), THEME_VERSION, true);
    wp_localize_script('zibpay', 'zibpay_ajax_url', admin_url("admin-ajax.php"));
}
//add_action('wp_enqueue_scripts', 'zibpay_load_scripts');

/**后台生成二维码图片 */
function zibpay_get_Qrcode($url)
{
    //引入phpqrcode类库
    require_once plugin_dir_path(__FILE__) . 'class/qrcode.class.php';
    $errorCorrectionLevel = 'L'; //容错级别
    $matrixPointSize      = 6; //生成图片大小
    ob_start();
    QRcode::png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);
    $data = ob_get_contents();
    ob_end_clean();

    $imageString = base64_encode($data);
    header("content-type:application/json; charset=utf-8");
    return 'data:image/jpeg;base64,' . $imageString;
}

/**获取支付参数函数 */
function zibpay_get_payconfig($type)
{
    $defaults = array();
    $defaults['xunhupay'] = array(
        'wechat_appid'     => '',
        'wechat_appsecret' => '',
        'alipay_appid'     => '',
        'alipay_appsecret' => '',
    );
    $defaults['official_wechat'] =  array(
        'merchantid'     => '',
        'appid'     => '',
        'key' => '',
        'jsapi' => '',
        'h5' => '',
    );
    $defaults['official_alipay'] =  array(
        'appid'     => '',
        'privatekey' => '',
        'publickey' => '',
        'pid'     => '',
        'md5key'     => '',
        'webappid'     => '',
        'webprivatekey'     => '',
        'h5' => '',
    );
    $defaults['codepay'] =  array(
        'id'     => '',
        'key' => '',
        'token' => '',
    );
    $defaults['payjs'] =  array(
        'mchid'     => '',
        'key' => '',
    );
    $defaults['xhpay'] =  array(
        'mchid'     => '',
        'key' => '',
    );
    return wp_parse_args((array)_pz($type), $defaults[$type]);;
}

/**根据订单号获取链接 */
function zibpay_get_order_num_link($order_num, $class = '')
{
    $href = '';
    $user_id = get_current_user_id();
    if ($user_id) {
        $href = get_author_posts_url($user_id) . '?page=pay';
    }
    $a = '<a target="_blank" href="' . $href . '" class="' . $class . '">' . $order_num . '</a>';
    if ($href) {
        return $a;
    } else {
        return '<span class="' . $class . '">' . $order_num . '</span>';
    }
}

/**判断是否在微信APP内 */
function zibpay_is_wechat_app()
{
    return strripos($_SERVER['HTTP_USER_AGENT'], 'micromessenger');
}

/**查看权限转文字 */
function zibpay_get_paid_type_name($pay_type)
{
    $order_type_name = '';
    if ($pay_type == 'paid') {
        $order_type_name = '已支付';
    } elseif ($pay_type == 'free') {
        $order_type_name = '免费资源';
    } elseif ($pay_type == 'vip1_free') {
        $order_type_name = _pz('pay_user_vip_1_name') . '免费';
    } elseif ($pay_type == 'vip2_free') {
        $order_type_name = _pz('pay_user_vip_2_name') . '免费';
    }
    return $order_type_name;
}
/**判断查查看权限 */
function zibpay_is_paid($post_id, $user_id = '', $product_id = '')
{
    // 准备判断参数
    if (!$post_id) return false;
    if (!$user_id) $user_id = get_current_user_id();
    $posts_pay = get_post_meta($post_id, 'posts_zibpay', true);
    $vip_level = zib_get_user_vip_level($user_id);
    if (empty($posts_pay['pay_price'])) {
        $pay_order = array('paid_type' => 'free');
        return $pay_order;
    }
    if ($vip_level && empty($posts_pay['vip_' . $vip_level . '_price'])) {
        $pay_order = array('paid_type' => 'vip' . $vip_level . '_free');
        return $pay_order;
    }

    global $wpdb;

    if ($user_id) {
        // 如果已经登录，根据用户id查找数据库订单
        $pay_order = $wpdb->get_row("SELECT * FROM $wpdb->zibpay_order where user_id=$user_id and post_id=$post_id and status=1");
        if ($pay_order) {
            $pay_order = (array) $pay_order;
            $pay_order['paid_type'] = 'paid';
            return $pay_order;
        }
    }
    // 如果未登录，
    //根据浏览器Cookie查找
    if (isset($_COOKIE['zibpay_' . $post_id])) {
        $pay_order = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->zibpay_order} WHERE order_num = %s and post_id=%d and status=1", $_COOKIE['zibpay_' . $post_id], $post_id));

        if ($pay_order) {
            $pay_order = (array) $pay_order;
            $pay_order['paid_type'] = 'paid';
            return $pay_order;
        }
    } else {
        //根据IP地址查找


    }
    return false;
}

/**判断是否已经存在订单 */
function zibpay_is_order_exists($post_id, $user_id = '', $product_id = '')
{
    // 准备判断参数
    if (!$user_id) $user_id = get_current_user_id();
    global $wpdb;

    if ($user_id) {
        // 如果已经登录，根据用户id查找数据库订单
        $pay_order = $wpdb->get_row("SELECT * FROM $wpdb->zibpay_order where user_id=$user_id and post_id=$post_id");
        if ($pay_order) return $pay_order;
    }
    // 如果未登录，
    //根据浏览器Cookie查找
    if (isset($_COOKIE['zibpay_' . $post_id])) {
        $pay_order = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->zibpay_order} WHERE order_num = %s and post_id=%d", $_COOKIE['zibpay_' . $post_id], $post_id));

        if ($pay_order) return $pay_order;
    } else {
        //根据IP地址查找
    }
    return false;
}


/**创建编辑器短代码 */
//添加隐藏内容，付费可见
function zibpay_to_show($atts, $content = null)
{

    $a = '#posts-pay';
    $_hide = '<div class="hidden-box"><a class="hidden-text" href="javascript:(scrollTo(\'' . $a . '\',-120));"><i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;此处内容已隐藏，请付费后查看</a></div>';
    global $post;

    $pay_mate = get_post_meta($post->ID, 'posts_zibpay', true);

    $paid = zibpay_is_paid($post->ID);
    /**如果未设置付费阅读功能，则直接显示 */
    if (empty($pay_mate['pay_type']) || $pay_mate['pay_type'] != '1') return  $content;
    /**
     * 判断逻辑
     * 1. 管理登录
     * 2. 已经付费
     * 3. 必须设置了付费阅读
     */
    if (is_super_admin()) {
        return '<div class="hidden-box show"><div class="hidden-text">本文隐藏内容 - 管理员可见</div>' . do_shortcode($content) . '</div>';
    } elseif ($paid) {
        $paid_name = zibpay_get_paid_type_name($paid['paid_type']);
        return '<div class="hidden-box show"><div class="hidden-text">本文隐藏内容 - ' . $paid_name . '</div>' . do_shortcode($content) . '</div>';
    } else {
        return  $_hide;
    }
}
add_shortcode('payshow', 'zibpay_to_show');


/**
 *页码加载
 */
function zibpay_admin_pagenavi($total_count, $number_per_page = 15)
{
    $current_page = isset($_GET['paged']) ? $_GET['paged'] : 1;

    if (isset($_GET['paged'])) {
        unset($_GET['paged']);
    }

    $total_pages    = ceil($total_count / $number_per_page);

    $first_page_url    = add_query_arg('paged', 1);
    $last_page_url    = add_query_arg('paged', $total_pages);

    if ($current_page > 1 && $current_page < $total_pages) {
        $prev_page        = $current_page - 1;
        $prev_page_url    = add_query_arg('paged', $prev_page);

        $next_page        = $current_page + 1;
        $next_page_url    = add_query_arg('paged', $next_page);
    } elseif ($current_page == 1) {
        $prev_page_url    = '#';
        $first_page_url    = '#';
        if ($total_pages > 1) {
            $next_page        = $current_page + 1;
            $next_page_url    = add_query_arg('paged', $next_page);
        } else {
            $next_page_url    = '#';
            $last_page_url    = '#';
        }
    } elseif ($current_page == $total_pages) {
        $prev_page        = $current_page - 1;
        $prev_page_url    = add_query_arg('paged', $prev_page);
        $next_page_url    = '#';
        $last_page_url    = '#';
    }
?>
    <div class="tablenav bottom">
        <div class="tablenav-pages">
            <span class="displaying-num">每页 <?php echo $number_per_page; ?> 共 <?php echo $total_count; ?></span>
            <span class="pagination-links">
                <a class="first-page button <?php if ($current_page == 1) echo 'disabled'; ?>" title="前往第一页" href="<?php echo $first_page_url; ?>">«</a>
                <a class="prev-page button <?php if ($current_page == 1) echo 'disabled'; ?>" title="前往上一页" href="<?php echo $prev_page_url; ?>">‹</a>
                <span class="paging-input">第 <?php echo $current_page; ?> 页，共 <span class="total-pages"><?php echo $total_pages; ?></span> 页</span>
                <a class="next-page button <?php if ($current_page == $total_pages) echo 'disabled'; ?>" title="前往下一页" href="<?php echo $next_page_url; ?>">›</a>
                <a class="last-page button <?php if ($current_page == $total_pages) echo 'disabled'; ?>" title="前往最后一页" href="<?php echo $last_page_url; ?>">»</a>
            </span>
        </div>
        <br class="clear">
    </div>
<?php
}


/**
 * @description: 给页面底部添加空白支付模态框
 * @param {*}
 * @return {*}
 */
add_action('wp_footer', 'zibpay_show_pay_modal');
function zibpay_show_pay_modal()
{

    $payment = zibpay_get_default_payment();
    $pay_moda_args = array(
        'payment' => $payment,
    );

    echo zibpay_qrcon_pay_modal($pay_moda_args);
}

/**
 * 包装一个模拟post进行url请求函数
 */
function zibpay_curl_post($url, $postData = '')
{
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        if ($result === false) {
            return 'Curl error: ' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    } else {
        wp_die("缺少curl组件，请开启");
    }
}
