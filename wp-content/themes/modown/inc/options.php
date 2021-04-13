<?php

/**
 * A unique identifier is defined to store the options in the database and reference them from the mobantu.com theme.
 */
function optionsframework_option_name() {
	//不要更改，否则主题无法使用
	return 'Modown';
}


/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'theme-textdomain'
 * with the actual text domain for your theme.  Read more:
 * http://codex.wordpress.org/Function_Reference/load_theme_textdomain
 */

function optionsframework_options() {

	// Multicheck Defaults
	$multicheck_defaults = array(
		'one' => '1',
		'five' => '1'
	);

	// Background Defaults
	$background_defaults = array(
		'color' => '',
		'image' => '',
		'repeat' => 'repeat',
		'position' => 'top center',
		'attachment'=>'scroll' );

	// Typography Defaults
	$typography_defaults = array(
		'face' => 'yahei',
		'style' => 'normal',
		'color' => '#383121' );
		
	$typography_content = array(
		'size' => '13px',
		'face' => 'yahei',
		'style' => 'normal',
		'color' => '#000000' );
		
	// Typography Options
	$typography_options = array(
		'sizes' => false
	);

	// Pull all the categories into an array
	$options_categories = array();
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name;
	}

	// Pull all the pages into an array
	$options_pages = array();
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	// $options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}


	$adsdesc =  '可添加广告联盟代码或自定义代码';
	$qrcode = get_stylesheet_directory_uri() . '/static/img/qrcode.png';
	$logo = get_stylesheet_directory_uri() . '/static/img/logo.png';
	$favicon = get_stylesheet_directory_uri() . '/static/img/favicon.ico';

//	$theme = optionsframework_option_name();
//	$ops = base64_decode(get_option('MBT_'.$theme.'_options'));
//    $token = get_option('MBT_'.$theme.'_token');
//    $ops = base64_decode( str_replace( md5($token.strtolower($theme)), '', $ops ) );
    $ops =
   array(
0 => array(
'name' => '基本',
'type' => 'heading',
),
1 => array(
'name' => 'Favicon图标',
'id' => 'favicon',
'desc' => '',
'std' => 'http://demo.mobantu.com/modown/wp-content/themes/modown/static/img/favicon.ico',
'type' => 'upload',
),
2 => array(
'name' => 'Logo',
'id' => 'logo',
'desc' => '建议尺寸：70*70px 建议格式：png',
'std' => 'http://demo.mobantu.com/modown/wp-content/themes/modown/static/img/logo.png',
'type' => 'upload',
),
3 => array(
'name' => 'Logo宽度',
'id' => 'logo_width',
'type' => 'text',
'std' => '70',
'desc' => '像素，请根据LOGO大小的比例来，高度是70，建议不要设置超过180',
),
4 => array(
'name' => 'Logo手机端宽度',
'id' => 'logo_width_wap',
'type' => 'text',
'std' => '60',
'desc' => '像素，请根据LOGO大小的比例来，高度是60，建议不要设置超过180',
),
5 => array(
'name' => '返回顶部/客服',
'id' => 'rollbar',
'type' => 'checkbox',
'std' => '',
'desc' => '显示',
),
6 => array(
'name' => '全屏切换',
'id' => 'fullscreen',
'type' => 'checkbox',
'std' => '',
'desc' => '显示',
),
7 => array(
'name' => '客服QQ链接',
'id' => 'kefu_qq',
'type' => 'text',
'std' => '',
'desc' => 'http://wpa.qq.com/msgrd?v=3&uin=82708210&site=qq&menu=yes',
),
8 => array(
'name' => '客服微信二维码',
'id' => 'kefu_weixin',
'desc' => '',
'std' => 'http://demo.mobantu.com/modown/wp-content/themes/modown/static/img/qrcode.png',
'type' => 'upload',
),
9 => array(
'name' => '前端防扒',
'id' => 'frontend_copy',
'type' => 'checkbox',
'std' => '',
'desc' => '开启',
),
10 => array(
'name' => '上传文件重命名',
'id' => 'file_rename',
'type' => 'checkbox',
'std' => '',
'desc' => '开启（该功能会针对上传的文件和图片重命名，如：09075549994.jpg）',
),
11 => array(
'name' => '投稿上传图片',
'id' => 'tougao_upload',
'type' => 'checkbox',
'std' => '',
'desc' => '开启（前端投稿页面支持上传图片）',
),
12 => array(
'name' => '无限下拉自动分页',
'id' => 'ajax_list_load',
'type' => 'checkbox',
'std' => '',
'desc' => '开启（页面下拉到一定位置自动加载下一页）',
),
13 => array(
'name' => '图片延迟加载',
'id' => 'lazyload',
'type' => 'checkbox',
'std' => '',
'desc' => '开启 （文章列表图片延迟加载，给网站提速）',
),
14 => array(
'name' => '瀑布流模式',
'id' => 'waterfall',
'type' => 'checkbox',
'std' => '',
'desc' => '开启 （不建议开启。如需开启也请同时开启 无限下拉自动分页，开启后就是图片高度不统一了哦～',
),
15 => array(
'name' => '缩略图自动剪切',
'id' => 'timthumb',
'type' => 'checkbox',
'std' => '',
'desc' => '开启（使用timthumb.php剪切，需要wp-content目录有可写入权限，会生成一个cache文件夹，若发现启用后无法显示图片，请检查wp-content/cache目录权限，可设置成777，不开启则使用系统自带的剪切，记得在 设置-媒体 里调整下缩略图大小的尺寸，系统自带的剪切仅支持上传设置了特色图片）',
),
16 => array(
'name' => '标题分割符',
'id' => 'delimiter',
'type' => 'text',
'std' => '-',
'desc' => '',
),
17 => array(
'name' => '标题分割符去空格',
'id' => 'delimiter_space',
'type' => 'checkbox',
'std' => '',
'desc' => '开启',
),
18 => array(
'name' => '博客类型名称',
'id' => 'blog_name',
'type' => 'text',
'std' => '博客',
'desc' => '例如：教程',
),
19 => array(
'name' => '博客介绍',
'id' => 'blog_desc',
'type' => 'text',
'std' => '这是模板兔发布的关于wordpress的技术教程',
'desc' => '例如：教程',
),
20 => array(
'name' => '公告',
'desc' => '支持html，如需a标签为按钮样式可给a标签加class为btn',
'id' => 'site_tips',
'std' => '',
'type' => 'textarea',
),
21 => array(
'name' => '页头',
'type' => 'heading',
),
22 => array(
'name' => '全宽',
'id' => 'header_fullwidth',
'type' => 'checkbox',
'std' => '',
'desc' => '开启（顶部LOGO菜单栏部分全宽）',
),
23 => array(
'name' => '背景样式',
'id' => 'header_type',
'desc' => '',
'options' => array(
'default' => '默认（首页与列表页会透明显示，其他页为白色背景）',
'light' => '白色背景',
'dark' => '黑色背景',
'custom' => '自定义背景色',
),
'std' => 'default',
'type' => 'radio',
),
24 => array(
'name' => '自定义背景色',
'id' => 'header_bgcolor',
'desc' => '',
'std' => '#ffffff',
'type' => 'color',
),
25 => array(
'name' => '导航文字颜色',
'id' => 'header_txtcolor',
'desc' => '',
'std' => '#062743',
'type' => 'color',
),
26 => array(
'name' => '默认文字颜色',
'id' => 'header_color',
'desc' => '仅适用于背景样式为默认、首页显示Banner，避免当Banner图与文字颜色相近导致导航看不清',
'std' => '#ffffff',
'type' => 'color',
),
27 => array(
'name' => 'VIP按钮',
'id' => 'header_vip',
'type' => 'checkbox',
'std' => '',
'desc' => '开启',
),
28 => array(
'name' => 'VIP手机端按钮',
'id' => 'header_vip_wap',
'type' => 'checkbox',
'std' => '',
'desc' => '隐藏',
),
29 => array(
'name' => '投稿按钮',
'id' => 'header_tougao',
'type' => 'checkbox',
'std' => '',
'desc' => '开启',
),
30 => array(
'name' => '页脚',
'type' => 'heading',
),
31 => array(
'name' => '友情链接',
'id' => 'friendlink',
'type' => 'checkbox',
'std' => '',
'desc' => '显示（后台左侧有个【链接】的菜单，进去添加友情链接，可分类）',
),
32 => array(
'name' => '底部小工具',
'id' => 'footer_widget',
'type' => 'checkbox',
'std' => '',
'desc' => '隐藏',
),
33 => array(
'name' => '小工具列数',
'id' => 'footer_widget_num',
'desc' => '建议选4或者5，太少的话请确保你每列内容够宽',
'options' => array(
5 => '5列',
4 => '4列',
3 => '3列',
2 => '2列',
),
'std' => '5',
'type' => 'radio',
),
34 => array(
'name' => '底部版权',
'desc' => '可加html标签',
'id' => 'copyright',
'std' => '',
'type' => 'textarea',
),
35 => array(
'name' => '网站统计代码',
'desc' => '位于底部，会被隐藏，但不影响统计效果',
'id' => 'analysis',
'std' => '',
'type' => 'textarea',
),
36 => array(
'name' => '自定义底部代码',
'desc' => '位于</body>之前，若是js代码需添加<script>标签',
'id' => 'js',
'std' => '',
'type' => 'textarea',
),
37 => array(
'name' => '首页',
'type' => 'heading',
),
38 => array(
'name' => '关键字(keywords)',
'id' => 'keywords',
'std' => 'mobantu,模板兔,wordpress主题定制',
'desc' => '关键字有利于SEO优化，建议个数在5-10之间，用英文逗号隔开',
'settings' => array(
'rows' => 4,
),
'type' => 'textarea',
),
39 => array(
'name' => '描述(description)',
'id' => 'description',
'std' => '模板兔，专业提供wordpress主题定制开发服务',
'desc' => '描述有利于SEO优化，建议字数在30-70之间',
'settings' => array(
'rows' => 4,
),
'type' => 'textarea',
),
40 => array(
'name' => 'Banner/幻灯片',
'id' => 'banner',
'options' => array(
0 => '无',
1 => 'Banner',
2 => '幻灯片',
),
'std' => '1',
'type' => 'select',
'desc' => '可选择显示为一个大Banner或者幻灯片',
),
41 => array(
'name' => '分类导航',
'id' => 'home_cat',
'type' => 'checkbox',
'std' => '',
'desc' => '显示（仅对默认首页有效，需设置分类菜单）',
),
42 => array(
'name' => '排除分类ID',
'id' => 'home_cats_exclude',
'type' => 'text',
'std' => '',
'desc' => '首页的最新文章里不显示这些分类的文章，多个用半角英文逗号隔开，例如：1,3,12。如何查看ID',
),
43 => array(
'name' => '博客',
'id' => 'home_blog',
'type' => 'checkbox',
'std' => '',
'desc' => '显示（首页底部显示博客类型文章）',
),
44 => array(
'name' => '博客个数',
'id' => 'home_blog_num',
'type' => 'text',
'std' => '6',
'desc' => '',
),
45 => array(
'name' => 'VIP',
'id' => 'home_vip',
'type' => 'checkbox',
'std' => '',
'desc' => '显示（首页底部显示VIP介绍）',
),
46 => array(
'name' => 'VIP标题',
'id' => 'home_vip_title',
'type' => 'text',
'std' => '关于VIP',
'desc' => '',
),
47 => array(
'name' => '优势介绍',
'id' => 'home_why',
'type' => 'checkbox',
'std' => '',
'desc' => '显示（首页底部显示我们的优势）',
),
48 => array(
'name' => '优势标题',
'id' => 'home_why_title',
'type' => 'text',
'std' => '我们的优势',
'desc' => '',
),
49 => array(
'name' => '优势简介',
'id' => 'home_why_desc',
'type' => 'text',
'std' => '资源丰富，专业人员筛选上传更新',
'desc' => '',
),
50 => array(
'name' => '优势一标题',
'id' => 'home_why_title1',
'type' => 'text',
'std' => '更新及时',
'desc' => '',
),
51 => array(
'name' => '优势一简介',
'id' => 'home_why_desc1',
'type' => 'text',
'std' => '专人上传，每天更新',
'desc' => '',
),
52 => array(
'name' => '优势一图标',
'id' => 'home_why_icon1',
'type' => 'text',
'std' => 'arrow-thin-up',
'desc' => '图标字体值请查看http://demo.amitjakhu.com/dripicons/',
),
53 => array(
'name' => '优势二标题',
'id' => 'home_why_title2',
'type' => 'text',
'std' => '精选资源',
'desc' => '',
),
54 => array(
'name' => '优势二简介',
'id' => 'home_why_desc2',
'type' => 'text',
'std' => '各类资源，满足需求',
'desc' => '',
),
55 => array(
'name' => '优势二图标',
'id' => 'home_why_icon2',
'type' => 'text',
'std' => 'archive',
'desc' => '',
),
56 => array(
'name' => '优势三标题',
'id' => 'home_why_title3',
'type' => 'text',
'std' => '高速下载',
'desc' => '',
),
57 => array(
'name' => '优势三简介',
'id' => 'home_why_desc3',
'type' => 'text',
'std' => '专属网盘，极速体验',
'desc' => '',
),
58 => array(
'name' => '优势三图标',
'id' => 'home_why_icon3',
'type' => 'text',
'std' => 'download',
'desc' => '',
),
59 => array(
'name' => '优势四标题',
'id' => 'home_why_title4',
'type' => 'text',
'std' => '7x24h服务',
'desc' => '',
),
60 => array(
'name' => '优势四简介',
'id' => 'home_why_desc4',
'type' => 'text',
'std' => '专人客服，随时联系',
'desc' => '',
),
61 => array(
'name' => '优势四图标',
'id' => 'home_why_icon4',
'type' => 'text',
'std' => 'time-reverse',
'desc' => '',
),
62 => array(
'name' => '统计数',
'id' => 'home_total',
'type' => 'checkbox',
'std' => '',
'desc' => '显示（首页底部显示资源与用户数）',
),
63 => array(
'name' => '实际数',
'id' => 'home_total_no',
'type' => 'checkbox',
'std' => '',
'desc' => '不加（不加，则下面填多少，首页就显示多少）',
),
64 => array(
'name' => '总资源数',
'id' => 'total_posts',
'type' => 'text',
'std' => '',
'desc' => '这里填的是一个基数，比如你填500，那么网站显示的是500加上你网站实际的文章数',
),
65 => array(
'name' => 'VIP资源数',
'id' => 'total_vip_posts',
'type' => 'text',
'std' => '',
'desc' => '这里填的是一个基数，比如你填500，那么网站显示的是500加上你网站实际的文章数',
),
66 => array(
'name' => '总用户数',
'id' => 'total_users',
'type' => 'text',
'std' => '',
'desc' => '这里填的是一个基数，比如你填500，那么网站显示的是500加上你网站实际的文章数',
),
67 => array(
'name' => 'VIP用户数',
'id' => 'total_vip_users',
'type' => 'text',
'std' => '',
'desc' => '这里填的是一个基数，比如你填500，那么网站显示的是500加上你网站实际的文章数',
),
68 => array(
'name' => 'Banner',
'type' => 'heading',
),
69 => array(
'name' => 'Banner背景灰度处理',
'id' => 'banner_dark',
'type' => 'checkbox',
'std' => '',
'desc' => '开启',
),
70 => array(
'name' => 'Banner背景图片',
'id' => 'banner_img',
'type' => 'upload',
'std' => '',
'desc' => '请上传一张高380px的图片，repeat-x模式',
),
71 => array(
'name' => 'Banner大标题',
'id' => 'banner_title',
'type' => 'text',
'std' => '模板兔',
'desc' => '',
),
72 => array(
'name' => 'Banner搜索',
'id' => 'banner_search',
'type' => 'checkbox',
'std' => '',
'desc' => '开启（开启搜索后不会显示描述与按钮）',
),
73 => array(
'name' => 'Banner搜索分类',
'id' => 'banner_cats',
'type' => 'text',
'std' => '',
'desc' => '分类ID列表，例如：1,3,8',
),
74 => array(
'name' => 'Banner小标题',
'id' => 'banner_desc',
'type' => 'text',
'std' => '国内最专业的wordpress建站仿站、二次开发、主题插件定制服务商！',
'desc' => '',
),
75 => array(
'name' => 'Banner按钮',
'id' => 'banner_btn',
'type' => 'text',
'std' => '查看详情',
'desc' => '',
),
76 => array(
'name' => 'Banner链接',
'id' => 'banner_link',
'type' => 'text',
'std' => 'http://www.mobantu.com',
'desc' => '',
),
77 => array(
'name' => '幻灯片',
'type' => 'heading',
),
78 => array(
'name' => '全屏模式',
'id' => 'slider_fullwidth',
'type' => 'checkbox',
'std' => '',
'desc' => '开启（图片宽度占整个屏幕）',
),
79 => array(
'name' => '幻灯片标题1',
'id' => 'slider_title1',
'type' => 'text',
),
80 => array(
'name' => '幻灯片副标题1',
'id' => 'slider_desc1',
'type' => 'text',
),
81 => array(
'name' => '幻灯片按钮文字1',
'id' => 'slider_btn1',
'type' => 'text',
),
82 => array(
'name' => '幻灯片链接1',
'id' => 'slider_link1',
'type' => 'text',
),
83 => array(
'name' => '幻灯片图片1',
'id' => 'slider_img1',
'desc' => '图片尺寸：1200*380',
'type' => 'upload',
),
84 => array(
'name' => '幻灯片标题2',
'id' => 'slider_title2',
'type' => 'text',
),
85 => array(
'name' => '幻灯片副标题2',
'id' => 'slider_desc2',
'type' => 'text',
),
86 => array(
'name' => '幻灯片按钮文字2',
'id' => 'slider_btn2',
'type' => 'text',
),
87 => array(
'name' => '幻灯片链接2',
'id' => 'slider_link2',
'type' => 'text',
),
88 => array(
'name' => '幻灯片图片2',
'id' => 'slider_img2',
'desc' => '图片尺寸：1200*380',
'type' => 'upload',
),
89 => array(
'name' => '幻灯片标题3',
'id' => 'slider_title3',
'type' => 'text',
),
90 => array(
'name' => '幻灯片副标题3',
'id' => 'slider_desc3',
'type' => 'text',
),
91 => array(
'name' => '幻灯片按钮文字3',
'id' => 'slider_btn3',
'type' => 'text',
),
92 => array(
'name' => '幻灯片链接3',
'id' => 'slider_link3',
'type' => 'text',
),
93 => array(
'name' => '幻灯片图片3',
'id' => 'slider_img3',
'desc' => '图片尺寸：1200*380',
'type' => 'upload',
),
94 => array(
'name' => '幻灯片标题4',
'id' => 'slider_title4',
'type' => 'text',
),
95 => array(
'name' => '幻灯片副标题4',
'id' => 'slider_desc4',
'type' => 'text',
),
96 => array(
'name' => '幻灯片按钮文字4',
'id' => 'slider_btn4',
'type' => 'text',
),
97 => array(
'name' => '幻灯片链接4',
'id' => 'slider_link4',
'type' => 'text',
),
98 => array(
'name' => '幻灯片图片4',
'id' => 'slider_img4',
'desc' => '图片尺寸：1200*380',
'type' => 'upload',
),
99 => array(
'name' => '幻灯片标题5',
'id' => 'slider_title5',
'type' => 'text',
),
100 => array(
'name' => '幻灯片副标题5',
'id' => 'slider_desc5',
'type' => 'text',
),
101 => array(
'name' => '幻灯片按钮文字5',
'id' => 'slider_btn5',
'type' => 'text',
),
102 => array(
'name' => '幻灯片链接5',
'id' => 'slider_link5',
'type' => 'text',
),
103 => array(
'name' => '幻灯片图片5',
'id' => 'slider_img5',
'desc' => '图片尺寸：1200*380',
'type' => 'upload',
),
104 => array(
'name' => '右侧栏',
'id' => 'slider_right',
'type' => 'checkbox',
'std' => '',
'desc' => '开启（开启后幻灯片图片尺寸建议为：895*380）',
),
105 => array(
'name' => '右侧栏链接1',
'id' => 'slider_right_link1',
'type' => 'text',
),
106 => array(
'name' => '右侧栏图片1',
'id' => 'slider_right_img1',
'desc' => '图片尺寸：285*180',
'type' => 'upload',
),
107 => array(
'name' => '右侧栏链接2',
'id' => 'slider_right_link2',
'type' => 'text',
),
108 => array(
'name' => '右侧栏图片2',
'id' => 'slider_right_img2',
'desc' => '图片尺寸：285*180',
'type' => 'upload',
),
109 => array(
'name' => 'VIP',
'type' => 'heading',
),
110 => array(
'name' => '全站隐藏VIP',
'id' => 'vip_hidden',
'type' => 'checkbox',
'std' => '',
'desc' => '隐藏（隐藏后，部分非设置性的地方将不会显示VIP功能）',
),
111 => array(
'name' => '直接支付升级',
'id' => 'vip_just_pay',
'type' => 'checkbox',
'std' => '',
'desc' => '开启（开启后，升级VIP当余额不足时会直接弹出在线支付）',
),
112 => array(
'name' => 'VIP介绍页面描述',
'id' => 'vip_desc',
'std' => '
升级VIP，享受更好的下载体验！

',
'settings' => array(
'rows' => 6,
),
'type' => 'textarea',
),
113 => array(
'name' => '体验VIP权限',
'id' => 'vip_day',
'std' => '
部分资源免费
部分资源折扣
',
'settings' => array(
'rows' => 6,
),
'type' => 'textarea',
),
114 => array(
'name' => '包月VIP权限',
'id' => 'vip_month',
'std' => '
部分资源免费
部分资源折扣
',
'settings' => array(
'rows' => 6,
),
'type' => 'textarea',
),
115 => array(
'name' => '包季VIP权限',
'id' => 'vip_quarter',
'std' => '
部分资源免费
部分资源折扣
',
'settings' => array(
'rows' => 6,
),
'type' => 'textarea',
),
116 => array(
'name' => '包年VIP权限',
'id' => 'vip_year',
'std' => '
部分资源免费
部分资源折扣
',
'settings' => array(
'rows' => 6,
),
'type' => 'textarea',
),
117 => array(
'name' => '终身VIP权限',
'id' => 'vip_life',
'std' => '
部分资源免费
部分资源折扣
',
'settings' => array(
'rows' => 6,
),
'type' => 'textarea',
),
118 => array(
'name' => '优势介绍',
'id' => 'vip_why',
'type' => 'checkbox',
'std' => '',
'desc' => '显示（内容在主题设置 - 首页里填写）',
),
119 => array(
'name' => '文章',
'type' => 'heading',
),
120 => array(
'name' => '列表页banner图片',
'id' => 'banner_archive_img',
'type' => 'upload',
'std' => '',
'desc' => '',
),
121 => array(
'name' => '筛选',
'id' => 'filter',
'type' => 'checkbox',
'std' => '',
'desc' => '开启',
),
122 => array(
'name' => '筛选搜索',
'id' => 'filter_search',
'type' => 'checkbox',
'std' => '',
'desc' => '开启',
),
123 => array(
'name' => '分类筛选',
'id' => 'filter_cat',
'type' => 'checkbox',
'std' => '',
'desc' => '开启',
),
124 => array(
'name' => '筛选分类IDs',
'desc' => '输入分类ID列表，多个用英文半角逗号隔开，例如：1,3,12。如何查看ID（此次的筛选用于 最新发布 页面模板，请输入一级分类IDs，如果分类下有二级子分类，会出现二级分类筛选，二级分类可筛选三级分类，最多三级。）',
'id' => 'filter_cats',
'std' => '',
'type' => 'text',
),
125 => array(
'name' => '标签筛选',
'id' => 'filter_tag',
'type' => 'checkbox',
'std' => '',
'desc' => '开启',
),
126 => array(
'name' => '自动标签',
'id' => 'filter_tag_auto',
'type' => 'checkbox',
'std' => '',
'desc' => '开启（分类页面自动获取分类下所有标签，开启后可能会影响分类页面加载速度）',
),
127 => array(
'name' => '最大自动标签数',
'id' => 'filter_tags_auto_count',
'std' => '20',
'type' => 'text',
),
128 => array(
'name' => '筛选标签IDs',
'desc' => '输入标签ID列表，多个用英文半角逗号隔开，例如：1,3,12。如何查看ID',
'id' => 'filter_tags',
'std' => '',
'type' => 'text',
),
129 => array(
'name' => '价格筛选',
'id' => 'filter_price',
'type' => 'checkbox',
'std' => '',
'desc' => '开启',
),
130 => array(
'name' => '排序筛选',
'id' => 'filter_order',
'type' => 'checkbox',
'std' => '',
'desc' => '开启',
),
131 => array(
'name' => '筛选标题一',
'desc' => '分类筛选自定义标题',
'id' => 'filter_cats_title1',
'std' => '分类',
'type' => 'text',
),
132 => array(
'name' => '筛选标题二',
'desc' => '二级分类筛选自定义标题',
'id' => 'filter_cats_title2',
'std' => '二级分类',
'type' => 'text',
),
133 => array(
'name' => '筛选标题三',
'desc' => '三级分类筛选自定义标题',
'id' => 'filter_cats_title3',
'std' => '三级分类',
'type' => 'text',
),
134 => array(
'name' => '筛选标题四',
'desc' => '标签筛选自定义标题',
'id' => 'filter_cats_title4',
'std' => '标签',
'type' => 'text',
),
135 => array(
'name' => '自定义分类法',
'desc' => '网站需要添加的所有分类法都首先需要在这里填写，填写后系统会自动创建分类法。名称,别名,筛选参数，多个用|隔开。例如：格式,format,fm|大小,size,sz',
'id' => 'post_taxonomy',
'std' => '',
'type' => 'text',
),
136 => array(
'name' => '自定义分类法筛选',
'id' => 'filter_taxonomy',
'type' => 'checkbox',
'std' => '',
'desc' => '开启（请先设置好自定义分类法，再开启）',
),
137 => array(
'name' => '列表项隐藏底部字段',
'id' => 'post_metas',
'desc' => '隐藏',
'std' => '',
'type' => 'checkbox',
),
138 => array(
'name' => '列表项标题显示两行',
'id' => 'post_title',
'desc' => '开启（默认显示一行）',
'std' => '',
'type' => 'checkbox',
),
139 => array(
'name' => '列表项显示分类',
'id' => 'post_cat',
'desc' => '显示',
'std' => '1',
'type' => 'checkbox',
),
140 => array(
'name' => '文章日期',
'id' => 'post_date',
'desc' => '显示',
'std' => '1',
'type' => 'checkbox',
),
141 => array(
'name' => '日期显示方式',
'id' => 'post_date_format',
'desc' => '',
'options' => array(
0 => 'xx前',
1 => 'Y-m-d',
),
'std' => '0',
'type' => 'radio',
),
142 => array(
'name' => '文章阅读数',
'id' => 'post_views',
'desc' => '显示',
'std' => '1',
'type' => 'checkbox',
),
143 => array(
'name' => '文章评论数',
'id' => 'post_comments',
'desc' => '显示',
'std' => '',
'type' => 'checkbox',
),
144 => array(
'name' => '文章下载数',
'id' => 'post_downloads',
'desc' => '开启',
'std' => '',
'type' => 'checkbox',
),
145 => array(
'name' => '下载框位置',
'id' => 'down_position',
'desc' => '',
'options' => array(
'side' => '边栏',
'top' => '内容上',
'bottom' => '内容下',
'sidetop' => '边栏与内容上',
'sidebottom' => '边栏与内容下',
),
'std' => 'side',
'type' => 'radio',
),
146 => array(
'name' => '文章标签',
'id' => 'post_tags',
'desc' => '开启',
'std' => '1',
'type' => 'checkbox',
),
147 => array(
'name' => '文章收藏',
'id' => 'post_collect',
'desc' => '开启',
'std' => '',
'type' => 'checkbox',
),
148 => array(
'name' => '文章点赞',
'id' => 'post_zan',
'desc' => '开启',
'std' => '1',
'type' => 'checkbox',
),
149 => array(
'name' => '分享',
'id' => 'post_share',
'desc' => '开启',
'std' => '1',
'type' => 'checkbox',
),
150 => array(
'name' => '分享卡片默认头图',
'id' => 'post_share_cover_img',
'type' => 'upload',
'std' => '',
'desc' => '',
),
151 => array(
'name' => '分享卡片默认LOGO',
'id' => 'post_share_cover_logo',
'type' => 'upload',
'std' => '',
'desc' => '',
),
152 => array(
'name' => '上一篇和下一篇文章',
'id' => 'post_nav',
'desc' => '开启',
'std' => '1',
'type' => 'checkbox',
),
153 => array(
'name' => '相关文章',
'id' => 'post_related',
'type' => 'checkbox',
'std' => '1',
'desc' => '开启',
),
154 => array(
'name' => '相关标题',
'desc' => '',
'id' => 'post_related_title',
'std' => '猜你喜欢',
'type' => 'text',
),
155 => array(
'name' => '相关数量',
'desc' => '显示相关文章的数量',
'id' => 'post_related_num',
'std' => '6',
'type' => 'text',
),
156 => array(
'name' => '相关限制',
'desc' => '默认分类限制',
'id' => 'post_related_in',
'options' => array(
'cat' => '分类',
'tag' => '标签',
'all' => '分类与标签',
),
'type' => 'select',
),
157 => array(
'name' => '价格显示',
'id' => 'post_price',
'type' => 'checkbox',
'std' => '',
'desc' => '隐藏（列表页item的价格）',
),
158 => array(
'name' => '评论显示已购买',
'id' => 'post_comment_bought',
'type' => 'checkbox',
'std' => '1',
'desc' => '开启',
),
159 => array(
'name' => '新窗口打开',
'id' => 'post_target',
'type' => 'checkbox',
'std' => '',
'desc' => '开启',
),
160 => array(
'name' => '用户中心',
'type' => 'heading',
),
161 => array(
'name' => '默认充值方式',
'id' => 'recharge_default',
'type' => 'checkbox',
'std' => '',
'desc' => '隐藏（隐藏后则不显示支付接口的充值方式）',
),
162 => array(
'name' => '充值固定金额',
'id' => 'recharge_price_s',
'type' => 'checkbox',
'std' => '',
'desc' => '开启',
),
163 => array(
'name' => '固定金额',
'id' => 'recharge_price',
'type' => 'text',
'std' => '1,5,10,50,100',
'desc' => '输入价格，多个用半角英文逗号隔开，例如：1,5,10,50,100',
),
164 => array(
'name' => '隐藏Paypy支付宝',
'id' => 'recharge_paypy_alipay',
'type' => 'checkbox',
'std' => '',
'desc' => '隐藏',
),
165 => array(
'name' => '充值说明',
'desc' => '用户中心在线充值按钮下面的说明，可加html标签',
'id' => 'user_charge_tips',
'std' => '',
'type' => 'textarea',
),
166 => array(
'name' => '每日签到',
'id' => 'checkin',
'type' => 'checkbox',
'std' => '',
'desc' => '开启',
),
167 => array(
'name' => '签到奖励',
'id' => 'checkin_gift',
'type' => 'text',
'std' => '0',
'desc' => '输入一个数字，赠送erphpdown插件的货币',
),
168 => array(
'name' => '提现',
'id' => 'withdraw',
'type' => 'checkbox',
'std' => '',
'desc' => '开启',
),
169 => array(
'name' => '工单',
'id' => 'ticket',
'type' => 'checkbox',
'std' => '',
'desc' => '开启（此功能为收费扩展功能，如需要请联系QQ82708210）',
),
170 => array(
'name' => '工单图片上传',
'id' => 'ticket_img',
'type' => 'checkbox',
'std' => '',
'desc' => '开启',
),
171 => array(
'name' => '登录',
'type' => 'heading',
),
172 => array(
'name' => '登录Logo',
'id' => 'logo_login',
'desc' => '建议尺寸：100*100px 格式：png',
'std' => 'http://demo.mobantu.com/modown/wp-content/themes/modown/static/img/logo.png',
'type' => 'upload',
),
173 => array(
'name' => '注册',
'id' => 'register',
'type' => 'checkbox',
'std' => '',
'desc' => '不开启',
),
174 => array(
'name' => '注册验证码',
'id' => 'captcha',
'desc' => '',
'options' => array(
'none' => '无',
'image' => '图形验证码',
'email' => '邮件验证码',
),
'std' => 'none',
'type' => 'radio',
),
175 => array(
'name' => 'QQ登录',
'id' => 'oauth_qq',
'type' => 'checkbox',
'std' => '',
'desc' => '开启（接口申请：https://connect.qq.com）',
),
176 => array(
'name' => 'qq id',
'id' => 'oauth_qqid',
'type' => 'text',
'std' => '',
'desc' => '',
),
177 => array(
'name' => 'qq key',
'id' => 'oauth_qqkey',
'type' => 'text',
'std' => '',
'desc' => '',
),
178 => array(
'name' => '微博登录',
'id' => 'oauth_weibo',
'type' => 'checkbox',
'std' => '',
'desc' => '开启（接口申请：https://open.weibo.com）',
),
179 => array(
'name' => 'weibo id',
'id' => 'oauth_weiboid',
'type' => 'text',
'std' => '',
'desc' => '',
),
180 => array(
'name' => 'weibo key',
'id' => 'oauth_weibokey',
'type' => 'text',
'std' => '',
'desc' => '',
),
181 => array(
'name' => '微信登录（开发平台）',
'id' => 'oauth_weixin',
'type' => 'checkbox',
'std' => '',
'desc' => '开启（接口申请：https://open.weixin.qq.com）',
),
182 => array(
'name' => 'weixin id',
'id' => 'oauth_weixinid',
'type' => 'text',
'std' => '',
'desc' => '',
),
183 => array(
'name' => 'weixin srcret',
'id' => 'oauth_weixinkey',
'type' => 'text',
'std' => '',
'desc' => '',
),
184 => array(
'name' => '样式',
'type' => 'heading',
),
185 => array(
'name' => '主题风格',
'desc' => '14种颜色供选择，点击选择你喜欢的颜色，保存后前端展示会有所改变。',
'id' => 'theme_color',
'std' => '#ff5f33',
'type' => 'colorradio',
'options' => array(
'#1d1d1d' => 1,
'#2CDB87' => 2,
'#00D6AC' => 3,
'#ff5f33' => 4,
'#EA84FF' => 5,
'#FDAC5F' => 6,
'#FD77B2' => 7,
'#76BDFF' => 8,
'#C38CFF' => 9,
'#FF926F' => 10,
'#8AC78F' => 11,
'#C7C183' => 12,
'#1E88E5' => 13,
'#6454ef' => 14,
),
),
186 => array(
'id' => 'theme_color_custom',
'std' => '',
'desc' => '不喜欢上面提供的颜色，你好可以在这里自定义设置，如果不用自定义颜色清空即可（默认不用自定义）',
'type' => 'color',
),
187 => array(
'name' => '文章列表列数',
'id' => 'list_column',
'desc' => '最大可显示的列数（仅对网格Grid显示样式生效）',
'options' => array(
'four' => '4列',
'five' => '5列',
'six' => '6列',
),
'std' => 'four',
'type' => 'radio',
),
188 => array(
'name' => '文章列表显示样式',
'id' => 'list_style',
'desc' => '默认文章聚合页的显示样式，若标签分类页单独设置了样式，则以单独设置为准',
'options' => array(
'grid' => '网格Grid',
'list' => '列表List',
),
'std' => 'grid',
'type' => 'radio',
),
189 => array(
'name' => '缩略图图片高度',
'desc' => '文章网格Grid的图片高度，单位px，输入一个数字即可，默认180，如果是正方形，请填285',
'id' => 'timthumb_height',
'std' => '',
'type' => 'text',
),
190 => array(
'name' => '自定义CSS样式',
'desc' => '位于</head>之前，直接写样式代码，不用添加<style>标签',
'id' => 'css',
'std' => '',
'type' => 'textarea',
),
191 => array(
'name' => '广告位',
'type' => 'heading',
),
192 => array(
'name' => '首页banner下',
'id' => 'ad_banner_footer_s',
'std' => '',
'desc' => '开启',
'type' => 'checkbox',
),
193 => array(
'desc' => '可添加广告联盟代码或自定义代码',
'id' => 'ad_banner_footer',
'std' => '',
'settings' => array(
'rows' => 6,
),
'type' => 'textarea',
),
194 => array(
'name' => '首页下',
'id' => 'ad_home_footer_s',
'std' => '',
'desc' => '开启',
'type' => 'checkbox',
),
195 => array(
'desc' => '可添加广告联盟代码或自定义代码',
'id' => 'ad_home_footer',
'std' => '',
'settings' => array(
'rows' => 6,
),
'type' => 'textarea',
),
196 => array(
'name' => '列表上',
'id' => 'ad_list_header_s',
'std' => '',
'desc' => '开启',
'type' => 'checkbox',
),
197 => array(
'desc' => '可添加广告联盟代码或自定义代码',
'id' => 'ad_list_header',
'std' => '',
'settings' => array(
'rows' => 6,
),
'type' => 'textarea',
),
198 => array(
'name' => '列表下',
'id' => 'ad_list_footer_s',
'std' => '',
'desc' => '开启',
'type' => 'checkbox',
),
199 => array(
'desc' => '可添加广告联盟代码或自定义代码',
'id' => 'ad_list_footer',
'std' => '',
'settings' => array(
'rows' => 6,
),
'type' => 'textarea',
),
200 => array(
'name' => '文章内容上',
'id' => 'ad_post_header_s',
'std' => '',
'desc' => '开启',
'type' => 'checkbox',
),
201 => array(
'desc' => '可添加广告联盟代码或自定义代码',
'id' => 'ad_post_header',
'std' => '',
'settings' => array(
'rows' => 6,
),
'type' => 'textarea',
),
202 => array(
'name' => '文章内容下',
'id' => 'ad_post_footer_s',
'std' => '',
'desc' => '开启',
'type' => 'checkbox',
),
203 => array(
'desc' => '可添加广告联盟代码或自定义代码',
'id' => 'ad_post_footer',
'std' => '',
'settings' => array(
'rows' => 6,
),
'type' => 'textarea',
),
204 => array(
'name' => '文章评论下',
'id' => 'ad_post_comment_s',
'std' => '',
'desc' => '开启',
'type' => 'checkbox',
),
205 => array(
'desc' => '可添加广告联盟代码或自定义代码',
'id' => 'ad_post_comment',
'std' => '',
'settings' => array(
'rows' => 6,
),
'type' => 'textarea',
),
206 => array(
'name' => '下载页面',
'id' => 'ad_erphpdown_s',
'std' => '',
'desc' => '开启',
'type' => 'checkbox',
),
207 => array(
'desc' => '可添加广告联盟代码或自定义代码',
'id' => 'ad_erphpdown',
'std' => '',
'settings' => array(
'rows' => 6,
),
'type' => 'textarea',
),
208 => array(
'name' => '使用教程',
'type' => 'heading',
),
209 => array(
'name' => '作者',
'type' => 'info',
'desc' => '模板兔',
),
210 => array(
'name' => '升级地址',
'type' => 'info',
'desc' => 'http://www.mobantu.com/7191.html
如果你使用的是盗版主题，数据可能会面临被清空的风险，一旦被清空，我们概不负责。',
),
211 => array(
'name' => '教程地址',
'type' => 'info',
'desc' => 'http://www.mobantu.com/7472.html',
),
212 => array(
'name' => '关于作者',
'type' => 'info',
'desc' => '专注WordPress开发7年，提供WordPress建站仿站、二次开发、主题插件定制等服务。',
),
213 => array(
'name' => '联系QQ',
'type' => 'info',
'desc' => '82708210（请直接说明来意，勿问在否，售后请到模板兔网站提交工单。）',
),
);
    return $ops;
}