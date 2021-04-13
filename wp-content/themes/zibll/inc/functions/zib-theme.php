<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-09-29 13:18:36
 * @LastEditTime: 2021-01-07 15:53:01
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Email         : 770349780@qq.com
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

$theme_data = wp_get_theme();
$_version = $theme_data['Version'];
define('THEME_VERSION', $_version);

// 开启链接管理
add_filter('pre_option_link_manager_enabled', '__return_true');

// 删除WordPress Emoji 表情
if (_pz('remove_emoji', true)) {
	remove_action('admin_print_scripts',	'print_emoji_detection_script');
	remove_action('admin_print_styles',	'print_emoji_styles');
	remove_action('wp_head',	'print_emoji_detection_script',	7);
	remove_action('wp_print_styles',	'print_emoji_styles');
	remove_filter('the_content_feed',	'wp_staticize_emoji');
	remove_filter('comment_text_rss',	'wp_staticize_emoji');
	remove_filter('wp_mail',	'wp_staticize_emoji_for_email');
}
//开启文章格式
add_theme_support('post-formats', array('image', 'gallery', 'video'));
//开启特色图像
add_theme_support('post-thumbnails', array('post', 'page'));

/**
 * 主题启动时执行函数
 *
 * @return void
 */
function zib_init_theme()
{
	global $pagenow;
	if ('themes.php' == $pagenow && isset($_GET['activated'])) {
		wp_redirect(zib_get_admin_csf_url());
		//exit;
	}
}
add_action('after_setup_theme', 'zib_init_theme');
add_action('after_switch_theme', 'zib_init_theme');

function zib_admin_init_theme()
{
	/**
	 * 刷新固定连接
	 */
	flush_rewrite_rules();
}
add_action('admin_head', 'zib_admin_init_theme');

//删除google字体
if (_pz('remove_open_sans', true)) {
	function remove_open_sans()
	{
		wp_deregister_style('open-sans');
		wp_register_style('open-sans', false);
		wp_enqueue_style('open-sans', '');
	}
	add_action('init', 'remove_open_sans');
}

// 禁用更新
if (_pz('display_wp_update')) {
	remove_action('admin_init', '_maybe_update_core');    // 禁止 WordPress 检查更新
	remove_action('admin_init', '_maybe_update_plugins'); // 禁止 WordPress 更新插件
	remove_action('admin_init', '_maybe_update_themes');  // 禁止 WordPress 更新主题
}
//非管理员关闭顶部admin_bar
if (_pz('hide_admin_bar', true) || is_admin()) {
	add_filter('show_admin_bar', '__return_false');
}

if (_pz('disabled_pingback', true)) {
	// 阻止文章内相互 pingback
	add_action('pre_ping', '_noself_ping');
	function _noself_ping(&$links)
	{
		$home = get_option('home');
		foreach ($links as $l => $link) {
			if (0 === strpos($link, $home)) {
				unset($links[$l]);
			}
		}
	}
}

// 搜索内容排除页面
if (_pz('search_no_page')) {
	add_filter('pre_get_posts', 'ri_exclude_page_from_search');
	function ri_exclude_page_from_search($query)
	{
		if ($query->is_search) {
			$query->set('post_type', 'post');
		}
		return $query;
	}
}

// 注册菜单位置
if (function_exists('register_nav_menus')) {
	register_nav_menus(array(
		'topmenu' => __('PC端顶部菜单', 'zib_language'),
		'mobilemenu' => __('移动端菜单(最多支持两级菜单)', 'zib_language'),
	));
}

function _name($name, $fenge = ' ')
{
	$n = 'Zibll';
	return $n . $fenge . $name;
}

//允许SVG图片上传
function zib_custom_upload_mimes($mimes = array())
{
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_action('upload_mimes', 'zib_custom_upload_mimes');

//用zibll的登录页面代替系统的登录页面
function zib_replace_wp_login()
{
	$action = !empty($_REQUEST['action']) ? $_REQUEST['action'] : '';
	if ($action) {
		$redirect_to = !empty($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : '';
		$tab = array();
		$tab['signin'] = array('login');
		$tab['signup'] = array('register');
		$tab['resetpassword'] = array('lostpassword', 'retrievepassword', 'resetpass', 'rp');
		$tab_v = '';
		foreach ($tab as $key => $value) {
			if (in_array($action, $value)) {
				$tab_v = $key;
				break;
			}
		}
		if ($tab_v) {
			$url = add_query_arg('redirect_to', urlencode($redirect_to), zib_get_sign_url($tab_v));
			if (isset($_REQUEST['interim-login'])) {
				$url = add_query_arg('interim-login', 1, $url);
			}
			wp_safe_redirect($url);
			exit();
		}
	}
}
//用zibll的登录页面代替系统的登录页面
function zib_replace_wp_login_sign()
{
	$redirect_to = !empty($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : '';
	$url = add_query_arg('redirect_to', urlencode($redirect_to), zib_get_sign_url('signin'));
	if (isset($_REQUEST['interim-login'])) {
		$url = add_query_arg('interim-login', 1, $url);
	}
	wp_safe_redirect($url);
	exit();
}

if (_pz('replace_wp_login')) {
	add_action('login_init', 'zib_replace_wp_login');
	add_action("login_form_login", 'zib_replace_wp_login_sign');
}

//缓存自动清理
//编辑文章-清理文章缓存
function zib_cache_delete_posts($post_id)
{
	//文章缩略图
	wp_cache_delete($post_id, 'post_thumbnail_url');
	//文章多图模式缩略图
	wp_cache_delete($post_id, 'post_multi_thumbnail');
}
add_action('save_post', 'zib_cache_delete_posts');


//初始化文章参数
function zib_initialization_post_favorite($post_id)
{
	$favorite_count = get_post_meta($post_id, 'favorite', true);
	if (!$favorite_count) {
		update_post_meta($post_id, 'favorite', 0);
	}
}
add_action('save_post', 'zib_initialization_post_favorite');


/*注册专题*/
function zib_register_topics()
{
	$labels = [
		'name'              => __('专题'),
		'singular_name'     => __('专题'),
		'search_items'      => __('搜索专题'),
		'all_items'         => __('所有专题'),
		'parent_item'       => __('父专题'),
		'parent_item_colon' => __('父专题:'),
		'edit_item'         => __('编辑专题'),
		'update_item'       => __('更新专题'),
		'add_new_item'      => __('添加新专题'),
		'new_item_name'     => __('新专题名称'),
		'menu_name'         => __('专题'),
	];
	$args = [
		'description'       => '添加文章专题',
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_in_menu'      => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'query_var'         => true,
	];
	register_taxonomy('topics', ['post'], $args);
}
add_action('init', 'zib_register_topics');


//设置主循环排序方式
function exclude_single_posts_orderby($query)
{
	if ($query->is_main_query()) {
		$query->set('orderby', _pz('list_orderby', 'data'), 'data');
	}
}
add_action('pre_get_posts', 'exclude_single_posts_orderby');

//首页文章排除
function exclude_single_posts_home($query)
{
	$exclude_cats = array();
	if ($query->is_home() && $query->is_main_query()) {
		$home_exclude_posts = _pz('home_exclude_posts', array());
		if ($home_exclude_posts) {
			$query->set('post__not_in', $home_exclude_posts);
		}

		$home_exclude_cats = _pz('home_exclude_cats', array());

		if ($home_exclude_cats) {
			$exclude_cats = array_merge($exclude_cats, $home_exclude_cats);
		}
		$d_cats = _pz('docs_mode_cats');
		if (_pz('docs_mode_exclude') && $d_cats) {
			foreach ($d_cats as $d_cat) {
				$children = get_term_children($d_cat, 'category');
				$d_cats = array_merge($d_cats, $children);
			}
			$exclude_cats = array_merge($exclude_cats, $d_cats);
		}
		$query->set('category__not_in', $exclude_cats);
	}
}
add_action('pre_get_posts', 'exclude_single_posts_home');

//获取用户id
function zib_get_user_id($id_or_email)
{
	$user_id = '';
	if (is_numeric($id_or_email))
		$user_id = (int) $id_or_email;
	elseif (is_string($id_or_email) && ($user = get_user_by('email', $id_or_email)))
		$user_id = $user->ID;
	elseif (is_object($id_or_email) && !empty($id_or_email->user_id))
		$user_id = (int) $id_or_email->user_id;
	return $user_id;
}

//用户头像
function zib_default_avatar()
{
	return _pz('avatar_default_img', ZIB_STYLESHEET_DIRECTORY_URI . '/img/avatar-default.png');
}

function zib_default_thumb($size = '')
{
	$size = $size ? '-' . $size : '';
	return _pz('thumbnail', ZIB_STYLESHEET_DIRECTORY_URI . '/img/thumbnail' . $size . '.svg');
}

function zib_get_data_avatar($user_id = '', $size = '', $alt = '')
{
	$args = array(
		'size'          => $size,
		'height'        => $size,
		'width'         => $size,
		'alt'           => $alt,
	);
	$cache = wp_cache_get($user_id, 'user_avatar', true);
	if (!$cache) {
		$avatar = zib_get_avatar(null, $user_id, $args);
		wp_cache_set($user_id, $avatar, 'user_avatar');
	} else {
		$avatar = $cache;
	}
	if (_pz('lazy_avatar')) {
		$avatar =  str_replace(' src=', ' src="' . zib_default_avatar() . '" data-src=', $avatar);
	}
	return $avatar;
}

add_filter('pre_get_avatar', 'zib_get_avatar', 10, 3);
function zib_get_avatar($avatar, $user_id, $args)
{
	$custom_avatar = get_user_meta($user_id, 'custom_avatar', true);
	$alt = $args['alt'] ? $args['alt'] : get_the_author_meta('nickname', $user_id);

	$avatar = $custom_avatar ? $custom_avatar : zib_default_avatar();
	$avatar = preg_replace("/^(https:|http:)/", "", $avatar);
	$avatar = '<img alt="' . esc_attr($alt) . '" src="' . esc_url($avatar) . '" class="lazyload avatar avatar-' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" width="' . esc_attr($args['size']) . '">';
	return $avatar;
}

// 侧边栏显示判断
function zib_is_show_sidebar()
{
	$is = false;
	if (_pz('sidebar_home_s') && is_home()) {
		$is = true;
	}
	if (_pz('sidebar_single_s') && is_single()) {
		$is = true;
	}
	if (_pz('sidebar_page_s', false) && is_page()) {
		$is = true;
	}
	if (_pz('sidebar_cat_s') && is_category()) {
		$is = true;
	}
	if (_pz('sidebar_tag_s') && is_tag()) {
		$is = true;
	}
	if (_pz('sidebar_search_s') && is_search()) {
		$is = true;
	}
	if (is_single() || is_page()) {
		$show_layout = get_post_meta(get_queried_object_id(), 'show_layout', true);
		$sites = array("sidebar_left", "sidebar_right");
		if (in_array($show_layout, $sites)) {
			$is = true;
		} elseif ($show_layout == 'no_sidebar') {
			$is = false;
		}
	}
	if (is_page_template('pages/postsnavs.php')) {
		$is = true;
	}
	if (wp_is_mobile()) {
		$is = false;
	}
	if (is_page_template('pages/newposts.php')) {
		$is = true;
	}
	if (is_page_template('pages/sidebar.php')) {
		$is = true;
	}
	return $is;
}

//获取随机布尔值
function zib_random_true($inex = 5)
{
	return (rand() % $inex === 0);
}


// 禁止非管理员登录后台
add_action('admin_head', 'zib_no_entry_backstage');
function zib_no_entry_backstage()
{
	if (!is_super_admin() && empty($_REQUEST) && '/wp-admin/admin-ajax.php' != $_SERVER['PHP_SELF']) {
		wp_redirect(home_url());
		exit;
	}
}

// 分类链接删除 'category'
if (_pz('no_categoty') && !function_exists('no_category_base_refresh_rules')) {
	register_activation_hook(__FILE__, 'no_category_base_refresh_rules');
	add_action('created_category', 'no_category_base_refresh_rules');
	add_action('edited_category', 'no_category_base_refresh_rules');
	add_action('delete_category', 'no_category_base_refresh_rules');
	function no_category_base_refresh_rules()
	{
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}

	register_deactivation_hook(__FILE__, 'no_category_base_deactivate');
	function no_category_base_deactivate()
	{
		remove_filter('category_rewrite_rules', 'no_category_base_rewrite_rules');
		// We don't want to insert our custom rules again
		no_category_base_refresh_rules();
	}

	// Remove category base
	add_action('init', 'no_category_base_permastruct');
	function no_category_base_permastruct()
	{
		global $wp_rewrite, $wp_version;
		if (version_compare($wp_version, '3.4', '<')) {
			// For pre-3.4 support
			$wp_rewrite->extra_permastructs['category'][0] = '%category%';
		} else {
			$wp_rewrite->extra_permastructs['category']['struct'] = '%category%';
		}
	}

	// Add our custom category rewrite rules
	add_filter('category_rewrite_rules', 'no_category_base_rewrite_rules');
	function no_category_base_rewrite_rules($category_rewrite)
	{
		//var_dump($category_rewrite); // For Debugging

		$category_rewrite = array();
		$categories = get_categories(array('hide_empty' => false));
		foreach ($categories as $category) {
			$category_nicename = $category->slug;
			if ($category->parent == $category->cat_ID) // recursive recursion
				$category->parent = 0;
			elseif ($category->parent != 0)
				$category_nicename = get_category_parents($category->parent, false, '/', true) . $category_nicename;
			$category_rewrite['(' . $category_nicename . ')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
			$category_rewrite['(' . $category_nicename . ')/page/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&paged=$matches[2]';
			$category_rewrite['(' . $category_nicename . ')/?$'] = 'index.php?category_name=$matches[1]';
		}
		// Redirect support from Old Category Base
		global $wp_rewrite;
		$old_category_base = get_option('category_base') ? get_option('category_base') : 'category';
		$old_category_base = trim($old_category_base, '/');
		$category_rewrite[$old_category_base . '/(.*)$'] = 'index.php?category_redirect=$matches[1]';

		//var_dump($category_rewrite); // For Debugging
		return $category_rewrite;
	}
	// Add 'category_redirect' query variable
	add_filter('query_vars', 'no_category_base_query_vars');
	function no_category_base_query_vars($public_query_vars)
	{
		$public_query_vars[] = 'category_redirect';
		return $public_query_vars;
	}

	// Redirect if 'category_redirect' is set
	add_filter('request', 'no_category_base_request');
	function no_category_base_request($query_vars)
	{
		//print_r($query_vars); // For Debugging
		if (isset($query_vars['category_redirect'])) {
			$catlink = trailingslashit(get_option('home')) . user_trailingslashit($query_vars['category_redirect'], 'category');
			status_header(301);
			header("Location:$catlink");
			exit();
		}
		return $query_vars;
	}
}

//颜色转换
function hex_to_rgba($hex, $a)
{
	$hex = str_replace("#", "", $hex);
	if (strlen($hex) == 3) {
		$r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
		$g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
		$b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
	} else {
		$r = hexdec(substr($hex, 0, 2));
		$g = hexdec(substr($hex, 2, 2));
		$b = hexdec(substr($hex, 4, 2));
	}
	$a = $a ? ',' . $a : '';
	$rgb = 'rgb(' . $r . ',' . $g . ',' . $b . $a . ')';
	return $rgb;
}
// 加载css和js文件
add_action('wp_enqueue_scripts', '_load_scripts');
function _load_scripts()
{
	if (!is_admin()) {
		wp_deregister_script('jquery');

		wp_deregister_script('l10n');

		$purl = ZIB_STYLESHEET_DIRECTORY_URI;

		$css = array(
			'no' => array(
				'fontawesome' => 'font-awesome.min',
				'bootstrap' => 'bootstrap.min'
			),
			'baidu' => array(
				'fontawesome' => '//cdn.staticfile.org/font-awesome/4.7.0/css/font-awesome.min.css',
				'bootstrap' => '//apps.bdimg.com/libs/bootstrap/3.3.7/css/bootstrap.min.css'
			),
			'staticfile' => array(
				'fontawesome' => '//cdn.staticfile.org/font-awesome/4.7.0/css/font-awesome.min.css',
				'bootstrap' => '//cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css'
			),
			'bootcdn' => array(
				'fontawesome' => '//cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css',
				'bootstrap' => '//cdn.bootcss.com/twitter-bootstrap/3.3.7/css/bootstrap.min.css'
			),
			'he' => array(
				'fontawesome' => '//cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css',
				'bootstrap' => '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'
			)
		);

		// common css
		_cssloader(array('bootstrap' => _pz('js_outlink') ? $css[_pz('js_outlink')]['bootstrap'] : 'bootstrap.min', 'fontawesome' => _pz('js_outlink') ? $css[_pz('js_outlink')]['fontawesome'] : 'fontawesome.min', 'main' => 'main.min'));

		// page css
		if (is_page_template('pages/newposts.php')) {
			_cssloader(array('new-posts' => 'new-posts.min'));
		}
		// page css
		if (is_page_template('pages/postsnavs.php')) {
			_cssloader(array('page-navs' => 'page-navs.min'));
		}

		$jss = array(
			'no' => array(
				'jquery' => $purl . '/js/libs/jquery.min.js',
				'bootstrap' => $purl . '/js/libs/bootstrap.min.js'
			),
			'baidu' => array(
				'jquery' => '//apps.bdimg.com/libs/jquery/1.9.1/jquery.min.js',
				'bootstrap' => '//apps.bdimg.com/libs/bootstrap/3.3.7/js/bootstrap.min.js'
			),
			'staticfile' => array(
				'jquery' => '//cdn.staticfile.org/jquery/1.9.1/jquery.min.js',
				'bootstrap' => '//cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js'
			),
			'bootcdn' => array(
				'jquery' => '//cdn.bootcss.com/jquery/1.9.1/jquery.min.js',
				'bootstrap' => '//cdn.bootcss.com/twitter-bootstrap/3.3.7/js/bootstrap.min.js'
			),
			'he' => array(
				'jquery' => '//code.jquery.com/jquery-1.9.1.min.js',
				'bootstrap' => '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'
			)
		);
		wp_register_script('jquery', _pz('js_outlink') ? $jss[_pz('js_outlink')]['jquery'] : $purl . '/js/libs/jquery.min.js', false, THEME_VERSION, false);
		wp_enqueue_script('bootstrap', _pz('js_outlink') ? $jss[_pz('js_outlink')]['bootstrap'] : $purl . '/js/libs/bootstrap.min.js', array('jquery'), THEME_VERSION, true);
		_jsloader(array('loader'));
	}
}

function _cssloader($arr)
{
	foreach ($arr as $key => $item) {
		$href = $item;
		if (strstr($href, '//') === false) {
			$href = ZIB_STYLESHEET_DIRECTORY_URI . '/css/' . $item . '.css';
		}
		wp_enqueue_style('_' . $key, $href, array(), THEME_VERSION, 'all');
	}
}
function _jsloader($arr)
{
	foreach ($arr as $item) {
		wp_enqueue_script('_' . $item, ZIB_STYLESHEET_DIRECTORY_URI . '/js/' . $item . '.js', array(), THEME_VERSION, true);
	}
}


function _get_delimiter()
{
	return _pz('connector') ? _pz('connector') : '-';
}

//文章列表新窗口打开
function _post_target_blank()
{
	return _pz('target_blank') ? ' target="_blank"' : '';
}

//中文用户名注册
function chinese_username($username, $raw_username, $strict)
{
	$username = wp_strip_all_tags($raw_username);
	$username = remove_accents($username);
	$username = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '', $username);
	$username = preg_replace('/&.+?;/', '', $username); // Kill entities
	if ($strict) {
		$username = preg_replace('|[^a-z\p{Han}0-9 _.\-@]|iu', '', $username);
	}
	$username = trim($username);
	$username = preg_replace('|\s+|', ' ', $username);
	return $username;
}
add_filter('sanitize_user', 'chinese_username', 10, 3);

//用户中心链接格式化
function zib_author_link($link, $author_id, $author_nicename)
{
	global $wp_rewrite;
	$author_id = (int) $author_id;
	$link = $wp_rewrite->get_author_permastruct();

	if (empty($link)) {
		$file = home_url('/');
		$link = $file . '?author=' . $author_id;
	} else {
		$link = str_replace('%author%', $author_id, $link);
		$link = home_url(user_trailingslashit($link));
	}

	return $link;
}
add_filter('author_link', 'zib_author_link', 10, 3);
function zib_author_link_request($query_vars)
{
	if (array_key_exists('author_name', $query_vars)) {
		global $wpdb;
		$author_id = !empty($query_vars['author_name']) ? $query_vars['author_name'] : get_current_user_id();
		if ($author_id) {
			$query_vars['author'] = $author_id;
			unset($query_vars['author_name']);
		}
	}
	return $query_vars;
}
add_filter('request', 'zib_author_link_request');


function get_the_subtitle($span = true)
{
	global $post;
	$post_ID = $post->ID;
	$subtitle = get_post_meta($post_ID, 'subtitle', true);

	if (!empty($subtitle)) {
		if ($span) {
			return '<span>' . $subtitle . '</span>';
		} else {
			return $subtitle;
		}
	} else {
		return false;
	}
}

//小工具可视化编辑连接
function zib_get_customize_widgets_url()
{
	return esc_url(
		add_query_arg(
			array(
				array('autofocus' => array('panel' => 'widgets')),
				'return' => urlencode(remove_query_arg(wp_removable_query_args(), wp_unslash($_SERVER['REQUEST_URI']))),
			),
			admin_url('customize.php')
		)
	);
}

//主题切换
function zib_get_theme_mode()
{
	$theme_mode = '';
	$theme_mode = _pz('theme_mode');
	$time = current_time('G');
	if ($theme_mode == 'time-auto') {
		if ($time > 19 || $time < 9) {
			$theme_mode = 'dark-theme';
		} else {
			$theme_mode = 'white-theme';
		}
	}
	if (_pz('theme_mode_button', true) && isset($_COOKIE["theme_mode"])) {
		$theme_mode = $_COOKIE["theme_mode"];
	}
	return $theme_mode;
}

//根据主题筛选图片
function zib_get_adaptive_theme_img($white_src = '', $dark_src = '', $atl = '', $more = '', $lazy = false)
{
	if (!$dark_src && !$white_src) return;

	if (!$dark_src) $dark_src = $white_src;
	if (!$white_src) $white_src = $dark_src;
	$lazy_src = ZIB_STYLESHEET_DIRECTORY_URI . '/img/thumbnail-sm.svg';
	if (zib_get_theme_mode() == 'dark-theme') {
		$img = '<img ' . ($lazy ? 'src="' . $lazy_src . '" data-' : '') . 'src="' . $dark_src . '" switch-src="' . $white_src . '" alt="' . $atl . '" ' . $more . '>';
	} else {
		$img = '<img ' . ($lazy ? 'src="' . $lazy_src . '" data-' : '') . 'src="' . $white_src . '" switch-src="' . $dark_src . '" alt="' . $atl . '" ' . $more . '>';
	}
	return $img;
}


function _bodyclass()
{
	$class = '';

	$class .= zib_get_theme_mode();

	if (is_super_admin()) {
		$class .= ' logged-admin';
	}
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	if (_pz('nav_fixed') || (is_home() && $paged == 1 && _pz('index_slide_s') && _pz('index_slide_position', 'top') == 'header' && _pz('index_slide_src_1'))) {
		$class .= ' nav-fixed';
	}

	if (zib_is_show_sidebar()) {

		$show_layout = get_post_meta(get_queried_object_id(), 'show_layout', true);
		if ($show_layout == 'sidebar_left') {
			$layout_class = ' site-layout-3';
		} elseif ($show_layout == 'sidebar_right') {
			$layout_class = ' site-layout-2';
		} else {
			$layout_class = _pz('sidebar_layout') == 'left' ? ' site-layout-3' : ' site-layout-2';
		}
		$class .= $layout_class;
	} else {
		$class .= ' site-layout-1';
	}

	if ((is_single() || is_page()) && get_post_format()) {
		$class .= ' postformat-' . get_post_format();
	}
	return apply_filters('zib_add_bodyclass', trim($class));
}

function _cut_count($number)
{
	$number = (int) $number;
	if ($number > 9999) {
		$number =  round($number / 10000, 1) . 'W+';
	}
	return $number;
}

function get_post_view_count($before = '阅读(', $after = ')', $post_id = 0)
{
	if (!$post_id) {
		global $post;
		$post_id = $post->ID;
	}
	$views = _cut_count(get_post_meta($post_id, 'views', true));
	return $before . $views . $after;
}

function zib_str_cut($str, $start = 0, $width = 100, $trimmarker = '...')
{
	if (($str == '') || is_null($str)) return $str;
	if (_new_strlen($str) < ($width - 2)) return $str;
	$code = 'UTF-8';
	$str = mb_convert_encoding($str, $code, mb_detect_encoding($str, array("UTF-8", "ASCII", "GB2312", "GBK")));
	$start = (int)$start > 0 ? (int)$start : 0;
	$len = (int)$width > 0 ? (int)$width * 2 : null;
	$cl = $byteL = 0;
	$sub = '';
	$sLen =  mb_strlen($str, $code);

	for ($i = 0; $i < $sLen; $i++) {
		$val = mb_substr($str, $i, 1, $code);
		$cl = ord($val) >= 128 ? 2 : 1;
		$byteL += $cl;

		if ($start >= $byteL) { //还不到开始位
			continue;
		}

		if (
			is_null($len) //取完
			|| (($len -= $cl) >= 0) //取本字时不超过
		) {
			$sub .= $val;
		} else { //取超了
			$trimmarker && ($sub .= $trimmarker);
			break;
		}
	}
	return $sub;
}

function zib_get_excerpt($limit = 90, $after = '...')
{
	global $post;
	$excerpt = '';
	if (!empty($post->post_excerpt)) {
		$excerpt = $post->post_excerpt;
	} else {
		$excerpt = $post->post_content;
	}
	$excerpt = trim(str_replace(array("\r\n", "\r", "\n", "　", " "), " ", str_replace("\"", "'", strip_tags(strip_shortcodes($excerpt)))));

	$the = trim(get_post_meta($post->ID, 'description', true));

	if ($the) {
		$excerpt = $the;
	}
	$excerpt = zib_str_cut(strip_tags($excerpt), 0, $limit, $after);
	return $excerpt;
}

function zib_get_post_comments($before = '评论(', $after = ')')
{
	return $before . get_comments_number('0', '1', '%') . $after;
}

function zib_is_url($C_url)
{
	if (preg_match("/^(http:\/\/|https:\/\/).*$/", $C_url)) {
		return true;
	} else {
		return false;
	}
}
//中文文字计数
function _new_strlen($str, $charset = 'utf-8')
{
	//中文算一个，英文算半个
	return (int)((strlen($str) + mb_strlen($str, $charset)) / 4);
}

/**
 * @description: 主-获取文章缩略图
 * @param {*}
 * @return {*}
 */
function zib_post_thumbnail($size = '', $class = 'fit-cover', $url = false)
{
	if (!$size) {
		$size = _pz('thumb_postfirstimg_size');
	}
	global $post;
	$r_src = '';
	//查询缓存
	$cache_url = wp_cache_get($post->ID, 'post_thumbnail_url_' . $size, true);
	if (!$cache_url) {
		$post_thumbnail_id = get_post_thumbnail_id($post->ID);
		if ($post_thumbnail_id) {
			$images = zib_get_attachment_image_src($post_thumbnail_id, $size);
			if ($images) {
				$images = $images[0];
			}
		} elseif (_pz('thumb_postfirstimg_s', true)) {
			$images = zib_get_post_img($size, $class, 1, false, true);
			if (!empty($images[0])) {
				$images_id = zib_get_image_id($images[0]);
				if ($images_id) {
					$images = zib_get_attachment_image_src($images_id, $size);
					if ($images) {
						$images = $images[0];
					}
				} else {
					$images = $images[0];
				}
			}
		}
		if ($images) {
			$r_src = $images;
		} elseif (_pz('thumb_catimg_s', true)) {
			$category = get_the_category();
			foreach ($category as $cat) {
				$r_src = zib_get_taxonomy_img_url($cat->cat_ID, $size);
				if ($r_src) {
					break;
				}
			}
		}
		if ($r_src) {
			//设置缓存
			wp_cache_set($post->ID, $r_src, 'post_thumbnail_url_' . $size);
		} else {
			wp_cache_set($post->ID, 'no', 'post_thumbnail_url_' . $size);
		}
	} else {
		$r_src = $cache_url == 'no' ? false : $cache_url;
	}

	if ($r_src) {
		if ($url) {
			return $r_src;
		}
		if (_pz('lazy_posts_thumb')) {
			return sprintf('<img src="%s" data-src="%s" alt="%s" class="lazyload ' . $class . '">', zib_default_thumb(), $r_src, $post->post_title . _get_delimiter() . get_bloginfo('name'));
		} else {
			return sprintf('<img src="%s" alt="%s" class="' . $class . '">', $r_src, $post->post_title . _get_delimiter() . get_bloginfo('name'));
		}
	} else {
		if ($url) {
			return false;
		}
		return sprintf('<img data-thumb="default" src="%s" alt="%s" class="' . $class . '">', zib_default_thumb(), $post->post_title . _get_delimiter() . get_bloginfo('name'));
	}
}


function zib_get_attachment_image_src($img_id, $size = false)
{
	$url = '';
	if (!$size || $size == 'full') {
		$file = get_post_meta($img_id, '_wp_attached_file', true);
		if ($file) {
			// Get upload directory.
			$uploads = wp_get_upload_dir();
			if ($uploads && false === $uploads['error']) {
				// Check that the upload base exists in the file location.
				if (0 === strpos($file, $uploads['basedir'])) {
					// Replace file location with url location.
					$url = str_replace($uploads['basedir'], $uploads['baseurl'], $file);
				} elseif (false !== strpos($file, 'wp-content/uploads')) {
					// Get the directory name relative to the basedir (back compat for pre-2.7 uploads).
					$url = trailingslashit($uploads['baseurl'] . '/' . _wp_get_attachment_relative_path($file)) . wp_basename($file);
				} else {
					// It's a newly-uploaded file, therefore $file is relative to the basedir.
					$url = $uploads['baseurl'] . "/$file";
				}
			}
		}
		if (empty($url)) {
			$url = get_the_guid($img_id);
		}
		return array($url, 0, 0);
	} else {
		return wp_get_attachment_image_src($img_id, $size);
	}
}

//列表多图模式获取文章图片
function zib_posts_multi_thumbnail($size = '', $class = 'fit-cover')
{
	//return '';
	if (!$size) {
		$size = _pz('thumb_postfirstimg_size');
	}
	global $post;
	$cache_html = wp_cache_get($post->ID, 'post_multi_thumbnail', true);
	if (!$cache_html) {
		$html = zib_get_post_img($size, $class, 4);
		if (_pz('lazy_posts_thumb')) {
			$html = str_replace(' src=', ' src="' . zib_default_thumb() . '" data-src=', $html);
			$html = str_replace(' class="', ' class="lazyload ', $html);
		}
		wp_cache_set($post->ID, $html, 'post_multi_thumbnail');
	} else {
		$html = $cache_html;
	}
	return $html;
}

/**
 * @description: 获取文章内的图片
 * @param {*}
 * @return {*}
 */
function zib_get_post_img($size = '', $class = '', $count = 0, $show_count = false, $show_array = false)
{
	if (!$size) {
		$size = _pz('thumb_postfirstimg_size');
	}
	//"数据库查询": 1300,"执行时间": 1690.0677680969238
	global $post;
	$html = '';
	$content = $post->post_content;
	preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
	$images = $strResult[1];
	$i = 0;
	if ($show_array) {
		return $images;
	}
	if ($show_count) {
		return count($images);
	}
	//"数据库查询": 1298,执行时间": 1652.890920639038
	foreach ($images as $src) {
		$i++;
		$images_id = zib_get_image_id($src);
		$src2 = '';
		//"数据库查询": 1708,"执行时间": 2700.4239559173584
		if ($images_id) {
			$src2 = wp_get_attachment_image_src($images_id, $size)[0];
		}
		//"数据库查询": 1710,"执行时间": 2652.353048324585
		$src = $src2 ? $src2 : $src;
		$html .= '<span><img src="' . $src . '" class="' . $class . '"></span>';
		if ($count && $i == $count) break;
	}
	//开启zib_get_image_id，"数据库查询": 2507,"执行时间": 4456.125974655151
	//开启zib_get_image_id$$wp_get_attachment_image_src，"数据库查询": 2515,,"执行时间": 4520.646095275879
	//"数据库查询": 2515, "执行时间": 4508.143901824951
	return $html;
}

/**
 * @description: 通过图片链接获取图片ID
 * @param {*}
 * @return {*}
 */
function zib_get_image_id($img_url)
{
	$cache_key	= md5($img_url);
	$post_id	= wp_cache_get($cache_key, 'wp_attachment_id', true);
	$post_id	= '';
	if ($post_id == 'noid') {
		return false;
	} elseif (!$post_id) {
		$path = basename($img_url);
		if ($path) {
			global $wpdb;
			$post_id	= $wpdb->get_var("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_wp_attachment_metadata' AND meta_value like '%$path%'");
			$post_id	= $post_id ? $post_id : '';
		} else {
			$post_id	= '';
		}
		if ($post_id) {
			wp_cache_set($cache_key, $post_id, 'wp_attachment_id');
		} else {
			wp_cache_set($cache_key, 'noid', 'wp_attachment_id', 60000);
		}
	}
	return $post_id;
}

//图片灯箱
if (_pz('imagelightbox')) {
	add_filter('the_content', 'imgbox_replace');
	function imgbox_replace($content)
	{
		$pattern = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>(.*?)<\/a>/i";
		$replacement = '<a$1href=$2$3.$4$5 data-imgbox="imgbox"$6>$7</a>';
		$content = preg_replace($pattern, $replacement, $content);
		return $content;
	}
}

//文章图片异步加载
if (_pz('lazy_posts_content')) {
	add_filter('the_content', 'lazy_img_replace');
	function lazy_img_replace($content)
	{
		$pattern = "/<img(.*?)src=('|\")([^>]*).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
		$replacement = '<img$1src="' . ZIB_STYLESHEET_DIRECTORY_URI . '/img/thumbnail-lg.svg' . '" data-src=$2$3.$4$5 $6>';
		$content = preg_replace($pattern, $replacement, $content);
		$pattern = "/<img(.*?)srcset=('|\")([^>]*)('|\")(.*?)>/i";
		$replacement = '<img$1data-srcset=$2$3$4 $5>';
		$content = preg_replace($pattern, $replacement, $content);
		return $content;
	}
}

//昵称是否有保留字符
function is_disable_username($name)
{
	$disable_reg_keywords = _pz('user_nickname_out');
	$disable_reg_keywords = preg_split("/,|，|\s|\n/", $disable_reg_keywords);

	if (!$disable_reg_keywords || !$name) {
		return false;
	}
	foreach ($disable_reg_keywords as $keyword) {
		if (stristr($name, $keyword) || $keyword == $name) {
			return true;
		}
	}
	return false;
}

// 记录用户登录时间
function user_last_login($user_login)
{
	$user = get_user_by('login', $user_login);
	$time = current_time('mysql');
	update_user_meta($user->ID, 'last_login', $time);
}
add_action('wp_login', 'user_last_login');

//时间倒序格式化
function zib_get_time_ago($time)
{
	if (is_int($time)) {
		$time = intval($time);
	} else {
		$time = strtotime($time);
	}

	if (!_pz('time_ago_s', true) && _pz('time_format')) {
		return date(_pz('time_format'), $time);
	}
	$ctime = intval(strtotime(current_time('mysql')));
	$t = $ctime - $time; //时间差 （秒）

	if ($t < 0) {
		return date('Y-m-d H:i', $time);
	}
	$y = intval(date('Y', $ctime) - date('Y', $time)); //是否跨年
	if ($t == 0) {
		$text = '刚刚';
	} elseif ($t < 60) { //一分钟内
		$text = $t . '秒前';
	} elseif ($t < 3600) { //一小时内
		$text = floor($t / 60) . '分钟前';
	} elseif ($t < 86400) { //一天内
		$text = floor($t / 3600) . '小时前'; // 一天内
	} elseif ($t < 2592000) { //30天内
		if ($time > strtotime(date('Ymd', strtotime("-1 day")))) {
			$text = '昨天';
		} elseif ($time > strtotime(date('Ymd', strtotime("-2 days")))) {
			$text = '前天';
		} else {
			$text = floor($t / 86400) . '天前';
		}
	} elseif ($t < 31536000 && $y == 0) { //一年内 不跨年
		$m = date('m', $ctime) - date('m', $time) - 1;

		if ($m == 0) {
			$text = floor($t / 86400) . '天前';
		} else {
			$text = $m . '月前';
		}
	} elseif ($t < 31536000 && $y > 0) { //一年内 跨年
		$text = (12 - date('m', $time) + date('m', $ctime)) . '月前';
	} else {
		$text = (date('Y', $ctime) - date('Y', $time)) . '年前';
	}

	return $text;
}

//评论者链接重定向
add_filter('get_comment_author_link', 'add_redirect_comment_link', 5);
add_filter('comment_text', 'add_redirect_comment_link', 99);
function add_redirect_comment_link($text = '')
{
	return go_link($text);
}

function go_link($text = '', $link = false)
{
	if (!$text || !_pz('go_link_s')) {
		return $text;
	}
	if ($link) {
		if (strpos($text, '://') !== false && strpos($text, home_url()) === false && !preg_match('/\.(jpg|jepg|png|ico|bmp|gif|tiff)/i', $text)) {
			$text = ZIB_STYLESHEET_DIRECTORY_URI . "/go.php?url=" . base64_encode($text);
		}
		return esc_url($text);
	}
	preg_match_all("/<a(.*?)href='(.*?)'(.*?)>/", $text, $matches);
	if ($matches) {
		foreach ($matches[2] as $val) {
			if (strpos($val, '://') !== false && strpos($val, home_url()) === false && !preg_match('/\.(jpg|jepg|png|ico|bmp|gif|tiff)/i', $val)) {
				$text = str_replace("href=\"$val\"", "href=\"" . esc_url(ZIB_STYLESHEET_DIRECTORY_URI . "/go.php?url=" . base64_encode($val)) . "\" ", $text);
			}
		}
		foreach ($matches[1] as $val) {
			$text = str_replace("<a" . $val, "<a" . $val . " target=\"_blank\" ", $text);
		}
	}
	return $text;
}

if (_pz('go_link_s') && _pz('go_link_post')) {
	add_filter('the_content', 'the_content_nofollow', 999);
	function the_content_nofollow($content)
	{
		preg_match_all('/<a(.*?)href="(.*?)"(.*?)>/', $content, $matches);
		if ($matches) {
			foreach ($matches[2] as $val) {
				if (strpos($val, '://') !== false && strpos($val, home_url()) === false && !preg_match('/\.(jpg|jepg|png|ico|bmp|gif|tiff)/i', $val)) {
					$content = str_replace("href=\"$val\"", "href=\"" . esc_url(ZIB_STYLESHEET_DIRECTORY_URI . "/go.php?url=" . base64_encode($val)) . "\" ", $content);
				}
			}
		}
		return $content;
	}
}

// 给分类连接添加SEO
function _get_tax_meta($id = 0, $field = '')
{
	$ops = get_option("_taxonomy_meta_$id");

	if (empty($ops)) {
		return '';
	}

	if (empty($field)) {
		return $ops;
	}

	return isset($ops[$field]) ? $ops[$field] : '';
}

//内容删除空格
function trimall($str)
{
	$limit = array(" ", "　", "\t", "\n", "\r");
	$rep = array("", "", "", "", "");
	return str_replace($limit, $rep, $str);
}

// 打赏按钮
function zib_get_rewards_button($user_ID, $class = 'ml6 but c-blue', $before = '', $after = '')
{
	$text = _pz('post_rewards_text', '赞赏');
	$before = $before ? $before : zib_svg('money');
	$weixin = get_user_meta($user_ID, 'rewards_wechat_image_id', true);
	$alipay = get_user_meta($user_ID, 'rewards_alipay_image_id', true);
	if (!$user_ID || !_pz('post_rewards_s') || (!$weixin && !$alipay)) return;
	return '<a href="javascript:;"  class="rewards ' . $class . '">' . $before . $text . $after . '</a>';
}

// 写文章、投稿按钮
function zib_get_write_posts_button($class = 'but b-theme', $text = '写文章', $before = '', $after = '')
{
	if (!_pz('post_article_s', true) || is_page_template('pages/newposts.php')) return;
	$class .= ' start-new-posts';
	$href = zib_get_template_page_url('pages/newposts.php');
	return '<a target="_blank" href="' . $href . '" class="' . $class . '">' . $before . $text . $after . '</a>';
}

//前台也可上传图片,用户权限修改
function allow_contributor_uploads()
{
	if (_pz('post_article_img_s')) {
		foreach (array('subscriber', 'editor', 'author', 'contributor') as $user_role) {
			$role = get_role($user_role);
			$role->add_cap('edit_published_posts');
			$role->add_cap('edit_published_pages');
			$role->add_cap('edit_others_posts');
			$role->add_cap('edit_others_pages');
			$role->add_cap('upload_files');
		}
	}
}
add_action('init', 'allow_contributor_uploads');

//在文章编辑页面的[添加媒体]只显示用户自己上传的文件
function zib_upload_media($wp_query_obj)
{
	global $current_user, $pagenow;
	if (!is_a($current_user, 'WP_User'))
		return;
	if ('admin-ajax.php' != $pagenow || $_REQUEST['action'] != 'query-attachments')
		return;
	if (!current_user_can('manage_options') && !current_user_can('manage_media_library'))
		$wp_query_obj->set('author', $current_user->ID);
	return;
}
add_action('pre_get_posts', 'zib_upload_media');

//在[媒体库]只显示用户上传的文件
function zib_media_library($wp_query)
{
	if (strpos($_SERVER['REQUEST_URI'], '/wp-admin/upload.php') !== false) {
		if (!current_user_can('manage_options') && !current_user_can('manage_media_library')) {
			global $current_user;
			$wp_query->set('author', $current_user->id);
		}
	}
}
add_filter('parse_query', 'zib_media_library');

//获取评论点赞按钮
function zib_get_comment_like($class = '', $pid = '', $text = '', $count = false, $before = '', $after = '')
{
	if (!_pz('comment_like_s') || !$pid) return;
	$like = _cut_count(get_comment_meta($pid, 'comment_like', true));
	$svg = zib_svg('like');
	$before = $before ? $before : $svg;
	if (zib_is_my_com_like($pid)) {
		$class .= ' actived';
	}
	if ($count) {
		return $like;
	}
	return '<a href="javascript:;" data-action="comment_like" class="' . $class . '" data-pid="' . $pid . '">' . $before . '<text>' . $text . '</text><count>' . ($like ? $like : 0) . '</count></a>';
}

//前台管理员编辑按钮
function zib_get_admin_edit($title = '编辑', $type = '', $class = 'admin-edit', $before = '', $after = '')
{
	$bef = $before ? $before : '<span class="' . $class . '" data-toggle="tooltip" title="' . $title . '">';
	$aft = $after ? $after : '</span>';
	$name = '[编辑]';
	if (!is_super_admin()) return;
	$link = edit_term_link($name, $bef, $aft, null, false);
	if ($type == 'posts') {
		$link = $bef . '<a href="' . get_edit_post_link() . '">' . $name . '</a>' . $aft;
	}
	if ($type == 'comment') {
		$link = edit_comment_link($name, $bef, $aft);
	}
	return $link;
}
//获取文章点赞按钮
function zib_get_post_like($class = '', $pid = '', $text = '点赞', $count = false, $before = '', $after = '')
{
	if (!_pz('post_like_s')) return;
	$pid = $pid ? $pid : get_the_ID();
	$like = _cut_count(get_post_meta($pid, 'like', true));
	$svg = zib_svg('like');
	$before = $before ? $before : $svg;
	if (zib_is_my_like($pid)) {
		$class .= ' actived';
	}

	if ($count) {
		return $like;
	}
	return '<a href="javascript:;" data-action="like" class="' . $class . '" data-pid="' . $pid . '">' . $before . '<text>' . $text . '</text><count>' . ($like ? $like : 0) . '</count></a>';
}

//获取用户关注按钮
function zib_get_user_follow($class = '', $follow_id = '', $text = '<i class="fa fa-heart-o mr6" aria-hidden="true"></i>关注', $ok_text = '<i class="fa fa-heart mr6" aria-hidden="true"></i>已关注', $before = '', $after = '')
{

	if (!$follow_id || get_current_user_id() == $follow_id || zib_is_close_sign()) return;
	if (zib_is_my_follow($follow_id)) {
		$class .= ' actived';
		$text = $ok_text;
	}

	$before = $before;
	$action = ' data-action="follow_user"';

	if (!is_user_logged_in()) {
		$action = '';
		$class .= ' signin-loader';
	}
	return '<a href="javascript:;"' . $action . ' class="' . $class . '" data-pid="' . $follow_id . '">' . $before . '<count>' . $text . '</count></a>';
}

//判断文章模式
function zib_is_docs_mode($pid = '', $cat_id = '')
{
	$d_cats = array();
	$docs_mode_cats = _pz('docs_mode_cats');

	if ($docs_mode_cats) {
		$d_cats = $docs_mode_cats;
	}
	if (!$d_cats) return false;
	/**分类页检测 */
	if (is_category() && !$cat_id) {
		$cat_id = get_queried_object_id();
	}
	if ($cat_id && in_array($cat_id, $d_cats)) return $cat_id;
	/**文章页检测 */
	if (is_single() && !$pid) {
		$pid = get_queried_object_id();
	}
	foreach ($d_cats as $c_id) {
		$posts = get_posts(array(
			'category' => $c_id,
			'numberposts' => -1,
		));
		foreach ($posts as $post) {
			if ($post->ID == $pid) return $c_id;
		}
	}
	return false;
}

//获取文章收藏
function zib_get_post_favorite($class = '', $pid = '', $text = '收藏', $count = false, $before = '', $after = '')
{

	if (zib_is_close_sign()) return;

	$pid = $pid ? $pid : get_the_ID();
	$favorite_count = get_post_meta($pid, 'favorite', true);
	$text = $text . '<count>' . ($favorite_count ? $favorite_count : 0) . '</count>';
	if (zib_is_my_favorite($pid)) {
		$class .= ' actived';
	}
	$svg = zib_svg('favorite');
	$before = $before ? $before : $svg;
	if ($count) {
		return $favorite_count;
	}
	$action = ' data-action="favorite"';
	if (!is_user_logged_in()) {
		$action = '';
		$class .= ' signin-loader';
	}
	return '<a href="javascript:;"' . $action . ' class="' . $class . '" data-pid="' . $pid . '">' . $before . '<text>' . $text . '</text></a>';
}

//判断是否关注
function zib_is_my_follow($pid = '')
{
	$current_id = get_current_user_id();
	if (!$current_id || !$pid) return false;
	$value = get_user_meta($current_id, 'follow-user', true);
	$value = $value ? maybe_unserialize($value)  : array();
	return in_array($pid, $value) ? true : false;
}

//判断是否品评论点赞
function zib_is_my_com_like($pid = '')
{
	$current_id = get_current_user_id();
	if (!$current_id) return false;
	$pid = $pid ? $pid : get_the_ID();
	$value = get_user_meta($current_id, 'comment-posts', true);
	$value = $value ? maybe_unserialize($value) : array();
	return in_array($pid, $value) ? true : false;
}
//判断是否文章点赞
function zib_is_my_like($pid = '')
{
	$current_id = get_current_user_id();
	if (!$current_id) return false;
	$pid = $pid ? $pid : get_the_ID();
	$value = get_user_meta($current_id, 'like-posts', true);
	$value = $value ? maybe_unserialize($value) : array();
	return in_array($pid, $value) ? true : false;
}
//判断是否收藏文章
function zib_is_my_favorite($pid = '')
{
	$current_id = get_current_user_id();
	if (!$current_id) return false;
	$pid = $pid ? $pid : get_the_ID();
	$value = get_user_meta($current_id, 'favorite-posts', true);
	$value = $value ? maybe_unserialize($value) : array();
	return in_array($pid, $value) ? true : false;
}

//作者粉丝数量
function get_user_meta_count($user_id, $mata)
{
	if (!$user_id && $mata) return;
	$val = get_user_meta($user_id, $mata, true);
	if ($val) {
		$val = count(maybe_unserialize($val));
	}
	return _cut_count($val);
}

//作者总获赞
function get_user_posts_meta_count($user_id, $mata)
{
	global $wpdb;
	$cache_num = wp_cache_get($user_id, 'user_posts_' . $mata . '_count', true);
	if (!$cache_num) {
		$num = $wpdb->get_var("SELECT sum(meta_value) FROM $wpdb->posts,$wpdb->postmeta WHERE $wpdb->posts.post_author = $user_id AND $wpdb->postmeta.post_id=$wpdb->posts.ID AND $wpdb->postmeta.meta_key='$mata' AND $wpdb->posts.post_status='publish'");
		wp_cache_set($user_id, $num, 'user_posts_' . $mata . '_count', 43200);
	} else {
		$num = $cache_num;
	}
	return $num ? _cut_count($num) : 0;
}

//作者评论数
function get_user_comment_count($user_id, $comments_status = 'approve', $cut = true)
{
	if (!$user_id) return;
	$args = array(
		'user_id' => $user_id,
		'status' => $comments_status,
		'count'   => true
	);
	$comments = get_comments($args);
	return $cut ? _cut_count($comments) : (int)$comments;
}

//作者签名
function get_user_desc($user_id)
{
	$des = get_user_meta($user_id, 'description', true);
	if (!$des) {
		$des = _pz('user_desc_std', '这家伙很懒，什么都没有写...');
	}
	return esc_attr($des);
}

// 获取分类封面图片
function zib_get_taxonomy_img_url($term_id = null, $size = 'full', $default = false)
{
	//return '';
	if (!$term_id) {
		$term_id = get_queried_object_id();
	}
	$img = '';
	$cache = wp_cache_get($term_id, 'taxonomy_image_' . $size, true);
	if (!$cache) {
		//待处理-换新cfs
		$img = get_option('_taxonomy_image_' . $term_id);
		if ($img) {
			if (!$size || $size != 'full') {
				$img_id = zib_get_image_id($img);
				if ($img_id) {
					$img = wp_get_attachment_image_src($img_id, $size);
					$img = $img[0];
				}
			}
			//缓存数据
			if ($img) {
				wp_cache_set($term_id, $img, 'taxonomy_image_' . $size);
			} else {
				//缓存1天
				wp_cache_set($term_id, 'noimg',  'taxonomy_image_' . $size);
			}
		}
	} elseif ($cache == 'noimg') {
		$img = '';
	} else {
		$img = $cache;
	}
	if ($default)
		return ($img != '') ? $img : $default;
	else
		return $img;
}

//作者封面图
function get_user_cover_img($user_id)
{
	$img = get_user_meta($user_id, 'cover_image', true);
	$default_img = _pz('user_cover_img', ZIB_STYLESHEET_DIRECTORY_URI . '/img/user_t.jpg');
	return $img ? $img : $default_img;
}

add_action('init', 'custom_button');
function custom_button()
{
	add_filter('mce_external_plugins', 'add_plugin');
	add_filter('mce_buttons', 'register_button');
}
//前端编辑器
function register_button($buttons)
{
	array_push($buttons, "precode", "qedit");
	return $buttons;
}
//添加按钮动作
function add_plugin($plugin_array)
{
	$plugin_array['precode'] = get_bloginfo('template_url') . '/js/precode.js';
	return $plugin_array;
}
//禁用古腾堡
if (_pz('close_gutenberg')) {
	add_filter('use_block_editor_for_post', '__return_false');
}
// 编辑器按钮
function _add_editor_buttons($buttons)
{
	return $buttons;
}
add_filter("mce_buttons", "_add_editor_buttons");

//添加隐藏内容，回复可见
function reply_to_read($atts, $content = null)
{
	$a = '#comments';
	extract(shortcode_atts(array("notice" => '<a class="hidden-text" href="javascript:(scrollTo(\'' . $a . '\',-120));"><i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;此处内容已隐藏，请评论后刷新页面查看.</a>'), $atts));
	$_hide = '<div class="hidden-box">' . $notice . '</div>';
	$_show = '<div class="hidden-box show"><div class="hidden-text">本文隐藏内容</div>' . do_shortcode($content) . '</div>';

	if (is_super_admin()) { //管理员登陆直接显示内容
		return '<div class="hidden-box show"><div class="hidden-text">本文隐藏内容 - 管理员可见</div>' . do_shortcode($content) . '</div>';
	} else {
		$email = null;
		$user_ID = (int) wp_get_current_user()->ID;
		if ($user_ID > 0) {
			$email = get_userdata($user_ID)->user_email;
		} else if (isset($_COOKIE['comment_author_email_' . COOKIEHASH])) {
			$email = str_replace('%40', '@', $_COOKIE['comment_author_email_' . COOKIEHASH]);
		} else {
			return $_hide;
		}
		if (empty($email)) {
			return  $_hide;
		}
		global $wpdb;
		$post_id = get_the_ID();
		$query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `comment_author_email`='{$email}' LIMIT 1";
		if ($wpdb->get_results($query)) {
			return $_show;
		} else {
			return $_hide;
		}
	}
}
add_shortcode('reply', 'reply_to_read');


/**文章短代码 */
function add_shortcode_postsbox($atts, $content = null)
{
	extract(shortcode_atts(array(
		'post_id' => '0'
	), $atts));
	$con = '';
	if ($post_id) {
		$post_id = (int)$post_id;
		$args = array(
			'post__in' => (array) $post_id,
			'ignore_sticky_posts' => 1
		);

		$the_query = new WP_Query($args);

		if ($the_query->have_posts()) {
			// 通过查询的结果，开始主循环
			while ($the_query->have_posts()) {
				global $post;
				$the_query->the_post();
				$_thumb = zib_post_thumbnail('', 'fit-cover radius8', true);
				$author = get_the_author();
				$title = get_the_title() . '<span class="focus-color">' . get_the_subtitle(false) . '</span>';
				$author = '<a href="' . get_author_posts_url(get_the_author_meta('ID')) . '">' . $author . '</a>';
				$time_ago = zib_get_time_ago(get_the_time('U'));
				$posts_meta = zib_get_posts_meta();
				/** 付费金额 */
				$posts_pay = get_post_meta($post->ID, 'posts_zibpay', true);
				$pay_mate = '';
				$order_type_class = '';
				$mark = _pz('pay_mark', '￥');

				if (!empty($posts_pay['pay_type']) && $posts_pay['pay_type'] != 'no') {
					$order_type_class = 'order-type-' . $posts_pay['pay_type'];
					//$order_type = zibpay_get_pay_type_name($posts_pay['pay_type']);
					$pay = $posts_pay['pay_price'] ? $mark  . $posts_pay['pay_price'] : '免费';
					$pay_mate = '<span class="badg badg-sm mr3 c-red">' . $pay . '</span>';
					$title = $pay_mate . $title;
				}

				$meta_l = '<item class="meta-author">' . $author . '<span class="icon-spot">' . $time_ago . '</span></item>';

				$con = '<div class="article-postsbox pay-box relative radius8 ' . $order_type_class . '">
						<div class="absolute postsbox-background"><img src="' . zib_default_thumb() . '" data-src="' . $_thumb . '" class="fit-cover radius8 lazyload"></div>
						<div class="absolute posts-item posts-mini radius8">
							<a class="item-thumbnail lazyload" data-bg="' . $_thumb . '" href="' . get_permalink() . '"></a>
							<div class="posts-mini-con">
								<div class="item-heading text-ellipsis-2 main-color">
									<a class="main-color" href="' . get_permalink() . '">' . $title . '</a>
								</div>
									<div class="item-meta muted-color">' . $meta_l . '
									<div class="meta-right pull-right">' . $posts_meta . '
									</div>
									</div>
							</div>
							</div>
						</div>';
			}
		}
	}
	if (!$con && is_super_admin()) {
		$con = '<div class="hidden-box"><div class="text-center">[postsbox post_id="' . $post_id . '"]</div><div class="hidden-text">未找到文章，请重新设置短代码文章ID</div></div>';
	}
	wp_reset_query();
	wp_reset_postdata();
	return $con;
}
add_shortcode('postsbox', 'add_shortcode_postsbox');

function add_shortcode_hidecontent($atts, $content = null)
{
	extract(shortcode_atts(array(
		'type' => 'reply',
		'is_logged' => ''
	), $atts));
	$content = rtrim(ltrim($content, "</span>"), "<span>");
	$user_id = get_current_user_id();
	$type_text = array(
		'reply' => '评论可见',
		'payshow' => '付费阅读',
		'logged' => '登录可见',
		'password' => '密码验证',
		'vip1' => _pz('pay_user_vip_1_name') . '可见',
		'vip2' => _pz('pay_user_vip_2_name') . '可见',
	);
	if (is_super_admin()) {   //管理员登陆直接显示内容
		return '<div class="hidden-box show"><div class="hidden-text">[' . $type_text[$type] . ']隐藏内容 - 管理员可见</div>' . do_shortcode($content) . '</div>';
	}
	if ($type == 'reply') {
		$a = '#comments';
		$_hide = '<div class="hidden-box"><a class="hidden-text" href="javascript:(scrollTo(\'' . $a . '\',-120));"><i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;此处内容已隐藏，请评论后刷新页面查看.</a></div>';
		$_show = '<div class="hidden-box show"><div class="hidden-text">本文隐藏内容</div>' . do_shortcode($content) . '</div>';

		global $wpdb, $post;
		$post_id = $post->ID;
		if ($user_id > 0) {  //当登陆时根据id查询数据库
			$query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `user_id`='{$user_id}' LIMIT 1";
		} elseif (isset($_COOKIE['comment_author_email_' . COOKIEHASH])) {
			//当未登陆时根据_COOKIE的email查询数据库
			$email = str_replace('%40', '@', $_COOKIE['comment_author_email_' . COOKIEHASH]);
			if (empty($email)) {
				return  $_hide;
			}
			$query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `comment_author_email`='{$email}' LIMIT 1";
		} else {
			return $_hide;
		}

		if ($wpdb->get_results($query)) {
			return $_show;
		} else {
			return $_hide;
		}
	} elseif ($type == 'payshow') {
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
		if ($paid) {
			$paid_name = zibpay_get_paid_type_name($paid['paid_type']);
			if ($pay_type == 'free' && _pz('pay_free_logged_show') && !$post_id) {
				return '<div class="hidden-box"><a class="hidden-text signin-loader" href="javascript:;"><i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;免费资源，请登录后查看</a></div>';
			} else {
				return '<div class="hidden-box show"><div class="hidden-text">本文付费阅读内容 - ' . $paid_name . '</div>' . do_shortcode($content) . '</div>';
			}
		} else {
			return  $_hide;
		}
	} elseif ($type == 'logged') {
		if ($user_id > 0) {
			return '<div class="hidden-box show"><div class="hidden-text">本文隐藏内容 - 登录可见</div>' . do_shortcode($content) . '</div>';
		} else {
			return '<div class="hidden-box"><a class="hidden-text signin-loader" href="javascript:;"><i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;隐藏内容，请登录后查看</a></div>';
		}
	} elseif ($type == 'vip1' || $type == 'vip2') {
		$vip_level = (int)zib_get_user_vip_level($user_id);
		if ($type == 'vip1') {
			$vip_l = 1;
		} else {
			$vip_l = 2;
		}
		if ($user_id > 0) {
			if (!$vip_level) {
				return '<div class="hidden-box"><a class="hidden-text pay-vip" vip-level="' . $vip_l . '" href="javascript:;"><i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;此处内容已隐藏，' . $type_text['vip' . $vip_l] . '</br><i class="fa fa-diamond"></i>&nbsp;&nbsp;请开通会员后查看</a></div>';
			} elseif ($vip_level < $vip_l) {
				return '<div class="hidden-box"><a class="hidden-text pay-vip" vip-level="' . $vip_l . '" href="javascript:;"><i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;此处内容已隐藏，' . $type_text['vip' . $vip_l] . '</br><i class="fa fa-diamond"></i>&nbsp;&nbsp;请升级会员后查看</a></div>';
			} else {
				return '<div class="hidden-box show"><div class="hidden-text">本文隐藏内容 - ' . $type_text['vip' . $vip_l] . '</div>' . do_shortcode($content) . '</div>';
			}
		} else {
			return '<div class="hidden-box"><a class="hidden-text signin-loader" href="javascript:;"><i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;此处内容已隐藏，' . $type_text['vip' . $vip_l] . '</br><i class="fa fa-sign-in"></i>&nbsp;&nbsp;请登录后查看特权</a></div>';
		}
	} elseif ($type == 'password') {
		$a = '#comments';
	}
}

add_shortcode('hidecontent', 'add_shortcode_hidecontent');
function zib_svg($name = '', $viewBox = '0 0 1024 1024', $class = "icon")
{
	if ($name) {
		return '<i data-class="' . $class . '" data-viewBox="' . $viewBox . '" data-svg="' . $name . '" aria-hidden="true"></i>';
	}
}

//函数调试代码-函数性能测试
function zib_microtime_float()
{
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}
function ZFuncTime($func, array $arr = NULL)
{
	//查询当前 timestamp 精确到 microseconds

	$time_taken = 0;
	$time_start = zib_microtime_float();

	if ($arr == NULL) {
		//Call a user function given by the first parameter
		call_user_func($func);
	} else {
		//Call a user function given with an array of parameters
		call_user_func_array($func, $arr);
	}

	$time_end = zib_microtime_float();
	$time_taken = $time_taken + ($time_end - $time_start);

	$log = array(
		'数据库查询' => get_num_queries(),
		'页面加载' => (timer_stop(0, 10) * 1000),
		'测试函数' => $func,
		'测试参数' => $arr,
		'执行时间' => ($time_taken * 1000),
	);

	$html = '<script type="text/javascript">';
	$html .= 'console.log(' . json_encode($log) . ')';
	$html .= '</script>';
	echo $html;
}
