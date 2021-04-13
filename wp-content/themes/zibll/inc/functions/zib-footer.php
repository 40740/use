<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-09-29 13:18:37
 * @LastEditTime: 2021-01-07 22:53:11
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

// foot code
add_action('wp_footer', 'zib_footer', 98);
function zib_footer()
{
    $code = '';
    if (_pz('footcode')) {
        $code .= "<!--FOOTER_CODE_START-->\n" . _pz('footcode') . "\n<!--FOOTER_CODE_END-->\n";
    }
    if (_pz('trackcode')) {
        $code .= "<!--FOOTER_CODE_START-->\n" . _pz('trackcode') . "\n<!--FOOTER_CODE_END-->\n";
    }
    if (_pz('javascriptcode')) {
        $code .= '<script type="text/javascript">' . _pz('javascriptcode') . '</script>';
    }
    echo $code;
}

add_action('wp_footer', 'zib_win_var');
function zib_win_var()
{
    $highlight_dark_zt = _pz("highlight_dark_zt", 'dracula');
    $highlight_white_zt = _pz("highlight_zt", 'enlighter');
    $highlight_theme = zib_get_theme_mode() == 'dark-theme' ? $highlight_dark_zt : $highlight_white_zt;
?>
    <script type="text/javascript">
        window._win = {
            www: '<?php echo esc_url(home_url()) ?>',
            uri: '<?php echo esc_url(ZIB_STYLESHEET_DIRECTORY_URI) ?>',
            ver: '<?php echo THEME_VERSION ?>',
            sign_type: '<?php echo _pz("user_sign_type") ?>',
            signin_url: '<?php echo add_query_arg('redirect_to', home_url(add_query_arg(null, null)), zib_get_sign_url('signin')); ?>',
            signup_url: '<?php echo add_query_arg('redirect_to', home_url(add_query_arg(null, null)), zib_get_sign_url('signup')); ?>',
            ajax_url: '<?php echo esc_url(admin_url('admin-ajax.php')) ?>',
            ajaxpager: '<?php echo esc_html(_pz("ajaxpager")) ?>',
            ajax_trigger: '<?php echo _pz("ajax_trigger") ?>',
            ajax_nomore: '<?php echo _pz("ajax_nomore") ?>',
            qj_loading: '<?php echo _pz("qj_loading") ?>',
            highlight_kg: '<?php echo _pz("highlight_kg") ?>',
            highlight_hh: '<?php echo _pz("highlight_hh") ?>',
            highlight_btn: '<?php echo _pz("highlight_btn") ?>',
            highlight_zt: '<?php echo  $highlight_theme; ?>',
            highlight_white_zt: '<?php echo  $highlight_white_zt; ?>',
            highlight_dark_zt: '<?php echo  $highlight_dark_zt; ?>',
            up_max_size: '<?php echo _pz("up_max_size") ?>',
            comment_upload_img: '<?php echo (_pz("comment_img") && _pz("comment_upload_img")) ?>'
        }
    </script>
<?php
}

add_action('admin_footer', 'zib_win_console', 99);
add_action('wp_footer', 'zib_win_console', 99);
function zib_win_console()
{
?>
    <script type="text/javascript">
        console.log("get_num_queries：<?php echo get_num_queries(); ?> | timer_stop：<?php echo timer_stop(0, 6) * 1000 . 'ms'; ?>");
    </script>
<?php
}


if (_pz('zib_baidu_push_js')) {
    add_action('wp_footer', 'zib_baidu_push_js', 98);
}

function zib_baidu_push_js()
{
?>
    <!--baidu_push_js-->
    <script type="text/javascript">
        (function() {
            var bp = document.createElement('script');
            var curProtocol = window.location.protocol.split(':')[0];
            if (curProtocol === 'https') {
                bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
            } else {
                bp.src = 'http://push.zhanzhang.baidu.com/push.js';
            }
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(bp, s);
        })();
    </script>
    <!--baidu_push_js-->
<?php
}

/**右侧浮动按钮 */
function zib_float_right()
{
    $float = '';
    if ((_pz('float_right_ontop') && !wp_is_mobile()) || (_pz('float_right_mobile_show') && wp_is_mobile())) {
        $scrollTo = zib_random_true(5) && ZibPay::set_rebate_status(1, 'status', 'obj') ? '' : 'javascript:(scrollTo());';
        $float .= '<a class="main-shadow muted-2-color ontop radius8 fade" title="返回顶部" href="' . $scrollTo . '"><i class="fa fa-angle-up em12"></i></a>';
    }
    if (in_array('float_right', (array)_pz('theme_mode_button', array('pc_nav', 'm_menu')))) {
        $float .= '<a class="main-shadow muted-2-color radius8 toggle-theme mt10" title="切换主题" href="javascript:;">' . zib_svg('theme') . '</a>';
    }
    $float = apply_filters('zib_float_right', $float);
    echo '<div class="float-right text-center">' . $float . '</div>';
}
add_action('wp_footer', 'zib_float_right', 10);


//-----底部页脚内容------
if (_pz('fcode_template') == 'template_1') {
    add_action('zib_footer_conter', 'zib_footer_con');
}
function zib_footer_con()
{

    $show_xs_1 = _pz('footer_t1_m_s');
    $show_xs_3 = _pz('footer_mini_img_m_s', true);
    $html = '';
    $box = '<li' . (!$show_xs_1 ? ' class="hidden-xs"' : '') . ' style="max-width: 300px;">' . zib_footer_con_1() . '</li>';
    $box .= '<li style="max-width: 550px;">' . zib_footer_con_2() . '</li>';
    $box .= '<li' . (!$show_xs_3 ? ' class="hidden-xs"' : '') . '>' . zib_footer_con_3() . '</li>';

    $c_code = _pz('fcode_customize_code');
    $c_code = $c_code ? '<p class="footer-conter">' . $c_code . '</p>' : '';
    $html = '<ul class="list-inline">' . $box . '</ul>';
    $html .= $c_code;
    echo $html;
}

function zib_footer_con_1()
{
    $html = '';
    $s_src = ZIB_STYLESHEET_DIRECTORY_URI . '/img/thumbnail-sm.svg';
    if (_pz('footer_t1_img')) {
        $html .= '<p><a class="footer-logo" href="' . esc_url(home_url()) . '" title="' . _pz('hometitle') . '">
                    ' . zib_get_adaptive_theme_img(_pz('footer_t1_img'), _pz('footer_t1_img_dark'), _pz('hometitle'), 'class="lazyload" height="40"', true) . '
                </a></p>';
    }

    if (_pz('footer_t1_t')) {
        $html .= '<p class="title-h-left">' . _pz('footer_t1_t') . '</p>';
    }

    if (_pz('fcode_t1_code')) {
        $html .= '<div class="footer-muted em09">' . _pz('fcode_t1_code') . '</div>';
    }
    return $html;
}

function zib_footer_con_2()
{
    $html = '';

    if (_pz('fcode_t2_code_1')) {
        $html .= '<p class="fcode-links">' . _pz('fcode_t2_code_1') . '</p>';
    }

    if (_pz('fcode_t2_code_2')) {
        $html .= '<div class="footer-muted em09">' . _pz('fcode_t2_code_2') . '</div>';
    }
    $s_src = ZIB_STYLESHEET_DIRECTORY_URI . '/img/thumbnail-sm.svg';;
    $m_show = _pz('footer_contact_m_s', true) ? '' : ' hidden-xs';
    $html .= '<div class="footer-contact mt10' . $m_show . '">';
    if ((!wp_is_mobile() || _pz('footer_contact_m_s', true)) && _pz('footer_contact_wechat_img')) {
        $s_img = '';
        $s_img .= '<div class="hover-show-con footer-wechat-img">';
        $s_img .= '<img style="box-shadow: 0 5px 10px rgba(0,0,0,.2); border-radius:4px;" class="lazyload" height="100" src="' . $s_src . '" data-src="' . _pz('footer_contact_wechat_img') . '">';
        $s_img .= '</div>';

        $html .= '<a class="toggle-radius hover-show" href="javascript:;">' . zib_svg('d-wechat') . $s_img . '</a>';
    }
    if (_pz('footer_contact_qq')) {
        $html .= '<a class="toggle-radius" data-toggle="tooltip" title="QQ联系" href="http://wpa.qq.com/msgrd?v=3&uin=' . _pz('footer_contact_qq') . '&site=qq&menu=yes">' . zib_svg('d-qq', '-50 0 1100 1100') . '</a>';
    }
    if (_pz('footer_contact_weibo')) {
        $html .= '<a class="toggle-radius" data-toggle="tooltip" title="微博" href="' . _pz('footer_contact_weibo') . '">' . zib_svg('d-weibo') . '</a>';
    }
    if (_pz('footer_contact_email')) {
        $html .= '<a class="toggle-radius" data-toggle="tooltip" title="发邮件" href="mailto:' . _pz('footer_contact_email') . '">' . zib_svg('d-email', '-20 80 1024 1024') . '</a>';
    }
    $html .= '</div>';
    return $html;
}


function zib_footer_con_3()
{
    $html = '';
    $imgs = (array)_pz('footer_mini_img');
    if (!$imgs) return;
    $s_src = ZIB_STYLESHEET_DIRECTORY_URI . '/img/thumbnail-sm.svg';
    foreach ($imgs as $img) {
        if (!empty($img['image'])) {
            $text = !empty($img['text']) ? $img['text'] : '';
            $html .= '<div class="footer-miniimg"' . ($text ? ' data-toggle="tooltip" title="' . $text  . '"' : '') . '>
            <p>
            <img class="lazyload" src="' . $s_src . '" data-src="' . $img['image'] . '">
            </p>
            <span class="opacity8 em09">' . $text . '</span>
        </div>';
        }
    }
    return $html;
}

/**挂钩_GET参数打开tab */
function zib_url_show_tab()
{

    if (!empty($_GET['show_tab'])) {
        echo '<script type="text/javascript">';
        echo 'window._win.show_tab = "' . $_GET['show_tab'] . '";';
        if (!empty($_GET['show_tab2'])) {
            echo 'window._win.show_tab2 = "' . $_GET['show_tab2'] . '";';
        }
        if (!empty($_GET['show_tab3'])) {
            echo 'window._win.show_tab3 = "' . $_GET['show_tab3'] . '";';
        }
        echo '</script>';
    }
}
add_action('wp_footer', 'zib_url_show_tab', 99);
