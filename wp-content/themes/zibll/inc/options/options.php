<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-11-11 10:19:48
 * @LastEditTime: 2021-01-08 22:31:34
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */


$functions = array(
    'options-module',
    // 'zib-update',
    'admin-options',
    'metabox-options',
    'profile-options',
    'action',
);

foreach ($functions as $function) {
    require_once plugin_dir_path(__FILE__) . $function . '.php';
}

//使用Font Awesome 4
add_filter('csf_fa4', '__return_true');

//定义文件夹
function csf_custom_csf_override()
{
    return 'inc/csf-framework';
}
add_filter('csf_override', 'csf_custom_csf_override');


//自定义css、js
function csf_add_custom_wp_enqueue()
{
    // Style
    wp_enqueue_style('csf_custom_css', get_template_directory_uri() . '/inc/csf-framework/assets/css/style.min.css');
    // Script
    wp_enqueue_script('csf_custom_js', get_template_directory_uri() . '/inc/csf-framework/assets/js/main.min.js', array('jquery'));
}
add_action('csf_enqueue', 'csf_add_custom_wp_enqueue');

//获取主题设置链接
function zib_get_admin_csf_url($tab = '')
{
    $tab_array = explode("/", $tab);
    $tab_array_sanitize = array();
    foreach ($tab_array as $tab_i) {
        $tab_array_sanitize[] = sanitize_title($tab_i);
    }
    $tab_attr = esc_attr(implode("/", $tab_array_sanitize));
    $url = add_query_arg('page', 'zibll_options', admin_url('admin.php'));
    $url = $tab ? $url . '#tab=' . $tab_attr : $url;
    return esc_url($url);
}

// 获取及设置主题配置参数
$zib_get_option = false;
function _pz($name, $default = false, $subname = '')
{
    //保存到全局变量，加速配置获取
    global $zib_get_option;
    if ($zib_get_option) {
        $options = $zib_get_option;
    } else {
        $options = get_option('zibll_options');
        $zib_get_option = $options;
    }
    if (isset($options[$name])) {
        if ($subname) {
            return isset($options[$name][$subname]) ? $options[$name][$subname] : $default;
        } else {
            return $options[$name];
        }
    }
    /**
     *  else {
        $options = get_option('Zibll');
        // echo $name . '//' . $subname . "<br>";
        if (isset($options[$name])) {
            if ($subname) {
                return isset($options[$name][$subname]) ? $options[$name][$subname] : $default;
            } else {
                return $options[$name];
            }
        }
    }

     */
    return $default;
}

function _spz($name, $value)
{
    $get_option = get_option('zibll_options');
    $get_option = is_array($get_option) ? $get_option : array();
    $get_option[$name] = $value;
    return update_option('zibll_options', $get_option);
}

//获取及设置压缩后的posts_meta
if (!function_exists('of_get_posts_meta')) {
    function of_get_posts_meta($name, $key, $default = false, $post_id = '')
    {
        global $post;
        $post_id = $post_id ? $post_id : $post->ID;
        $get_mate = get_post_meta($post_id, $name, true);
        if (isset($get_mate[$key])) {
            return $get_mate[$key];
        }
        return $default;
    }
}

if (!function_exists('of_set_posts_meta')) {
    function of_set_posts_meta($post_id = '', $name, $key, $value)
    {
        if (!$name) {
            return false;
        }
        global $post;
        $post_id = $post_id ? $post_id : $post->ID;
        $get_mate = get_post_meta($post_id, $name, true);
        $get_mate = (array)$get_mate;
        $get_mate[$key] = $value;
        return update_post_meta($post_id, $name, $get_mate);
    }
}

//老数据转新数据
function zib_pz_to_csf()
{
    $prefix = 'zibll_options';
    //老板数据迁移
    if (!get_option($prefix)) {
        $old_db = get_option('Zibll');
        if (!$old_db || !is_array($old_db)) return;
        //转换参数
        //切换主题按钮
        $old_db['theme_mode_button'] = !empty($old_db['theme_mode_button']) ? array('pc_nav', 'm_menu') : array();
        //通知按钮
        $old_db['system_notice_button'] = array();
        for ($i = 1; $i <= 2; $i++) {
            if (!empty($old_db['system_notice_b' . $i . '_t']) && !empty($old_db['system_notice_b' . $i . '_h'])) {
                $old_db['system_notice_button'][] = array(
                    'link' => array(
                        'url'    => @$old_db['system_notice_b' . $i . '_h'],
                        'text'   => @$old_db['system_notice_b' . $i . '_t'],
                    ),
                    'class' => @$old_db['system_notice_b' . $i . '_c'],
                );
            }
        }
        //底部二维码
        $old_db['footer_mini_img'] = array();
        for ($i = 1; $i <= 3; $i++) {
            if (!empty($old_db['footer_mini_img_' . $i])) {
                $old_db['footer_mini_img'][] = array(
                    'image' => @$old_db['footer_mini_img_' . $i],
                    'text' => @$old_db['footer_mini_img_t_' . $i],
                );
            }
        }
        //首页多栏目
        @$home_list_num = $old_db['home_list_num'] ? $old_db['home_list_num'] : 4;
        @$old_db['home_lists'] = array();
        for ($i = 2; $i <= $home_list_num; $i++) {
            if (!empty($old_db['home_list' . $i . '_s']) && !empty($old_db['home_list' . $i . '_cat'])) {
                $old_db['home_lists'][] = array(
                    'title' => @$old_db['home_list' . $i . '_t'],
                    'term_id' => @$old_db['home_list' . $i . '_cat'],
                );
            }
        }
        //老多选转新多选
        $multicheck_kay = array(
            'header_search_more_cat_obj',
            'post_article_cat',
            'pay_rebate_user_s',
            'pay_rebate_user_s_1',
            'pay_rebate_user_s_2',
        );

        foreach ($multicheck_kay as $key) {
            $old_db[$key] = array_keys($old_db[$key], true);
        }

        //排序方式
        @$orderby = array_keys($old_db['option_list_orderby'], true);
        @$old_db['cat_orderby_option']['lists'] = $orderby;
        @$old_db['tag_orderby_option']['lists'] = $orderby;
        @$old_db['topics_orderby_option']['lists'] = $orderby;
        @$old_db['home_list1_orderby_option']['lists'] = $orderby;

        //ajax菜单
        $ajax_list_page = array(
            'cat', 'topics', 'tag',
        );
        //ajax按钮列表
        $ajax_but_cats = array_keys($old_db['option_list_cats'], true);
        $ajax_but_topics = array_keys($old_db['option_list_topics'], true);
        $ajax_but_tags = array_keys($old_db['option_list_tags'], true);

        foreach ($ajax_list_page as $page) {
            @$old_db['ajax_list_' . $page . '_cat'] = $old_db['option_list_' . $page . '_cat'];
            @$old_db['ajax_list_' . $page . '_topics'] = $old_db['option_list_' . $page . '_top'];
            @$old_db['ajax_list_' . $page . '_tag'] = $old_db['option_list_' . $page . '_tag'];
            @$old_db[$page . '_orderby_s'] = $old_db['option_list_' . $page . '_orderby'];
            @$old_db['ajax_list_option_' . $page . '_cat']['lists'] = $ajax_but_cats;
            @$old_db['ajax_list_option_' . $page . '_topics']['lists'] = $ajax_but_topics;
            @$old_db['ajax_list_option_' . $page . '_tag']['lists'] = $ajax_but_tags;
        }

        //封面图
        @$old_db['cat_default_cover'] = $old_db['page_cover_img'];
        @$old_db['topics_default_cover'] = $old_db['page_cover_img'];
        @$old_db['tag_default_cover'] = $old_db['page_cover_img'];

        //列表卡片模式-分类id
        @$old_db['list_card_cat'] = array_keys($old_db['list_card'], true);

        //列表多图模式
        if ($old_db['mult_thumb']) {
            @$old_db['mult_thumb_cat'] = array($old_db['mult_thumb_cat']);
        } else {
            @$old_db['mult_thumb_cat'] = '';
        }

        //文章幻灯片封面
        @$old_db['article_slide_cover_option']['button'] = $old_db['article_cover_slide_show_button'];
        @$old_db['article_slide_cover_option']['pagination'] = $old_db['article_cover_slide_show_pagination'];
        @$old_db['article_slide_cover_option']['effect'] = $old_db['article_cover_slide_effect'];
        @$old_db['article_slide_cover_option']['pc_height'] = $old_db['article_cover_slide_height'];
        @$old_db['article_slide_cover_option']['m_height'] = $old_db['article_cover_slide_height_m'];

        //用，号分割数据
        @$old_db['home_exclude_posts'] = preg_split("/,|，|\s|\n/", $old_db['home_exclude_posts']);
        @$old_db['home_exclude_cats'] = preg_split("/,|，|\s|\n/", $old_db['home_exclude_cats']);

        //社交登录
        $oauth_type = array(
            'qq', 'weixin', 'weibo', 'github', 'gitee', 'baidu', 'alipay',
        );
        foreach ($oauth_type as $oauth) {
            @$old_db['oauth_' . $oauth . '_option']['appid'] = $old_db['oauth_' . $oauth . '_appid'];
            @$old_db['oauth_' . $oauth . '_option']['appkey'] = !empty($old_db['oauth_' . $oauth . '_appkey']) ? $old_db['oauth_' . $oauth . '_appkey'] : '';
        }
        @$old_db['oauth_alipay_option']['appkrivatekey'] = $old_db['oauth_alipay_appkrivatekey'];

        //会员产品
        for ($i = 1; $i <= 2; $i++) {
            @$old_db['vip_opt']['pay_user_vip_' . $i . '_equity'] = $old_db['pay_user_vip_' . $i . '_equity'];
            for ($x = 0; $x <= 3; $x++) {
                if ($old_db['vip_product_' . $i . '_' . ($x + 1) . '_s']) {
                    @$old_db['vip_opt']['vip_' . $i . '_product'][$x]['price'] = $old_db['vip_product_' . $i . '_' . ($x + 1) . '_price'];
                    @$old_db['vip_opt']['vip_' . $i . '_product'][$x]['show_price'] = $old_db['vip_product_' . $i . '_' . ($x + 1) . '_show_price'];
                    @$old_db['vip_opt']['vip_' . $i . '_product'][$x]['tag'] = $old_db['vip_product_' . $i . '_' . ($x + 1) . '_tag'];
                    @$old_db['vip_opt']['vip_' . $i . '_product'][$x]['time'] = $old_db['vip_product_' . $i . '_' . ($x + 1) . '_time'];
                }
            }
        }

        //收款接口
        @$old_db['official_alipay']['appid'] = $old_db['official_alipay_appid'];
        @$old_db['official_alipay']['privatekey'] = $old_db['official_alipay_privatekey'];
        @$old_db['official_alipay']['publickey'] = $old_db['official_alipay_publickey'];
        @$old_db['official_alipay']['webappid'] = $old_db['enterprise_alipay_appid'];
        @$old_db['official_alipay']['webprivatekey'] = $old_db['enterprise_alipay_privatekey'];
        @$old_db['official_alipay']['h5'] = $old_db['enterprise_alipay_h5'];

        @$old_db['xunhupay']['wechat_appid'] = $old_db['xunhupay_wechat_appid'];
        @$old_db['xunhupay']['wechat_appsecret'] = $old_db['xunhupay_wechat_appsecret'];
        @$old_db['xunhupay']['alipay_appid'] = $old_db['xunhupay_alipay_appid'];
        @$old_db['xunhupay']['alipay_appsecret'] = $old_db['xunhupay_alipay_appsecret'];

        @$old_db['official_wechat']['merchantid'] = $old_db['official_wechat_merchantid'];
        @$old_db['official_wechat']['appid'] = $old_db['official_wechat_appid'];
        @$old_db['official_wechat']['key'] = $old_db['official_wechat_appkey'];
        @$old_db['official_wechat']['jsapi'] = $old_db['official_wechat_jsapi'];
        @$old_db['official_wechat']['h5'] = $old_db['official_wechat_h5'];

        @$old_db['codepay']['id'] = $old_db['codepay_id'];
        @$old_db['codepay']['key'] = $old_db['codepay_key'];
        @$old_db['codepay']['token'] = $old_db['codepay_token'];

        @$old_db['payjs']['mchid'] = $old_db['payjs_mchid'];
        @$old_db['payjs']['key'] = $old_db['payjs_key'];
        @$old_db['xhpay']['mchid'] = $old_db['xhpay_mchid'];
        @$old_db['xhpay']['key'] = $old_db['xhpay_key'];

        //执行数据更新
        update_option($prefix, $old_db);
    }
}

//后台重新导入老数据
function zib_admin_option_to_csf()
{
    //管理员权限判断
    if (!is_super_admin()) {
        echo json_encode(array('error' => true, 'msg' => '权限不足，请用管理员账号登录！'));
        exit();
    }

    //二次确认操作
    if (empty($_COOKIE['option_to_csf'])) {
        echo (json_encode(array('error' => 1, 'msg' => '导入老数据之后会完全覆盖现有数据，此操作不可恢复！请再次确认！')));
        //设置浏览器缓存限制提交的间隔时间
        $expire = time() + 10;
        setcookie('option_to_csf', time(), $expire, '/', '', false);
        exit();
    }

    //执行删除数据
    delete_option('zibll_options');
    //执行导入老数据数据
    zib_pz_to_csf();

    echo json_encode(array('error' => 0, 'reload' => true, 'msg' => '配置数据已导入，请刷新页面'));
    exit();
}
add_action('wp_ajax_option_to_csf', 'zib_admin_option_to_csf');


//主题更新后发送通知
function zib_notice_update()
{
    $version = get_option('Zibll_version');
    $theme_data = wp_get_theme();
    if ($version && version_compare($version, $theme_data['Version'], '<')) {
        $up = get_option('zibll_new_version');
        $up_desc = !empty($up['update_description']) ? '<p>' . $up['update_description'] . '</p>' : '';
        $con = '<div class="notice notice-success is-dismissible">
				<h2 style="color:#fd4c73;"><i class="fa fa-heart fa-fw"></i> 恭喜您！Zibll子比主题已更新</h2>
                ' . $up_desc . '
                <p>首次升级V5.0务必查看<a target="_bank" href="https://www.zibll.com/1202.html">V5.0升级指南</a></p>
                <p>更新主题请记得清空缓存、刷新CDN，再保存一下<a href="' . zib_get_admin_csf_url() . '">主题设置</a>，保存主题设置后此通知会自动关闭</p>
                <p><a class="button" style="margin: 2px;" href="' . zib_get_admin_csf_url() . '">体验新功能</a><a class="button" style="margin: 2px;" href="' . zib_get_admin_csf_url('文档更新') . '">查看主题文档</a><a target="_blank" class="button" style="margin: 2px;" href="https://www.zibll.com/375.html">查看更新日志</a></p>
			</div>';
        echo  $con;
    }
}
add_action('admin_notices', 'zib_notice_update');

//保存主题更新主题版本
function zib_save_zibll_version()
{
    $theme_data = wp_get_theme();
    update_option('Zibll_version', $theme_data['Version']);
}
add_action("csf_zibll_options_save_after", 'zib_save_zibll_version');
