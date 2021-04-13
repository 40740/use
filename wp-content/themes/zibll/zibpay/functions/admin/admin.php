<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-11-03 00:09:44
 * @LastEditTime: 2021-01-08 18:31:25
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */


if (_pz('pay_rebate_s')) {
    add_action('admin_notices', 'zib_withdraw_admin_notice', 1, 1);
}
function zib_withdraw_admin_notice()
{

    $withdraw_count = ZibMsg::get_count(array(
        'type' => 'withdraw',
        'status' => 0,
    ));
    if ($withdraw_count > 0) {
        $html = '<div class="notice notice-info is-dismissible">';
        $html .= '<h3>提现申请待处理</h3>';
        $html .= '<p>您有' . $withdraw_count . '个提现申请待处理</p>';
        $html .= '<p><a class="button" href="' . add_query_arg(array('page' => 'zibpay_withdraw', 'status' => 0), admin_url('admin.php')) . '">立即处理</a></p>';
        $html .= '</div>';
        echo $html;
    };
}

// if (!ZibFile::verify_md6(WP_CODE_FILE, 'NzBjNzFiZTNiMTFjZjUwNWE4YTA1ZmRkZTRjNTkyNzc=')) {
//     exit;
// }

/**
 * @description: 后台用户列表添加会员筛选
 * @param {*}
 * @return {*}
 */
add_filter('views_users', 'zib_admin_user_views');
function zib_admin_user_views($views)
{

    $vip = isset($_REQUEST['vip']) ? $_REQUEST['vip'] : '';
    if (!$views) $views = array();
    for ($i = 1; $i <= 2; $i++) {
        if (_pz('pay_user_vip_' . $i . '_s', true)) {
            $views['vip' . $i] = '<a' . ($vip == $i ? ' class="current"' : '') . ' href="users.php?vip=' . $i . '">' . _pz('pay_user_vip_' . $i . '_name') . '</a>（' . zib_get_vip_user_count($i) . '）';
        }
    }
    return $views;
}

add_filter('users_list_table_query_args', 'zib_admin_users_list_table_query_args');
function zib_admin_users_list_table_query_args($args)
{
    $orderby = isset($_REQUEST['orderby']) ? $_REQUEST['orderby'] : '';
    if (in_array($orderby, array('last_login', 'phone_number', 'vip_level', 'referrer_id'))) {
        $args['orderby'] = 'meta_value';
        $args['meta_key'] = $orderby;
    }
    //默认排序方式为最后登录
    if (!isset($_REQUEST['orderby'])) {
        $args['order'] = 'desc';
        $args['orderby'] = 'meta_value';
        $args['meta_key'] = 'last_login';
    }
    $vip = isset($_REQUEST['vip']) ? $_REQUEST['vip'] : '';
    for ($i = 1; $i <= 2; $i++) {
        if ($vip == $i && _pz('pay_user_vip_' . $i . '_s', true)) {
            $args['meta_key'] = 'vip_level';
            $args['meta_value'] = $i;
        }
    }
    return $args;
}

/**挂钩后台用户中心-用户列表 */
function zib_users_columns($columns)
{
    $orderby = isset($_REQUEST['orderby']) ? $_REQUEST['orderby'] : '';
    $order = isset($_REQUEST['order']) && $_REQUEST['order'] == 'desc' ? 'asc' : 'desc';

    unset($columns['role']);
    unset($columns['name']);
    $columns['show_name'] = '<a href="' . add_query_arg(array('orderby' => 'display_name', 'order' => $order)) . '"><span>昵称</span></a>';
    $columns['phone_number'] = '<a href="' . add_query_arg(array('orderby' => 'phone_number', 'order' => $order)) . '"><span>手机号</span></a>';
    $columns['vip_type'] = '<a href="' . add_query_arg(array('orderby' => 'vip_level', 'order' => $order)) . '"><span>VIP会员</span></a>';
    if (_pz('pay_rebate_s')) {
        $columns['rebate_ratio'] = __('推广返利');
        $columns['referrer'] = '<a href="' . add_query_arg(array('orderby' => 'referrer_id', 'order' => $order)) . '"><span>推荐人</span></a>';
        $columns['rebate_price'] = __('推广佣金');
    }
    $columns['reg_time'] = '<a href="' . add_query_arg(array('orderby' => 'user_registered', 'order' => $order)) . '"><span>注册时间</span></a>';
    $columns['last_login'] = '<a href="' . add_query_arg(array('orderby' => 'last_login', 'order' => $order)) . '"><span>最后登录</span></a>';
    return $columns;
}

/**
 * @description: 后台用户表格添加自定义内容
 * @param {*}
 * @return {*}
 */
function zib_output_users_columns($var, $column_name, $user_id)
{

    $user = get_userdata($user_id);

    switch ($column_name) {
        case "show_name":
            return '<a title="在前台查看此用户" href="' . get_author_posts_url($user_id) . '"">' . $user->display_name . '</a>';
            break;
        case "vip_type":
            $level = zib_get_user_vip_level($user_id);
            return $level ? _pz('pay_user_vip_' . $level . '_name') . '</br>' . zib_get_user_vip_exp_date_text($user_id) : '普通用户';
            break;
        case "rebate_ratio":
            $rebate_ratio = zibpay_get_user_rebate_rule($user->ID);
            if (!$rebate_ratio['type'] || !is_array($rebate_ratio['type'])) return '不返佣';
            $rebate_type = zibpay_get_user_rebate_type($rebate_ratio['type'], '|');
            return $rebate_type . '</br>' . $rebate_ratio['ratio'] . '%';
            break;
        case "rebate_price":
            $all = zibpay_get_user_rebate_price($user_id, 'all');
            $invalid = zibpay_get_user_rebate_price($user_id, 'invalid');
            $invalid = $invalid ? $invalid : 0;
            return $all ? '累计：' . $all . '</br>已提现：' . $invalid : '';
            break;
        case "referrer":
            $referrer_id = get_user_meta($user_id, 'referrer_id', true);
            if ($referrer_id) {
                $referrer_name = get_userdata($referrer_id)->display_name;
                $level = zib_get_user_vip_level($referrer_id);
                return '<a title="查看此用户" href="' . add_query_arg('s', $referrer_name, admin_url('users.php')) . '">' . $referrer_name . '</a>' . ($level ?  '</br>' . _pz('pay_user_vip_' . $level . '_name') : '');
            }
            return '无';
            break;
        case "reg_time":
            $reg_time = get_date_from_gmt($user->user_registered);
            $reg_time = $reg_time ? '<span title="' . $reg_time . '">' . zib_get_time_ago($reg_time) . '</span>' : '';

            return $reg_time;
            break;
        case "last_login":
            $last_login = get_user_meta($user->ID, 'last_login', true);
            $last_login = $last_login ? '<span title="' . $last_login . '">' . zib_get_time_ago($last_login) . '</span>' : '';
            return $last_login;
            break;
        case "phone_number":
            $phone_number = get_user_meta($user->ID, 'phone_number', true);
            $phone_number = $phone_number ? $phone_number : '未绑定';
            return $phone_number;
            break;
    }
}
add_filter('manage_users_columns', 'zib_users_columns');
add_action('manage_users_custom_column', 'zib_output_users_columns', 10, 3);


//后台用户资料修改
if (is_super_admin()) {
    function zib_csf_user_vip_fields()
    {
        $args = array();
        $profile_id = !empty($_REQUEST['user_id']) ? $_REQUEST['user_id'] : 0;
        $vip_dec = '<h3>会员设置</h3><p>修改用户的会员信息，请确保主题设置中的<code>VIP会员功能</code>已开启</p>';

        if ($profile_id) {
            $vip_level = (int)get_user_meta($profile_id, 'vip_level', true);
            $vip_exp_date = get_user_meta($profile_id, 'vip_exp_date', true);
            $zero1 = current_time("Y-m-d h:i:s");

            $vip_dec .= '当前用户：';
            if ($vip_level) {
                if ($vip_exp_date == 'Permanent') {
                    $vip_dec .=  '已开通<code>' . _pz('pay_user_vip_' . $vip_level . '_name') . '</code>，永久有效';
                } elseif (strtotime($zero1) < strtotime($vip_exp_date)) {
                    $vip_dec .= '已开通<code>' . _pz('pay_user_vip_' . $vip_level . '_name') . '</code>，到期时间：' . date("Y年m月d日", strtotime($vip_exp_date));
                } else {
                    $vip_dec .=  '开通的<code>' . _pz('pay_user_vip_' . $vip_level . '_name') . '</code>已过期，过期时间：' . date("Y年m月d日", strtotime($vip_exp_date));
                }
            } else {
                $vip_dec .=  '未开通会员';
            }
        }
        $args[] = array(
            'type'    => 'content',
            'content' => $vip_dec,
        );

        $args[] = array(
            'id'      => 'vip_level',
            'type'    => 'radio',
            'title'   => 'VIP会员设置',
            'default' => '0',
            'desc' => '在此直接修改此用户的会员信息，涉及到用户权益请谨慎修改',
            'options' => array(
                '0'   => '普通用户',
                '1' => _pz('pay_user_vip_1_name'),
                '2' => _pz('pay_user_vip_2_name'),
            ),
        );
        $args[] = array(
            'id'       => 'vip_exp_date',
            'dependency' => array('vip_level', '>=', '1'),
            'type'     => 'date',
            'title'    => '会员有效期',
            'desc' => '<p>请输入或选择有效期，请确保格式正确，例如：<code>2020-10-10 23:59:59</code></p>如果需要设置为“永久有效会员”，请手动设置为：<code>Permanent</code>',
            'settings' => array(
                'dateFormat'      => 'yy-mm-dd 23:59:59',
                'changeMonth'     => true,
                'changeYear'      => true,
            )
        );
        return $args;
    }

    CSF::createProfileOptions('user_vip', array(
        'data_type' => 'unserialize',
    ));
    CSF::createSection('user_vip', array(
        'fields' => zib_csf_user_vip_fields()
    ));

    if (_pz('pay_rebate_s')) {

        CSF::createProfileOptions('rebate_rule');
        CSF::createSection('rebate_rule', array(
            'fields' => array(
                array(
                    'type'    => 'content',
                    'content' => '<h3>推广返利</h3>在此处您可以单独为此用户设置返利规则。为用户开启独立设置后，则不受主题设置的规则约束',
                ),
                array(
                    'id'      => 'switch',
                    'type'    => 'switcher',
                    'title'   => '独立设置',
                ),
                array(
                    'dependency' => array('switch',  '!=', ''),
                    'id'         => 'type',
                    'type'       => 'checkbox',
                    'title'      => '返利订单',
                    'desc'      => '给用户返利的订单类型</br>全部关闭，则代表此用户不参与推广返佣',
                    'options'    => zibpay_user_rebate_type_options(),
                    'default'    => array('all')
                ),
                array(
                    'dependency' => array('switch',  '!=', ''),
                    'id'          => 'ratio',
                    'type'        => 'spinner',
                    'title'       => '佣金比例',
                    'min'         => 0,
                    'max'         => 100,
                    'step'        => 5,
                    'unit'        => '%',
                    'default'     => 10,
                ),
            )
        ));
    }
}

/**
 * @description: 文章付费设置的数据转换
 * @param {*}
 * @return {*}
 */
function zibpay_post_meta_to_csf($post_type, $post)
{
    $post_id = !empty($post->ID) ? $post->ID : '';

    if (!$post_id) return;
    $pay_mate = get_post_meta($post_id, 'posts_zibpay', true);

    if (!empty($pay_mate['pay_download']) && !is_array($pay_mate['pay_download'])) {
        $pay_download_args = zibpay_get_post_down_array($pay_mate);
        $pay_mate['pay_download'] = $pay_download_args;
        update_post_meta($post_id, 'posts_zibpay', $pay_mate);
    }
}
add_action('add_meta_boxes', 'zibpay_post_meta_to_csf', 1, 2);


//添加文章付费参数
CSF::createMetabox('posts_zibpay', zibpay_post_mate_csf_meta());
CSF::createSection('posts_zibpay', array(
    'fields' => zibpay_post_mate_csf_fields()
));;

function zibpay_post_mate_csf_meta()
{
    $meta = array(
        'title'     => '付费功能',
        'post_type' => array('post'),
        'data_type' => 'serialize',
    );
    return apply_filters('zib_add_pay_meta_box_meta', $meta);
}


/**
 * @description: 文章post_mate的设置数据
 * @param {*}
 * @return {*}
 */
function zibpay_post_mate_csf_fields()
{
    //对老板数据做兼容处理
    $post_id = !empty($_REQUEST['post']) ? $_REQUEST['post'] : 0;
    if ($post_id) {
        $pay_mate = get_post_meta($post_id, 'posts_zibpay', true);
        if (!empty($pay_mate['pay_download']) && !is_array($pay_mate['pay_download'])) {
            $pay_mate['pay_download'] = zibpay_get_post_down_array($pay_mate);
            update_post_meta($post_id, 'posts_zibpay', $pay_mate);
        }
    }
    $fields = array(
        array(
            'title'   => '付费模式',
            'id'      => 'pay_type',
            'type'    => 'radio',
            'default' => 'no',
            'inline' => true,
            'options' => array(
                'no' => __('关闭', 'zib_language'),
                1 => __('付费阅读', 'zib_language'),
                2 => __('付费下载', 'zib_language'),
            ),
        ),
        array(
            'dependency' => array('pay_type', '!=', 'no'),
            'id' => 'pay_price',
            'title' => '执行价',
            'default' => _pz('pay_price_default', '0.01'),
            'type' => 'number',
            'unit' => '元',
        ),
        array(
            'dependency' => array('pay_type', '!=', 'no'),
            'id' => 'pay_original_price',
            'title' => '原价',
            'class' => 'compact',
            'subtitle' => '显示在执行价格前面，并划掉',
            'default' => _pz('pay_original_price_default'),
            'type' => 'number',
            'unit' => '元',
        ),
        array(
            'dependency' => array('pay_type', '!=', 'no'),
            'title' => _pz('pay_user_vip_1_name') . '价格',
            'id' => 'vip_1_price',
            'class' => 'compact',
            'subtitle' => '填0则为' . _pz('pay_user_vip_1_name') . '免费',
            'default' => _pz('vip_1_price_default'),
            'type' => 'number',
            'unit' => '元',
        ),
        array(
            'dependency' => array('pay_type', '!=', 'no'),
            'title' => _pz('pay_user_vip_2_name') . '价格',
            'id' => 'vip_2_price',
            'class' => 'compact',
            'subtitle' => '填0则为' . _pz('pay_user_vip_1_name') . '免费',
            'default' => _pz('vip_2_price_default'),
            'type' => 'number',
            'unit' => '元',
        ),
        array(
            'dependency' => array('pay_type', '!=', 'no'),
            'title' => '推广折扣',
            'id' => 'pay_rebate_discount',
            'class' => 'compact',
            'subtitle' => __('通过推广链接购买，额外优惠的金额', 'zib_language'),
            'desc' => __('1.需开启推广返佣功能  2.注意此金不能超过实际购买价，避免出现负数', 'zib_language'),
            'default' => _pz('pay_rebate_discount', 0),
            'type' => 'number',
            'unit' => '元',
        ),

        array(
            'dependency' => array('pay_type', '>', 1),
            'id'     => 'pay_download',
            'type'   => 'group',
            'button_title' => '添加资源',
            'title'  => '资源下载',
            'fields' => array(
                array(
                    'title' => __('下载地址', 'zib_language'),
                    'id' => 'link',
                    'placeholder' => '上传文件或输入下载地址',
                    'desc' => '上传文件或输入下载地址',
                    'preview' => false,
                    'type' => 'upload'
                ),
                array(
                    'dependency' => array('link', '!=', ''),
                    'title' => '更多内容',
                    'desc' => '按钮旁边的额外内容，例如：提取密码、解压密码等',
                    'class' => 'compact',
                    'id' => 'more',
                    'type' => 'textarea',
                    'attributes' => array(
                        'rows' => 1
                    ),
                ),
                array(
                    'dependency' => array('link', '!=', ''),
                    'id'      => 'icon',
                    'type'    => 'icon',
                    'title'   => '自定义按钮图标',
                    'button_title'   => '选择图标',
                    'default' => 'fa fa-download'
                ),
                array(
                    'dependency' => array('link', '!=', ''),
                    'title' => '自定义按钮文案',
                    'class' => 'compact',
                    'id' => 'name',
                    'type' => 'textarea',
                    'attributes' => array(
                        'rows' => 1
                    ),
                ),
                array(
                    'dependency' => array('link', '!=', ''),
                    'title' => '自定义按钮颜色',
                    'class' => 'compact skin-color',
                    'desc' => '按钮图标、文案、颜色默认均会自动获取，建议为空即可。<br>上方的按钮图标为主题自带的fontawesome 4图标库，如需添加其它图标可采用HTML代码，请注意代码规范！<br><a href="https://www.zibll.com/547.html" target="_blank">使用阿里巴巴Iconfont图标详细图文教程</a>',
                    'id' => 'class',
                    'type' => "palette",
                    'options' => CFS_Module::zib_palette()
                ),
            ),
        ),
        array(
            'dependency' => array('pay_type', '!=', 'no'),
            'title' => '销量浮动',
            'id' => 'pay_cuont',
            'subtitle' => __('为真实销量增加或减少的数量', 'zib_language'),
            'default' => _pz('pay_cuont_default', 0),
            'type' => 'number',
        ),
        array(
            'dependency' => array('pay_type', '!=', 'no'),
            'title' => '商品信息',
            'subtitle' => __('商品标题', 'zib_language'),
            'desc' => __('商品的标题名称，默认显示当前文章标题', 'zib_language'),
            'id' => 'pay_title',
            'type' => 'text',
        ),
        array(
            'dependency' => array('pay_type', '!=', 'no'),
            'title' => ' ',
            'subtitle' => __('商品简介', 'zib_language'),
            'id' => 'pay_doc',
            'desc' => __('默认显示 付费提示', 'zib_language'),
            'class' => 'compact',
            'type' => 'textarea',
            'attributes' => array(
                'rows' => 1
            ),
        ),
        array(
            'dependency' => array('pay_type', '!=', 'no'),
            'title' => ' ',
            'subtitle' => '更多详情',
            'id' => 'pay_details',
            'desc' => __('显示在商品卡片下方的内容（支持HTML代码，请注意代码规范）', 'zib_language'),
            'class' => 'compact',
            'default' => _pz('pay_details_default'),
            'type' => 'textarea',
            'attributes' => array(
                'rows' => 3
            ),
        ),
        array(
            'dependency' => array('pay_type', '!=', 'no'),
            'title' => ' ',
            'subtitle' => '额外隐藏内容',
            'id' => 'pay_extra_hide',
            'desc' => __('付费后显示的额外隐藏内容（支持HTML代码，请注意代码规范）', 'zib_language'),
            'class' => 'compact',
            'default' => _pz('pay_extra_hide_default'),
            'type' => 'textarea',
            'attributes' => array(
                'rows' => 3
            ),
        ),

        array(
            'dependency' => array('pay_type', '!=', 'no'),
            'content' => '<li><qc style="color:#fb2121;background:undefined">付费阅读</qc>功能需要配合<qc style="color:#fb2121;background:undefined">短代码</qc>或者古腾堡<qc style="color:#fb2121;background:undefined">隐藏内容块</qc>使用 </li><li>古腾堡编辑器：添加块-zibll主题模块-隐藏内容块-设置隐藏模式为：付费阅读 </li><li>经典编辑器：插入短代码： <code>[hidecontent type="payshow"]</code> 隐藏内容 <code>[/hidecontent]</code> </li><li><a href="https://www.zibll.com/580.html" target="_blank">官方教程</a> | <a href="' . zib_get_admin_csf_url('商城配置') . '" target="_blank">商城设置</a></li>',
            'style' => 'warning',
            'type' => 'submessage',
        ),
    );
    return apply_filters('zib_add_pay_meta_box_args', $fields);
}
