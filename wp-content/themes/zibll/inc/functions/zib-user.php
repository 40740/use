<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-09-29 13:18:38
 * @LastEditTime: 2021-01-07 23:15:59
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

/**
 * @description: 获取登录、注册页面路由
 * @param {*} $tab signin|signup|resetpassword
 * @return {*}
 */
function zib_get_sign_url($tab = 'signin')
{
    $url = zib_get_template_page_url('pages/user-sign.php');
    return add_query_arg('tab', $tab, $url);
}

function zib_get_repas_link($class = 'muted-2-color', $text = '找回密码')
{
    if (is_page_template('pages/user-sign.php')) {
        return '<a class="' . $class . '"  href="#tab-resetpassword" data-toggle="tab">' . $text . '</a>';
    } else {
        $url = add_query_arg('redirect_to', home_url(add_query_arg(null, null)), zib_get_sign_url('resetpassword'));
        return '<a class="' . $class . '" href="' . $url . '">' . $text . '</a>';
    }
}

function zib_get_sign_logo($url = false)
{
    $logo_html = '';
    $atl = _pz('hometitle') ? _pz('hometitle') : get_bloginfo('name') . (get_bloginfo('description') ? _get_delimiter() . get_bloginfo('description') : '');
    $logo_img = zib_get_adaptive_theme_img(_pz('user_card_option', 0, 'user_logo'), _pz('user_card_option', 0, 'user_logo_dark'), $atl, 'class="lazyload"', true);
    if (!$logo_img) return;
    if ($url) {
        $logo_img = '<a href="' . esc_url($url) . '">' . $logo_img . '</a>';
    }
    $logo_html .= '<div class="text-center"><div class="sign-logo box-body">';
    $logo_html .= $logo_img;
    $logo_html .= '</div></div>';
    return $logo_html;
}

//判断是否关闭了用户登录注册功能
function zib_is_close_sign($but = false)
{
    if (is_super_admin()) return false;
    if (_pz('close_sign', false)) return true;
    if ($but && is_page_template('pages/user-sign.php')) return true;
    return false;
}

/**登录 */
add_action('wp_footer', 'zib_sign_modal');
function zib_sign_modal()
{
    if (zib_is_close_sign(true) || is_user_logged_in() || _pz("user_sign_type") == 'page') {
        return;
    }

    $background = _pz('user_modal_option', 0, 'background');
    $background_url = '';

    if ($background) {
        $background_array = explode(',', $background);
        $rand = array_rand($background_array, 1);
        $background_url = wp_get_attachment_url($background_array[$rand]);
    }

    $background_html = '';

    if ($background_url) {
        $atl = _pz('hometitle') ? _pz('hometitle') : get_bloginfo('name') . (get_bloginfo('description') ? _get_delimiter() . get_bloginfo('description') : '');
        $background_html .= '<div class="sign-img absolute hide-sm">';
        $background_html .= '<img src="' . zib_default_thumb('lg') . '" data-src="' . $background_url . '" alt="' . $atl . '" class="fit-cover radius8 lazyload">';
        $background_html .= '</div>';
    }
    $logo_html = '';
    if (_pz('user_modal_option', 0, 'show_logo')) {
        $logo_html .= zib_get_sign_logo();
    }

?>
    <div class="modal fade" id="u_sign" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="sign-content">
                <?php echo $background_html; ?>
                <div class="sign zib-widget blur-bg relative">
                    <button class="close" data-dismiss="modal">
                        <?php echo zib_svg('close', '0 0 1024 1024', 'ic-close'); ?>
                    </button>
                    <?php echo $logo_html; ?>
                    <?php echo zib_user_signtab_content(); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function zib_user_signtab_content($tab = 'signin', $is_page = false)
{
    $tab_html = '';
    $tab = in_array($tab, array('signin', 'signup', 'resetpassword')) ? $tab : 'signin';
    //登录
    $tab_html .= '<div class="tab-pane fade' . ($tab == 'signin' ? ' active in' : '') . '" id="tab-sign-in">';
    $tab_html .= '<div class="box-body">';
    $tab_html .= '<div class="title-h-left fa-2x">登录</div>';
    $tab_html .= '<a class="muted-color px12" href="#tab-sign-up" data-toggle="tab">没有帐号？立即注册</a>';
    $tab_html .= '</div>';
    $tab_html .= zib_signin_form();
    $tab_html .= '</div>';

    //注册
    $tab_html .= '<div class="tab-pane fade' . ($tab == 'signup' ? ' active in' : '') . '" id="tab-sign-up">';
    $tab_html .= '<div class="box-body">';
    $tab_html .= '<div class="title-h-left fa-2x">注册</div>';
    $tab_html .= '<a class="muted-color px12" href="#tab-sign-in" data-toggle="tab">已有帐号，立即登录</a>';
    $tab_html .= '</div>';
    $tab_html .= zib_signup_form();
    $tab_html .= '</div>';

    if ($is_page) {
        //找回密码
        $tab_html .= '<div class="tab-pane fade' . ($tab == 'resetpassword' ? ' active in' : '') . '" id="tab-resetpassword">';
        $tab_html .= '<div class="box-body">';
        $tab_html .= '<div class="title-h-left fa-2x">找回密码</div>';
        $tab_html .= '<a class="muted-color px12" href="#tab-sign-in" data-toggle="tab">登录</a><i class="icon-spot"></i><a class="muted-color px12" href="#tab-sign-up" data-toggle="tab">注册</a>';
        $tab_html .= '</div>';
        $tab_html .= zib_resetpassword_form();
        $tab_html .= '</div>';
    }
    $tab_html .= zib_get_slider_verification_tab();

    echo '<div class="tab-content">' . $tab_html . '</div>';
}

/**
 * @description: 获取图形验证码input
 * @param {*} $id
 * @param {*} $name
 * @return {*}
 */
function zib_get_slider_verification_tab($id = 'img_verification', $name = 'canvas_yz')
{
    $yz =  _pz('user_verification_type', 'slider');

    if ($yz != 'slider') return;
    $tab_html = '';
    $tab_html .= '<li class="hide"><a href="#tab-slidercaptcha" data-toggle="tab"></a></li>';
    $tab_html .= '<div class="tab-pane fade slidercaptcha-tab" id="tab-slidercaptcha">';
    $tab_html .= '<div style="width: 270px;margin: auto;margin-bottom: 20px;">';
    $tab_html .= '<div class="title-h-left fa-2x">安全验证</div>';
    $tab_html .= '<a class="muted-2-color px12 slidercaptcha-back" data-toggle="tab" href="javascript:;" style="display:none;"><i class="fa fa-angle-left mr6"></i><text>返回上一级</text></a>';
    $tab_html .= '</div>';
    $tab_html .= '<div class="slidercaptcha"><div style="padding: 10px;"><p class="placeholder" style="height: 135px;"></p><div class="placeholder" style="height: 42px;"></div></div></div>';
    $tab_html .= '<p></p>';
    $tab_html .= '</div>';
    return $tab_html;
}


/**
 * @description: 获取图形验证码input
 * @param {*} $id
 * @param {*} $name
 * @return {*}
 */
function zib_get_img_verification_input($id = 'img_verification', $name = 'canvas_yz')
{
    $yz =  _pz('user_verification_type', 'slider');

    if ($yz != 'image') return;

    $input = '<div class="relative line-form mb10">';
    $input .= '<input type="text" name="' . $name . '" class="line-form-input" canvas-id="' . $id . '" autocomplete="off" tabindex="5">';
    $input .= '<div class="scale-placeholder">图形验证码</div>';
    $input .= '<span class="yztx abs-right pointer" data-toggle="tooltip" title="点击刷新"><canvas id="' . $id . '" canvas-code="' . $id . '" width="92" height="38"></canvas></span>';
    $input .= '<div class="abs-right match-ok muted-color"></div><i class="line-form-line"></i>';
    $input .= '</div>';
    return $input;
}

//找回密码的form
function zib_resetpassword_form($type = '')
{
    $html = '';
    $input = '';

    //用户名
    $captcha_type = _pz('user_repas_captch_type', 'email');
    $input .= zib_get_sign_captch($captcha_type, 'resetpassword_captcha');

    $input .= '<div class="relative line-form mb10">';
    $input .= '<input type="password" name="password2" class="line-form-input" tabindex="3" placeholder="">';
    $input .= '<div class="scale-placeholder">设置新密码</div>';
    $input .= '<div class="abs-right passw muted-2-color"><i class="fa-fw fa fa-eye"></i></div><i class="line-form-line"></i>';
    $input .= '</div>';

    $input .= '<div class="relative line-form mb10">';
    $input .= '<input type="password" name="repassword" class="line-form-input" tabindex="4" placeholder="">';
    $input .= '<div class="scale-placeholder">重复密码</div>';
    $input .= '<div class="abs-right passw muted-2-color"><i class="fa-fw fa fa-eye"></i></div><i class="line-form-line"></i>';
    $input .= '</div>';
    //按钮
    $input .= '<div class="box-body">';
    $input .= '<input type="hidden" name="action" value="reset_password">';
    $input .= '<input type="hidden" name="repeat" value="1">';
    $input .= wp_nonce_field('resetpassword', '_nonce', false, false);
    $input .= '<button type="button" class="but radius jb-green padding-lg signsubmit-loader btn-block">确认提交</button>';

    $input .= '</div>';

    $html = '<form id="sign-up">' . $input . '</form>';

    return $html;
}

//注册form
function zib_signup_form($type = '')
{

    $html = '';
    $input = '';
    $captch = _pz('user_signup_captch');

    //用户名
    $input .= '<div class="relative line-form mb10">';
    $input .= '<input type="text" name="name" class="line-form-input" tabindex="1" placeholder=""><i class="line-form-line"></i>';
    $input .= '<div class="scale-placeholder">设置用户名</div>';
    $input .= '</div>';

    if ($captch) {
        $captcha_type = _pz('captch_type', 'email');
        $input .= zib_get_sign_captch($captcha_type);
    }

    $input .= '<div class="relative line-form mb10">';
    $input .= '<input type="password" name="password2" class="line-form-input" tabindex="3" placeholder="">';
    $input .= '<div class="scale-placeholder">设置密码</div>';
    $input .= '<div class="abs-right passw muted-2-color"><i class="fa-fw fa fa-eye"></i></div><i class="line-form-line"></i>';
    $input .= '</div>';

    if (!$captch || !_pz('user_signup_no_repas')) {
        $input .= '<div class="relative line-form mb10">';
        $input .= '<input type="password" name="repassword" class="line-form-input" tabindex="4" placeholder="">';
        $input .= '<div class="scale-placeholder">设置密码</div>';
        $input .= '<div class="abs-right passw muted-2-color"><i class="fa-fw fa fa-eye"></i></div><i class="line-form-line"></i>';
        $input .= '</div>';
    }

    if (!$captch) {
        $input .= zib_get_img_verification_input('img_yz_signup_captcha');
    }

    //按钮
    $input .= '<div class="box-body">';
    $input .= '<input type="hidden" name="action" value="user_signup">';
    $input .= wp_nonce_field('signup', '_nonce', false, false);
    $input .= '<button type="button" class="but radius jb-green padding-lg signsubmit-loader btn-block">' . zib_svg('signup', '0 0 1024 1024', 'icon mr10') . '注册</button>';
    $agreement = zib_get_user_agreement('注册即表示同意');
    if ($agreement) {
        $input .= '<div class="muted-color mt10 text-center px12 opacity8">' . $agreement . '</div>';
    }
    $input .= '</div>';

    $html = '<form id="sign-up">' . $input . '</form>';

    return $html;
}

//登录form
function zib_signin_form($type = '')
{

    $phone_s = _pz('user_signin_phone_s');
    $nopas_s = _pz('user_signin_nopas_s');
    $nonce_input = wp_nonce_field('signin', '_nonce', false, false);

    $html = '';
    $input = '';

    //用户名
    $name_placeholder = $phone_s ? '用户名/手机号/邮箱' : '用户名或邮箱';
    $input .= '<div class="relative line-form mb10">';
    $input .= '<input type="text" name="username" class="line-form-input" tabindex="1" placeholder=""><i class="line-form-line"></i>';
    $input .= '<div class="scale-placeholder">' . $name_placeholder . '</div>';
    $input .= '</div>';

    //密码
    $input .= '<div class="relative line-form mb10">';
    $input .= '<input type="password" name="password" class="line-form-input" tabindex="2" placeholder="">';
    $input .= '<div class="scale-placeholder">登录密码</div>';
    $input .= '<div class="abs-right passw muted-2-color"><i class="fa-fw fa fa-eye"></i></div><i class="line-form-line"></i>';
    $input .= '</div>';

    //人机验证
    $input .= zib_get_img_verification_input('img_yz_signin');

    //记住登录
    $input .= '<div class="relative line-form mb10 em09">';

    $input .= '<span class="muted-color form-checkbox"><input type="checkbox" id="remember" checked="checked" tabindex="4" name="remember" value="forever"><label for="remember">记住登录</label></span>';

    //找回密码
    $input .= '<span class="pull-right muted-2-color">';
    $input .= zib_get_repas_link();
    //免密登录
    if ($nopas_s) {
        $input .= '<span class="opacity5"> | </span><a class="muted-2-color" data-toggle="tab" href="#tab-signin-nopas">免密登录</a> ';
    }
    $input .= '</span>';
    $input .= '</div>';

    //登录按钮
    $input .= '<div class="box-body">';
    $input .= '<input type="hidden" name="action" value="user_signin">';
    $input .= wp_nonce_field('signin', '_nonce', false, false);
    $input .= '<button type="button" class="but radius jb-blue padding-lg signsubmit-loader btn-block"><i class="fa fa-sign-in mr10"></i>登录</button>';
    $input .= '</div>';
    $input = '<form>' . $input . '</form>';

    //一键登录-免密登录-验证码登录
    if ($nopas_s) {
        $nopas_input = '';

        $nopas_type = _pz('user_signin_nopas_type', 'email');
        $nopas_input .= zib_get_sign_captch($nopas_type, 'signin_captcha');
        $nopas_input .= '<div class="relative line-form mb10 em09">';

        $nopas_input .= '<span class="muted-color form-checkbox"><input type="checkbox" id="remember2" checked="checked" tabindex="4" name="remember" value="forever"><label for="remember2">记住登录</label></span>';

        //找回密码
        $nopas_input .= '<span class="pull-right muted-2-color">';
        //免密登录
        $nopas_input .= '<a class="muted-2-color" data-toggle="tab" href="#tab-signin-pas">帐号密码登录</a> ';
        $nopas_input .= '</span>';
        $nopas_input .= '</div>';

        //登录按钮
        $nopas_input .= '<div class="box-body">';
        $nopas_input .= '<input type="hidden" name="action" value="user_signin_nopas">';
        $nopas_input .= $nonce_input;
        $nopas_input .= '<button type="button" class="but radius jb-blue padding-lg signsubmit-loader btn-block"><i class="fa fa-sign-in mr10"></i>登录</button>';
        $nopas_input .= '</div>';

        $nopas_input = '<form>' . $nopas_input . '</form>';

        //默认显示免密登录还是帐号密码登录
        $nopas_active = _pz('user_signin_nopas_active', 'nopas') == 'nopas';
        $html  .= '<div class="tab-content">';
        $html  .= '<div class="tab-pane fade' . ($nopas_active ? ' active in' : '') . '" id="tab-signin-nopas">' . $nopas_input . '</div>';
        $html  .= '<div class="tab-pane fade' . ($nopas_active ? '' : ' active in') . '" id="tab-signin-pas">' . $input . '</div>';
        $html  .= '</div>';
    } else {
        $html  .=  $input;
    }


    //社交登录
    $social_login = zib_social_login(false);
    if ($social_login) {
        $html .= '<p class="social-separator separator muted-3-color em09">社交帐号登录</p>';
        $html .= '<div class="social_loginbar">';
        $html .= $social_login;
        $html .= '</div>';
        $agreement = zib_get_user_agreement('使用社交帐号登录即表示同意');
        if ($agreement) {
            $html .= '<div class="muted-color mt10 text-center px12 opacity8">' . $agreement . '</div>';
        }
    }

    $html = '<div id="sign-in">' . $html . '</div>';

    return $html;
}

function zib_get_sign_captch($captcha_type = 'email', $action = 'signup_captcha')
{

    $input_placeholder = '邮箱';
    if ($captcha_type == 'phone') {
        $input_placeholder = '手机号';
    } elseif ($captcha_type == 'email_phone') {
        $input_placeholder = '手机号或邮箱';
    }
    $input = '';
    $input .= '<div class="relative line-form mb10">';
    $input .= '<input change-show=".change-show" type="text" name="' . esc_attr($captcha_type) . '" class="line-form-input" tabindex="1" placeholder=""><i class="line-form-line"></i>';
    $input .= '<div class="scale-placeholder">' . $input_placeholder . '</div>';
    $input .= '</div>';
    $input .=  zib_get_img_verification_input('img_yz_' . $action);

    $input .= '<div class="relative line-form mb10 change-show">';
    $input .= '<input type="text" name="captch" class="line-form-input" autocomplete="off" tabindex="2" placeholder=""><i class="line-form-line"></i>';
    $input .= '<div class="scale-placeholder">验证码</div>';
    $input .= '<span class="yztx abs-right"><button type="button" form-action="' . $action . '" class="but c-blue captchsubmit">发送验证码</button></span>';
    $input .= '<div class="abs-right match-ok muted-color"><i class="fa-fw fa fa-check-circle"></i></div>';
    $input .= '<span><input type="hidden" name="captcha_type" value="' . $captcha_type . '"></span>';
    $input .= '</div>';
    return $input;
}


/**退出登录 */
add_action('wp_footer', 'zib_signout_modal');
function zib_signout_modal()
{
    if (is_user_logged_in()) {
        global $current_user; ?>
        <div class="modal fade" id="modal_signout" tabindex="-1" role="dialog">
            <div class="modal-dialog" style="max-width:350px;margin: auto;" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h4>您好！ <?php echo $current_user->display_name ?></h4>
                        <p style="color: #ff473a">确认要退出当前登录吗？</p>
                    </div>
                    <div class="box-body text-right">
                        <button type="button" class="but mr10" data-dismiss="modal">取消</button>
                        <a type="button" class="but c-red" href="<?php echo wp_logout_url(home_url()) ?>">确认退出</a>
                    </div>
                </div>
            </div>
        </div>
<?php }
}


/**
 * @description: 获取用户协议和隐私声明的文字
 * @param {*} $before
 * @param {*} $glue
 * @return {*}
 */
function zib_get_user_agreement($before = '', $glue = '、')
{
    $user_agreement_s = _pz('user_agreement_s');
    $agreement_args = array();
    if ($user_agreement_s) {
        $agreement_args[] = '<a class="focus-color" target="_blank" href="' . get_permalink(_pz('user_agreement_page')) . '">用户协议</a>';
    }
    $user_privacy_s = _pz('user_privacy_s');

    if ($user_privacy_s) {
        $agreement_args[] = '<a class="focus-color" target="_blank" href="' . get_permalink(_pz('user_privacy_page')) . '">隐私声明</a>';
    }

    if (!$agreement_args) return;
    return $before . implode($glue, $agreement_args);
}

/**
 * @description: 获取同意用户协议的input
 * @param {*} $before
 * @param {*} $checked
 * @return {*}
 */
function zib_get_agreement_input($before = '阅读并同意', $checked = true)
{
    $agreement = zib_get_user_agreement($before);
    if (!$agreement) return;
    $checked = $checked ? ' checked="checked"' : '';
    $input = '<input name="user_agreement" id="user_agreement" type="checkbox"' . $checked . '>';
    $input .= '<label for="user_agreement" class="px12" style="font-weight:normal;">' . $agreement . '</label>';
    return '<div class="muted-color form-checkbox mb20">' . $input . '</div>';
}

/**
 * @description: 根据meta获取用户
 * @param {*} $field
 * @param {*} $value
 * @return {*}
 */
function zib_get_user_by($field = 'phone', $value)
{
    $cache = wp_cache_get($value, 'user_by_' . $field, true);
    if ($cache) return  $cache;
    $query = new WP_User_Query(array('meta_key' => 'phone_number', 'meta_value' => $value));

    if (!is_wp_error($query) && !empty($query->get_results())) {
        $user = $query->get_results()[0];
        wp_cache_set($value, $user, 'user_by_' . $field);
        return $user;
    } else {
        return false;
    }
}

/**
 * @description: 用户模块
 * @param {*} $loged_title 文案
 * @return {*}
 */
function zib_posts_user_box($loged_title = 'Hi！请登录',$show_payvip = 0)
{
    $cuid = get_current_user_id();
    $cover = '<img class="lazyload fit-cover" src="' . zib_default_thumb() . '" data-src="' . _pz('user_cover_img', ZIB_STYLESHEET_DIRECTORY_URI . '/img/user_t.jpg') . '">';
    $avatar = '<img class="fit-cover avatar lazyload" data-src="' . zib_default_avatar() . '">';
    if (is_user_logged_in()) {
        $args = array(
            'user_id' => $cuid,
            'show_posts' => false,
            'show_img_bg' => true,
            'show_payvip_button' => $show_payvip,
        );
        return zib_posts_avatar_box($args);
    } elseif (!zib_is_close_sign()) {
        echo '<div class="article-author zib-widget relative">';
        echo '<div class="avatar-img-bg">';
        echo $cover;
        echo '</div>';
        echo '<ul class="list-inline avatar-info radius8">
            <li><div class="avatar-img avatar-lg">' . $avatar . '</div></li>';

        echo '<div class="text-center">
            <p class="muted-3-color box-body notop">' . $loged_title . '</p>
        <p>
            <a href="javascript:;" class="signin-loader but jb-blue padding-lg"><i class="fa fa-fw fa-sign-in mr10"></i>登录</a>
            <a href="javascript:;" class="signup-loader ml10 but jb-yellow padding-lg">' . zib_svg('signup', '0 0 1024 1024', 'icon mr10') . '注册</a>
        </p>';
        zib_social_login();
        echo '</div>';
        echo '</ul>';
        echo '</div>';
    }
}

/**
 * @description: 用户绑定邮箱或者手机的from构建
 * @param {*} $type
 * @param {*} $user_id
 * @return {*}
 */
function zib_get_user_bind_from($type = 'email', $user_id = '')
{
    $user_id = $user_id ? $user_id : get_current_user_id();
    $input = '';
    $form = '';
    $html = '';
    $nonce_input = wp_nonce_field('bind_' . $type, '_nonce', false, false);


    $input = '';
    $input .= '<div class="mb20">';
    if ($type == 'email' && !_pz('user_bind_option', true, 'email_set_captch')) {
        $input .= '<div class="relative line-form mb10">';
        $input .= '<input type="text" name="email" class="line-form-input" tabindex="1" placeholder=""><i class="line-form-line"></i>';
        $input .= '<div class="scale-placeholder">请输入邮箱</div>';
        $input .= '</div>';
        $input .=  zib_get_img_verification_input('img_yz_bind_' . $type . '_captcha');
    } else {
        $input .= zib_get_sign_captch($type, 'bind_' . $type . '_captcha');
    }
    $input .= '</div>';

    $input .= zib_get_agreement_input();
    $input .= '<input type="hidden" name="action" value="user_bind_' . $type . '">';
    $input .= $nonce_input;
    $input .= '<button type="button" class="but jb-blue padding-lg btn-block signsubmit-loader"><i class="fa fa-check"></i> 确认提交</button>';

    $form = '<form>' . $input . '</form>';

    return $form;
}

/**
 * @description: 用户中心绑定、修改密码的tab
 * @param {*} $tab
 * @param {*} $user_id
 * @return {*}
 */
function zib_get_user_center_bind_tab($tab = 'email', $user_id = '')
{
    $user_id = $user_id ? $user_id : get_current_user_id();
    $udata = get_userdata($user_id);

    $tab_html = '';

    $tab = in_array($tab, array('email', 'phone', 'change_password')) ? $tab : 'email';
    $tab_html .= '<div class="hide">';
    $tab_html .= '<a href="#tab-bind-email" data-toggle="tab">修改邮箱</a>';
    $tab_html .= '<a href="#tab-change-password" data-toggle="tab">修改密码</a>';
    $tab_html .= '<a href="#tab-bind-phone" data-toggle="tab">绑定手机</a>';
    $tab_html .= '</div>';

    //email
    $user_email = $udata->user_email;
    $title = $user_email ? '修改邮箱' : '绑定邮箱';
    $tab_html .= '<div class="tab-pane fade' . ($tab == 'email' ? ' active in' : '') . '" id="tab-bind-email">';
    $tab_html .= '<div class="title-h-left em12 mb20">' . $title . '</div>';
    $tab_html .= zib_get_user_bind_from('email', $user_id);
    $tab_html .= '</div>';

    //修改密码
    $oauth_new = get_user_meta($user_id, 'oauth_new', true);

    $title = $oauth_new ? '设置密码' : '修改密码';

    $tab_html .= '<div class="tab-pane fade' . ($tab == 'change_password' ? ' active in' : '') . '" id="tab-change-password">';
    $tab_html .= '<div class="title-h-left em12 mb20">' . $title . '</div>';

    $tab_html .= '<form>';
    $tab_html .= '<div class="mb20">';
    if (!$oauth_new) {
        $tab_html .= '<div class="relative line-form mb10">';
        $tab_html .= '<input type="password" name="passwordold" class="line-form-input" tabindex="1" placeholder="">';
        $tab_html .= '<div class="scale-placeholder">请输入原密码</div>';
        $tab_html .= '<div class="abs-right passw muted-2-color"><i class="fa-fw fa fa-eye"></i></div><i class="line-form-line"></i>';
        $tab_html .= '</div>';
    } else {
        $tab_html .= '<input type="hidden" name="oauth_new" value="' . $oauth_new . '">';
    }
    $tab_html .= '<div class="relative line-form mb10">';
    $tab_html .= '<input type="password" name="password" class="line-form-input" tabindex="2" placeholder="">';
    $tab_html .= '<div class="scale-placeholder">请输入新密码</div>';
    $tab_html .= '<div class="abs-right passw muted-2-color"><i class="fa-fw fa fa-eye"></i></div><i class="line-form-line"></i>';
    $tab_html .= '</div>';
    $tab_html .= '<div class="relative line-form mb10">';
    $tab_html .= '<input type="password" name="password2" class="line-form-input" tabindex="3" placeholder="">';
    $tab_html .= '<div class="scale-placeholder">请再输入新密码</div>';
    $tab_html .= '<div class="abs-right passw muted-2-color"><i class="fa-fw fa fa-eye"></i></div><i class="line-form-line"></i>';
    $tab_html .= '</div>';
    $tab_html .=  zib_get_img_verification_input('img_yz_change_password');
    $tab_html .= '</div>';
    $tab_html .= '<input type="hidden" name="action" value="user_change_password">';
    $tab_html .= wp_nonce_field('change_password', '_nonce', false, false);
    $tab_html .= '<button type="button" class="but jb-blue padding-lg btn-block signsubmit-loader"><i class="fa fa-check"></i> 确认提交</button>';
    $tab_html .= '</form>';

    $tab_html .= '</div>';

    if (_pz('user_bind_option', false, 'bind_phone')) {
        //绑定手机
        $phone = get_user_meta($user_id, 'phone_number', true);
        $title = $phone ? '修改手机' : '绑定手机';
        $tab_html .= '<div class="tab-pane fade' . ($tab == 'phone' ? ' active in' : '') . '" id="tab-bind-phone">';
        $tab_html .= '<div class="title-h-left em12 mb20">' . $title . '</div>';
        $tab_html .= zib_get_user_bind_from('phone', $user_id);
        $tab_html .= '</div>';
    }

    $tab_html .= zib_get_slider_verification_tab();

    return '<div class="tab-content box-body nopw-sm">' . $tab_html . '</div>';
}


/**
 * @description: 强制绑定、提醒绑定邮箱、手机的tab内容
 * @param {*} $tab
 * @param {*} $user_id
 * @return {*}
 */
function zib_get_user_bind_tab($bind_type = array('email', 'phone'), $jump_over = false)
{
    $tab = $bind_type[0];
    $udata = wp_get_current_user();
    $user_id = (isset($udata->ID) ? (int) $udata->ID : 0);
    $tab_html = '';

    //email
    if (empty($udata->user_email) && in_array('email', $bind_type)) {
        $title = '绑定邮箱';
        $tab_html .= '<div class="tab-pane fade' . ($tab == 'email' ? ' active in' : '') . '" id="tab-bind-email">';
        $tab_html .= '<div class="title-h-left em12 mb20">' . $title . '</div>';
        $tab_html .= zib_get_user_bind_from('email', $user_id);
        $tab_html .= '</div>';
    } else {
        $tab = 'phone';
    }

    $phone = get_user_meta($user_id, 'phone_number', true);
    if (!$phone && in_array('phone', $bind_type)) {
        //绑定手机
        $title = '绑定手机';
        $tab_html .= '<div class="tab-pane fade' . ($tab == 'phone' ? ' active in' : '') . '" id="tab-bind-phone">';
        $tab_html .= '<div class="title-h-left em12 mb20">' . $title . '</div>';
        $tab_html .= zib_get_user_bind_from('phone', $user_id);
        $tab_html .= '</div>';
    }

    if (!$tab_html) return '';
    $tab_html .= '<div class="hide">';
    $tab_html .= '<a href="#tab-bind-email" data-toggle="tab">修改邮箱</a>';
    $tab_html .= '<a href="#tab-bind-phone" data-toggle="tab">绑定手机</a>';
    $tab_html .= '</div>';
    $tab_html .= zib_get_slider_verification_tab();
    return '<div class="tab-content">' . $tab_html . '</div>';
}

//强制绑定邮箱或手机，页面重定向
function zib_redirect_user_bind_page()
{
    $bind_type = (array)zib_get_user_bind_type();
    if (!$bind_type || is_super_admin()) return;
    $user = wp_get_current_user();
    $tab = !empty($_GET['tab']) ? $_GET['tab'] : '';
    $redirect_to = !empty($_GET['redirect_to']) ? $_GET['redirect_to'] : home_url();
    if (!empty($user->ID) && !is_admin() && $tab != 'bind') {
        //已经登录
        $email = $user->user_email;
        if (!$email && in_array('email', $bind_type)) {
            $bind_url = add_query_arg('redirect_to', $redirect_to, zib_get_sign_url('bind'));
            wp_safe_redirect($bind_url);
            exit;
        }
        $phone = get_user_meta($user->ID, 'phone_number', true);
        if (!$phone && in_array('phone', $bind_type)) {
            $bind_url = add_query_arg('redirect_to', $redirect_to, zib_get_sign_url('bind'));
            wp_safe_redirect($bind_url);
            exit;
        }
    }
}
add_action('template_redirect', 'zib_redirect_user_bind_page');


//挂载用户提醒绑定模态框显示
function zib_bind_reminder_modal()
{
    $user = wp_get_current_user();
    $bind_type = zib_get_user_bind_type('bind_reminder');

    if (isset($_COOKIE["showed_bind_reminder"]) || empty($user->ID) || !$bind_type || is_admin() || is_page_template('pages/user-sign.php')) return;

    //准备
    $user_bind_tab = zib_get_user_bind_tab($bind_type, true);
    if (!$user_bind_tab) return;
    $bind_text = _pz('user_bind_option', '', 'bind_reminder_text');
    $modal_con = $bind_text ? '<div class="mb20 em09">' . $bind_text . '</div>' : '';
    $modal_con .= $user_bind_tab;
    $modal = '<div class="modal fade" id="bind_reminder" tabindex="-1" role="dialog">';
    $modal .= '<div class="modal-dialog" style="max-width:400px;" role="document">';
    $modal .= '<div class="modal-content">';
    $modal .= '<div class="modal-body">';
    $modal .= '<button class="close" data-dismiss="modal"><i data-svg="close" data-class="ic-close" data-viewbox="0 0 1024 1024"></i></button>';
    $modal .= '<div class="box-body nopw-sm">' . $modal_con . '</div>';
    $modal .= '</div>';
    $modal .= '</div>';
    $modal .= '</div>';
    $modal .= '</div>';

    $modal .= '<script type="text/javascript">';
    $modal .= 'window.onload = function(){setTimeout(function () {$(\'#bind_reminder\').modal(\'show\');$.cookie("showed_bind_reminder","showed", {path: "/",expires: 1});}, 1000);};';
    $modal .= '</script>';
    echo $modal;
}
add_action('wp_footer', 'zib_bind_reminder_modal');

/**
 * @description: 获取强制绑定或绑定提醒的配置
 * @param {*} $pz_name
 * @return {*}
 */
function zib_get_user_bind_type($pz_name = 'mandatory_bind')
{
    if (zib_is_close_sign()) return array();
    $bind_pz = _pz('user_bind_option', 0, $pz_name);
    $bind_type = array();
    if ($bind_pz == 'email_phone') {
        $bind_type = array('email', 'phone');
    } elseif (in_array($bind_pz, array('phone', 'email'))) {
        $bind_type[] = $bind_pz;
    }
    return $bind_type;
}
