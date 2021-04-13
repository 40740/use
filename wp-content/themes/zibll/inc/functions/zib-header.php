<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-09-29 13:18:38
 * @LastEditTime: 2021-01-08 00:18:40
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */
function zib_header()
{
	$layout = _pz('header_layout', '2');
	$m_nav_align = _pz('mobile_navbar_align', 'right');
	$m_layout = _pz('mobile_header_layout', 'center');
	$show_slide = zib_header_slide_is_show();
?>
	<header class="header header-layout-<?php echo $layout;
										echo $show_slide ? ' show-slide' : ''; ?>">
		<nav class="navbar navbar-top <?php echo $m_layout; ?>">
			<div class="container-fluid container-header">
				<?php zib_navbar_header(); ?>
				<div class="collapse navbar-collapse">
					<?php
					if ($layout != 3) {
						zib_menu_items();
					}
					if ($layout == 2) {
						echo zib_get_menu_search();
					}
					zib_menu_button($layout);
					if ($layout == 3) {
						echo '<div class="navbar-right">';
						zib_menu_items();
						echo '</div>';
					}
					?>
				</div>
			</div>
		</nav>
	</header>

	<?php
	if (wp_is_mobile() || $layout != 2) {
		zib_header_search();
	}
	?>
	<div class="mobile-header">
		<nav <?php echo $m_nav_align != 'top' ? 'mini-touch="mobile-nav" touch-direction="' . $m_nav_align . '"' : ''; ?> class="mobile-navbar visible-xs-block scroll-y mini-scrollbar <?php echo $m_nav_align; ?>">
			<?php
			if (!_pz('nav_fixed', true)) {
				zib_navbar_header();
			}
			zib_nav_mobile();
			if (function_exists('dynamic_sidebar')) {
				echo '<div class="mobile-nav-widget">';
				dynamic_sidebar('mobile_nav_fluid');
				echo '</div>';
			}
			?>
		</nav>
		<div class="fixed-body" data-close=".mobile-navbar"></div>
	</div>
	<?php if ($show_slide) {
		zib_header_slide();
	} ?>
<?php }

function zib_menu_button($layout = 1)
{
	$li = '';
	$button = '';
	$user_id = get_current_user_id();

	if (_pz('nav_newposts')) {
		$button .= zib_get_write_posts_button('but jb-blue radius', _pz('nav_newposts_text', '写文章'));
	}
	$user_id = get_current_user_id();
	if (_pz('nav_pay_vip', true) && !zib_is_close_sign() && (_pz('pay_user_vip_1_s', true) || _pz('pay_user_vip_2_s', true))) {
		$hover_show = '<div class="sub-menu hover-show-con sub-vip-card">' . zibpay_get_vip_card(1) . zibpay_get_vip_card(2) . '</div>';
		if ($user_id) {
			if (!zib_get_user_vip_level($user_id)) {
				$vip_button = '<a class="pay-vip but jb-red radius payvip-icon ml10" href="javascript:;">' . zib_svg('vip_1', '0 0 1024 1024', 'em12 mr3') . '开通会员</a>';
				$button .= '<span class="hover-show">' . $vip_button . $hover_show . '</span>';
			}
		} else {
			$vip_button = '<a class="signin-loader but jb-red radius payvip-icon ml10" href="javascript:;">' . zib_svg('vip_1', '0 0 1024 1024', 'em12 mr3') . '开通会员</a>';
			$button .= '<span class="hover-show">' . $vip_button . $hover_show . '</span>';
		}
	}

	if ($button) {
		$button = '<div class="navbar-form navbar-right navbar-but">' . $button . '</div>';
	}

	$radius_but = in_array('pc_nav', (array)_pz('theme_mode_button', array('pc_nav', 'm_menu')))  ? '<a href="javascript:;" class="toggle-theme toggle-radius">' . zib_svg('theme') . '</a>' : '';
	$radius_but = apply_filters('zib_nav_radius_button', $radius_but, $user_id);

	if ($radius_but) {
		$button .= '<div class="navbar-form navbar-right">' . $radius_but . '</div>';
	}

	$sign_but = '';
	$user_sub = '';
	if (!zib_is_close_sign(true)) {
		$user_sub = '<div class="box-body">' . zib_header_user_box() . '</div>';
		if ($layout == 2) {
			$sign_but = '<li><a href="javascript:;" class="signin-loader">登录</a></li><li><a href="javascript:;" class="signup-loader">注册</a></li>';
			if ($user_id) {
				$avatar = zib_get_data_avatar($user_id);
				$sign_but = '<li><a href="javascript:;" class="navbar-avatar">' . $avatar . '</a>
						<ul class="sub-menu">' . $user_sub . '</ul></li>';
			}
		} else {
			$sign_but = '<li><a href="javascript:;" class="btn' . ($user_id ? '' : ' signin-loader') . '">' . zib_svg('user', '50 0 924 924') . '</a>
							<ul class="sub-menu">
							' . $user_sub . '
							</ul>
						</li>';
		}
	}

	$search_but = ($layout == 2) ? '' : '<li class="relative"><a href="javascript:;" data-toggle-class data-target=".navbar-search" class="btn nav-search-btn">' . zib_svg('search') . '</a></li>';
	$right_but = '<div class="navbar-form navbar-right' . (!$user_id && $layout == 2 ? ' navbar-text' : '') . '">
					<ul class="list-inline splitters relative">
						' . $sign_but . $search_but . '
					</ul>
				</div>';

	$html = '<div class="navbar-form navbar-right hide show-nav-but" style="margin-right:-20px;"><a data-toggle-class data-target=".nav.navbar-nav" href="javascript:;" class="but">' . zib_svg('menu_2', '0 0 1024 1024', '') . '</a></div>';

	if ($layout == 3) {
		$html .= $right_but . $button;
	} else {
		$html .= $button . $right_but;
	}
	echo $html;
}

function zib_header_user_box($type = 'pc')
{
	if (zib_is_close_sign(true)) {
		return;
	}
	$user_id = get_current_user_id();
	$con = '';
	if ($user_id) {
		$avatar = zib_get_data_avatar($user_id);
		//$cover = '<img class="lazyload fit-cover" data-src="' . get_user_cover_img($user_id) . '">';
		$user_data = get_userdata($user_id);
		$name = $user_data->display_name;
		$desc = get_user_desc($user_id);
		$desc = zib_str_cut(strip_tags($desc), 0, 24, '...');

		$like_n = get_user_posts_meta_count($user_id, 'like');
		$view_n = get_user_posts_meta_count($user_id, 'views');
		$followed_n = get_user_meta($user_id, 'followed-user-count', true);
		$com_n = get_user_comment_count($user_id);
		$post_n = (int) count_user_posts($user_id, 'post', true);
		$payvip = zib_get_header_payvip_icon($user_id);

		//消息通知
		$msg_remind = '';
		$msg_icon = '';
		$msg_icon_set = (array)_pz('message_icon_show', array('nav_menu', 'm_nav_user'));
		if (($type == 'mobile' && in_array('m_nav_user', $msg_icon_set)) || ($type == 'pc' && in_array('pc_nav_user', $msg_icon_set))) {
			$msg_icon = zibmsg_get_user_icon($user_id, 'abs-right');
		}

		$items = '<item><span class="badg c-blue" data-toggle="tooltip" title="发布' . $post_n . '篇文章">' . zib_svg('post') . ($post_n ? $post_n : '0') . '</span></item>';
		$items .= '<item><span class="badg c-green" data-toggle="tooltip" title="发布' . $com_n . '条评论">' . zib_svg('comment') . ($com_n ? $com_n : '0') . '</span></item>';
		$items .= '<item><span class="badg c-red" data-toggle="tooltip" title="人气值 ' . $view_n . '">' . zib_svg('huo') . $view_n . '</span></item>';
		$items .= $like_n ? '<item><span class="badg c-purple" data-toggle="tooltip" title="获得' . $like_n . '个点赞">' . zib_svg('like') . $like_n . '</span></item>' : '';
		$items .= $followed_n ? '<item><span class="badg c-yellow" data-toggle="tooltip" title="共' . $followed_n . '个粉丝"><i class="fa fa-heart em09"></i>' . $followed_n . '</span></item>' : '';

		$href = '<a href="' . get_author_posts_url($user_id) . '" ><div class="badg mb6 toggle-radius c-blue">' . zib_svg('user', '50 0 924 924') . '</div><div class="c-blue">个人中心</div></a>';
		$href .= zib_get_write_posts_button('', '发布文章', '<div class="badg mb6 toggle-radius c-green"><i class="fa fa-fw fa-pencil-square-o"></i></div><div class="c-green">', '</div>');
		$href .= '<a href="javascript:;" data-toggle="modal" data-target="#modal_signout" ><div class="badg mb6 toggle-radius c-red">' . zib_svg('signout') . '</div><div class="c-red">退出登录</div></a>';
		if (is_super_admin()) {
			$href .= '</br>';
			$href .= '<a target="_blank" href="' . zib_get_admin_csf_url() . '"><div class="badg mb6 toggle-radius c-yellow">' . zib_svg('theme') . '</div><div class="c-yellow">主题设置</div></a>';
			$href .= '<a target="_blank" href="' . zib_get_customize_widgets_url() . '" ><div class="badg mb6 toggle-radius c-yellow"><i class="fa fa-pie-chart"></i></div><div class="c-yellow">模块配置</div></a>';
			$href .= '<a target="_blank" href="' . admin_url() . '" ><div class="badg mb6 toggle-radius c-yellow">' . zib_svg('set') . '</div><div class="c-yellow">后台管理</div></a>';
		}

		$con .=  '<ul class="list-inline relative">';
		$con .= $msg_icon;
		$con .=  '<li><div class="avatar-img">' . $avatar . '</div></li>';
		$con .=  '<li>';
		$con .=  '<dt class="text-ellipsis user-name">' . $name . '</dt>';
		$con .=  '<dd class="author-desc muted-3-color px12">' . $desc . '</dd>';
		$con .=  '</li>';
		$con .=  $msg_remind;
		$con .=  $payvip ? '<div class="mt10 em09" style="padding: 6px;">' . $payvip . '</div>' : '';
		$con .=  '</ul>';

		$con .=  '<div class="em09 text-center author-tag mb10">' . $items . '</div>';
		$con .=  '<div class="relative"><i class="line-form-line"></i> </div>';
		$con .=  '<div class="text-center mt10 header-user-href">' . $href . '</div>';
	} else {
		$href =  ((_pz('pay_user_vip_1_s', true) || _pz('pay_user_vip_2_s', true)) && _pz('nav_user_pay_vip', true)) ? '<div><a class="em09 signin-loader but jb-red radius4 payvip-icon btn-block mt10" href="javascript:;">' . zib_svg('vip_1', '0 0 1024 1024', 'em12 mr10') . '开通会员 尊享会员权益</a></div>' : '';

		$href .= '<a href="javascript:;" class="signin-loader"><div class="badg mb6 toggle-radius c-blue">' . zib_svg('user', '50 0 924 924') . '</div><div class="c-blue">登录</div></a>';
		$href .= '<a href="javascript:;" class="signup-loader"><div class="badg mb6 toggle-radius c-green">' . zib_svg('signup') . '</div><div class="c-green">注册</div></a>';
		$href .= '<a target="_blank" href="' . add_query_arg('redirect_to', home_url(add_query_arg(null, null)), zib_get_sign_url('resetpassword')) . '"><div class="badg mb6 toggle-radius c-purple">' . zib_svg('user_rp') . '</div><div class="c-purple">找回密码</div></a>';

		$con .=  '<div class="text-center header-user-href">' . $href . '</div>';
		$ocial_login = zib_social_login(false);
		if ($ocial_login) {
			$con .= '<p class="social-separator separator muted-3-color em09 mt10">快速登录</p>';
			$con .= '<div class="social_loginbar">';
			$con .= $ocial_login;
			$con .= '</div>';
		}
	}

	$html = '<div class="sub-user-box">' . $con . '</div>';
	return $html;
}


function zib_get_header_payvip_icon($user_id = 0)
{
	if (!$user_id || (!_pz('pay_user_vip_1_s', true) && !_pz('pay_user_vip_2_s', true))) return;
	$vip_level = zib_get_user_vip_level($user_id);

	if ($vip_level) {
		return '<span class="radius4 payvip-icon btn-block text-center vipbg-v2 ' . $vip_level . '" data-toggle="tooltip" title="会员有效期：' . zib_get_user_vip_exp_date_text($user_id) . '">' . zib_svg('vip_' . $vip_level, '0 0 1024 1024', 'em12 mr6') . '<span>' . _pz('pay_user_vip_' . $vip_level . '_name') . '</span>' . '<span class="ml10 badg jb-yellow vip-expdate-tag">' . zib_get_user_vip_exp_date_text($user_id) . '</span></span>';
	} elseif (_pz('nav_user_pay_vip', true)) {
		$button = '<a class="pay-vip but radius4 payvip-icon btn-block" href="javascript:;">' . zib_svg('vip_1', '0 0 1024 1024', 'em12 mr6') . '开通会员 尊享会员权益</a>';
		return $button;
	}
	return '';
}



function zib_header_search()
{
	$more_cats = _pz('header_search_more_cat_obj', array());

	$args = array(
		'class' => '',
		'show_keywords' => _pz('header_search_popular_key', true),
		'show_history' => _pz('header_search_history_key', true),
		'show_input_cat' => _pz('header_search_cat', true),
		'show_more_cat' => _pz('header_search_more_cat', true),
		'show_posts' => _pz('header_search_posts'),
		'in_cat' => _pz('header_search_cat_in'),
		'more_cats' => $more_cats,
	);
	echo '<div mini-touch="nav_search" touch-direction="top" class="fixed-body main-bg box-body navbar-search nopw-sm">';
	echo '<div class="box-body">';
	echo '<div class="mb20"><button class="close" data-toggle-class data-target=".navbar-search" ><i data-svg="close" data-class="ic-close" data-viewbox="0 0 1024 1024"></i></button></div>';
	zib_get_search($args);
	echo '</div>';
	echo '</div>';
}


function zib_get_menu_search()
{
	$html = '
      <form method="get" class="navbar-form navbar-left" action="' . esc_url(home_url('/')) . '">
        <div class="form-group relative">
          	<input type="text" class="form-control search-input" name="s" placeholder="搜索内容">
			   <div class="abs-right muted-3-color"><button type="submit" tabindex="3" class="null">' . zib_svg('search') . '</button></div>
		</div>
      </form>';
	return $html;
}

function zib_menu_items($location = 'topmenu')
{
	$args = array(
		'container'       => false,
		'container_class' => 'nav navbar-nav',
		'echo'            => false,
		'fallback_cb'     => false,
		'items_wrap'      => '<ul class="nav navbar-nav">%3$s</ul>',
		'theme_location'  => $location,
	);
	if (!wp_is_mobile()) {
		$args['depth'] = 0;
	}

	$menu = wp_nav_menu($args);

	if (!$menu && is_super_admin()) {
		$menu = '<ul class="nav navbar-nav"><li><a href="' . admin_url('nav-menus.php') . '" class="loaderbt">添加导航菜单</a></li></ul>';
	}
	echo $menu;
}

function zib_navbar_header()
{
	$m_layout = _pz('mobile_header_layout', 'center');

	$t = _pz('hometitle') ? _pz('hometitle') : get_bloginfo('name') . (get_bloginfo('description') ? _get_delimiter() . get_bloginfo('description') : '');
	$logo = '<a class="navbar-logo" href="' . get_bloginfo('url') . '" title="' . $t . '">'
		. zib_get_adaptive_theme_img(_pz('logo_src'), _pz('logo_src_dark'), $t, 'height="50"') . '
			</a>';
	$button = '<button type="button" data-toggle-class data-target=".mobile-navbar" class="navbar-toggle">' . zib_svg('menu', '0 0 1024 1024', 'icon em12') . '</button>';
	if ($m_layout == 'center') {
		$button .= '<button type="button" data-toggle-class data-target=".navbar-search" class="navbar-toggle">' . zib_svg('search') . '</button>';
	}

	echo '<div class="navbar-header">
			<div class="navbar-brand">' . $logo . '</div>
			' . $button . '
		</div>';
}

function zib_nav_mobile($location = 'mobilemenu')
{
	$menu = '';
	$args = array(
		'container'       => false,
		'echo'            => false,
		'fallback_cb'     => false,
		'depth'           => 2,
		'items_wrap'      => '<ul class="mobile-menus theme-box">%3$s</ul>',
		'theme_location'  => $location,
	);

	$m_layout = _pz('mobile_header_layout', 'center');

	$menu .= in_array('m_menu', (array)_pz('theme_mode_button', array('pc_nav', 'm_menu'))) ? '<a href="javascript:;" class="toggle-theme toggle-radius">' . zib_svg('theme') . '</a>' : '';

	if ($m_layout != 'center') {
		$menu .= '<a href="javascript:;" data-toggle-class data-target=".navbar-search" class="toggle-radius">' . zib_svg('search') . '</a>';
	}
	$wp_nav_menu = wp_nav_menu($args);

	if (!$wp_nav_menu) {
		$args['theme_location'] = 'topmenu';
		$wp_nav_menu = wp_nav_menu($args);
	}

	if (!$wp_nav_menu && is_super_admin()) {
		$wp_nav_menu = '<ul class="mobile-menus theme-box"><li><a href="' . admin_url('nav-menus.php') . '" class="loaderbt">添加导航菜单</a></li></ul>';
	}
	$menu .= $wp_nav_menu;
	$menu .= '<div class="posts-nav-box" data-title="文章目录"></div>';
	$sub = zib_header_user_box('mobile');

	echo $menu . $sub;
}


function zib_header_slide_is_show()
{
	global $wp_query;
	if (!isset($wp_query)) {
		return false;
	}
	$show_slide = false;
	$wp_query_array = (array)$wp_query;

	//不是第一页不显示
	if (!empty($wp_query_array['query_vars']['paged'])) return false;

	$show_type = _pz('header_slider_show_type');
	if ($show_type == 'only_pc' && wp_is_mobile()) return false;
	if ($show_type == 'only_sm' && !wp_is_mobile()) return false;

	$header_slider_show = (array)_pz('header_slider_show');
	if ($header_slider_show) {
		foreach ($header_slider_show as $show) {
			if (!empty($wp_query_array['is_' . $show])) {
				$show_slide = true;
				continue;
			}
		}
	}
	if (!$show_slide && is_tax('topics') && $header_slider_show && in_array('topics', $header_slider_show)) {
		$show_slide = true;
	}

	return $show_slide;
}
/**
 * @description: 导航栏侧边栏
 * @param {*}
 * @return {*}
 */
function zib_header_slide()
{

	$header_slider = _pz('header_slider');
	$header_slider_option = _pz('header_slider_option');

	//判断配置是否为空
	if (!is_array($header_slider) || !is_array($header_slider_option) || empty($header_slider[0])) return;

	$header_slider_option['class'] = 'slide-index slide-header mb20';
	$header_slider_option['slides'] = $header_slider;
	$show_type = _pz('header_slider_show_type');
	if ($show_type == 'only_pc') $header_slider_option['class'] .= ' hidden-xs';
	if ($show_type == 'only_sm') $header_slider_option['class'] .= ' visible-xs-block';

	//echo json_encode($header_slider_option);
	zib_new_slider($header_slider_option);
}

//编辑菜单-清理菜单缓存
function zib_cache_delete_nav_menu()
{
	wp_cache_delete('mobilemenu', 'nav_menu');
	wp_cache_delete('topmenu', 'nav_menu');
}
add_action('wp_update_nav_menu_item', 'zib_cache_delete_nav_menu');
