<?php
ob_start();
/* 搜库资源网 soku.cc*
 * ziranzhi2 functions and definitions
 *
 * @link https:// 搜库资源网 soku.cc   developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ziranzhi2
 */

// 搜库资源网 soku.cc   delete_post_meta(31369,'zrz_buy_user');

 /* 搜库资源网 soku.cc
 * 路径
 */
 define( 'ZRZ_THEME_DIR', get_template_directory() );
 define( 'ZRZ_INCLUDES_PATH', get_template_directory() . '/includes/' );
 define( 'ZRZ_THEME_URI', get_template_directory_uri() );
 define( 'ZRZ_THEME_DOT', '<span class="dot"></span>');
 define('ZRZ_VERSION',zrz_get_theme_version());

/* 搜库资源网 soku.cc主题启用时数据初始化*/
 function zrz_message_install_callback(){
     global $wpdb;

     // 搜库资源网 soku.cc   新建消息表
     $table_name = $wpdb->prefix . 'zrz_message';
     if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) :
 		$sql = " CREATE TABLE `$table_name` (
            `msg_id` bigint(20) NOT NULL AUTO_INCREMENT,
            PRIMARY KEY(msg_id),
            `user_id` int,
            `msg_type` int,
            `msg_read` int,
            `msg_date` datetime,
            `msg_users` longtext,
            `msg_credit` int,
            `msg_credit_total` int,
            `msg_key` text,
            `msg_value` longtext
 		) CHARSET=utf8;";
 			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
 			dbDelta($sql);
     endif;

     // 搜库资源网 soku.cc   新建订单管理表
     $shop_table_name = $wpdb->prefix . 'zrz_order';
     if( $wpdb->get_var("SHOW TABLES LIKE '$shop_table_name'") != $shop_table_name ) :
       $sql = " CREATE TABLE `$shop_table_name` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            PRIMARY KEY(id),
            `order_id` text,
            `user_id` int,
            `post_id` int,
            `order_type` text,
            `order_commodity` int,
            `order_state` text,
            `order_date` datetime,
            `order_count` int,
            `order_price` float,
            `order_key` text,
            `order_value` longtext
       ) CHARSET=utf8;";
           require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
           dbDelta($sql);
     endif;

     // 搜库资源网 soku.cc   新建卡密表
     $card_table_name = $wpdb->prefix . 'zrz_card';
     if( $wpdb->get_var("SHOW TABLES LIKE '$card_table_name'") != $card_table_name ) :
       $sql = " CREATE TABLE `$card_table_name` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            PRIMARY KEY(id),
            `card_key` text,
            `card_value` text,
            `card_rmb` int,
            `card_status` int,
            `card_user` int
       ) CHARSET=utf8;";
           require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
           dbDelta($sql);
     endif;

     $invitation_table_name = $wpdb->prefix . 'zrz_invitation';
     if( $wpdb->get_var("SHOW TABLES LIKE '$invitation_table_name'") != $invitation_table_name ) :
       $sql = " CREATE TABLE `$invitation_table_name` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            PRIMARY KEY(id),
            `invitation_nub` text,
            `invitation_owner` int,
            `invitation_credit` int,
            `invitation_status` int,
            `invitation_user` int
       ) CHARSET=utf8;";
           require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
           dbDelta($sql);
     endif;

    // 搜库资源网 soku.cc   更新订单数据库
    $row = $wpdb->get_col("DESC $shop_table_name", 0);
    if($row[3] != 'post_id'){
        $wpdb->query("ALTER TABLE $shop_table_name ADD COLUMN `post_id` INT(11) NULL AFTER `user_id`");
    }
    if(!isset($row[12])){
        $wpdb->query("ALTER TABLE $shop_table_name ADD COLUMN `order_content` longtext NULL AFTER `order_value`");
    }
 }

 function zrz_message_install(){
     global $pagenow;
     if(has_filter('upload_dir', 'zrz_upload_dir',100,1)) remove_filter('upload_dir', 'zrz_upload_dir',100,1);
     if ( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) ){
         zrz_message_install_callback();
         $dirname  = wp_upload_dir();

         // 搜库资源网 soku.cc   生成头像目录
         $dirname = $dirname['basedir'].'/avatar';
         if(!is_dir($dirname)){
            wp_mkdir_p( $dirname );
         }

         // 搜库资源网 soku.cc   创建研究所分类
         $labs = array(
             'isaid'=>array(
                 'name'=>__('我说','7b2'),
                 'des'=>__('简单说一说你的看法','7b2'),
             ),
             'youguess'=>array(
                 'name'=>__('你猜','7b2'),
                 'des'=>__('猜一猜正确答案，满足你的好奇心','7b2')
             ),
             'vote'=>array(
                 'name'=>__('投票','7b2'),
                 'des'=>__('看似这是大部分人的选择，其实未必','7b2')
             ),
             'relay'=>array(
                 'name'=>__('接力','7b2'),
                 'des'=>__('这些神奇的事情或许你也经历过','7b2')
             ),
         );
         foreach ($labs as $key=>$val) {
             $term = term_exists($key, 'labtype' );
             if(!$term){
                 wp_insert_term(
                   $val['name'],
                   'labtype',
                   array(
                     'description'=> $val['des'],
                     'slug' => $key
                   )
                 );
             }
         }

         // 搜库资源网 soku.cc   注册与创建菜单
         $top_menu = array(
     		'name'=>__('顶部菜单-柒比贰','7b2'),
     		'menus'=>array(
                 array(
                     'name'=>__('专题中心','7b2'),
                     'url'=>esc_url(home_url('/collections'))
                 ),
                 array(
                     'name'=>__('研究所','7b2'),
                     'url'=>esc_url(home_url('/labs'))
                 ),
                 array(
                     'name'=>__('冒泡','7b2'),
                     'url'=>esc_url(home_url('/bubble'))
                 ),
                 array(
                    'name'=>__('活动','7b2'),
                    'url'=>esc_url(home_url('/activity'))
                ),
                 array(
                    'name'=>__('话题','7b2'),
                    'url'=>esc_url(home_url('/forums'))
                ),
                 array(
                     'name'=>__('商城','7b2'),
                     'url'=>esc_url(home_url('/shop'))
                 ),
                 array(
                     'name'=>__('导航','7b2'),
                     'url'=>esc_url(home_url('/links'))
                 ),
             )
     	);
     	$footer_menu = array(
     		'name'=>__('底部菜单-柒比贰','7b2'),
         );
        $top_gonggao_menu = array(
            'name'=>__('顶部公告条菜单-柒比贰','7b2'),
        );
        $top_luntan_menu = array(
            'name'=>__('论坛菜单-柒比贰','7b2'),
        );
        $top_bubble_menu = array(
            'name'=>__('冒泡菜单-柒比贰','7b2'),
        );
        $top_cat_menu = array(
            'name'=>__('首页分类菜单-柒比贰','7b2'),
        );

     	$menus = array($top_menu,$footer_menu,$top_gonggao_menu,$top_luntan_menu,$top_bubble_menu,$top_cat_menu);
     	foreach ($menus as $menu) {
     		$menu_exists = wp_get_nav_menu_object( $menu['name'] );
     		if(!$menu_exists){
     			$menu_id = wp_create_nav_menu($menu['name']);
     			if(isset($menu['url']) && !empty($menu['url'])){
     				wp_update_nav_menu_item($menu_id, 0, array(
     					'menu-item-title' => $menu['menu-name'],
     					'menu-item-url' => esc_url($menu['url']),
     					'menu-item-status' => 'publish')
     				);
     			}
                 if(isset($menu['menus'])){
                     foreach ($menu['menus'] as $val) {
                         wp_update_nav_menu_item($menu_id, 0, array(
         					'menu-item-title' => $val['name'],
         					'menu-item-url' => esc_url($val['url']),
         					'menu-item-status' => 'publish')
         				);
                     }
                 }
     		}
     	}
    }
 }
 add_action( 'load-themes.php', 'zrz_message_install' );

/* 搜库资源网 soku.cc
* 获取主题版本
*/
function zrz_get_theme_version(){
    $theme = wp_get_theme();
    return $theme->get( 'Version' );
}

function zrz_custom_page(){
    return array(
        'directmessage'=>__('私信','ziranzhi2'),
        'gold'=>__('财富明细','ziranzhi2'),
        'hot'=>__('活跃用户','ziranzhi2'),
        'newtopic'=>__('发起话题','ziranzhi2'),
        'notifications'=>__('消息','ziranzhi2'),
        'pass'=>__('完善注册资料','ziranzhi2'),
        'write'=>__('写文章','ziranzhi2'),
        'signin'=>__('登录','ziranzhi2'),
        'signup'=>__('注册','ziranzhi2'),
        'sticky'=>__('私信','ziranzhi2'),
        'top'=>__('财富排行','ziranzhi2'),
        'maopao'=>__('冒泡','ziranzhi2'),
        'links'=>__('导航链接','ziranzhi2'),
        'add-labs'=>__('发布研究','ziranzhi2'),
        'collections'=>__('专题中心','ziranzhi2'),
        'pay'=>__('支付','ziranzhi2'),
        'cart'=>__('我的购物车','ziranzhi2'),
        'notify-pay'=>__('支付结果','ziranzhi2'),
        'return-pay'=>__('支付结果','ziranzhi2'),
        'new-topic'=>__('发起话题','ziranzhi2'),
        'open'=>__('社交登录','ziranzhi2'),
        'gold'=>__('财富明细','ziranzhi2'),
        'wxpay'=>__('微信支付','ziranzhi2'),
        'weixinpay-notify'=>__('微信支付回调','ziranzhi2'),
        'vips'=>__('成为VIP会员','ziranzhi2'),
        'announcements'=>__('公告','ziranzhi2'),
        'xunhu-success'=>__('支付成功','ziranzhi2'),
        'xunhu-error'=>__('支付失败','ziranzhi2'),
        'withdraw'=>__('提现管理','ziranzhi2'),
        'task'=>__('我的任务','ziranzhi2'),
        'callback-payjs'=>__('支付确认中...','ziranzhi2')
    );
}

function ziranzhi2_setup() {

	// 搜库资源网 soku.cc   load_theme_textdomain( '7b2', ZRZ_THEME_DIR . '/languages' );

    // 搜库资源网 soku.cc   支持友情链接
	add_theme_support( 'automatic-feed-links' );

    // 搜库资源网 soku.cc   支持标签
	add_theme_support( 'title-tag' );

    // 搜库资源网 soku.cc   支持缩略图
	add_theme_support( 'post-thumbnails' );

    add_theme_support( 'customize-selective-refresh-widgets' );
    
    add_theme_support( 'post-formats', array( 'image', 'status' ) );

    register_nav_menus( array(
		'header-menu' => esc_html__( '顶部菜单', 'ziranzhi' ),
        'footer-menu' => esc_html__( '底部菜单', 'ziranzhi' ),
        'top-banner-menu' => esc_html__( '顶部公告栏右侧菜单', 'ziranzhi' ),
        'home-post-menu'=>esc_html__( '文章菜单', 'ziranzhi' ),
        'bbs-top-menu'=>esc_html__( '论坛菜单', 'ziranzhi' ),
        'bubble-top-menu'=>esc_html__( '冒泡菜单', 'ziranzhi' )
	) );
}
add_action( 'after_setup_theme', 'ziranzhi2_setup' );

add_action('admin_init', 'zrz_plugin_init', 10);
function zrz_plugin_init(){
    if(is_admin()){
        $bbpress_opt = get_option('_bbp_show_on_root');
        if($bbpress_opt != 'topics'){
            update_option('_bbp_show_on_root','topics');
        }
    
        // 搜库资源网 soku.cc   设置注册用户权限
        $user_role = get_option('default_role');
        if($user_role != 'contributor'){
            update_option('default_role','contributor');
        }
    
        // 搜库资源网 soku.cc   禁止普通用户进入后台
        if ( !current_user_can( 'manage_options' ) && !current_user_can( 'editor' ) && $_SERVER['PHP_SELF'] != '/wp-admin/admin-ajax.php' ) {
            wp_redirect( '/' );
        }
    }
}

function zrz_add_has_children_to_nav_items( $items ){
    $parents = wp_list_pluck( $items, 'menu_item_parent');
    foreach ( $items as $item ){
        $item->title = '<span>'.$item->title.'</span>';
        in_array( $item->ID, $parents ) && $item->title = $item->title.'<i class="iconfont zrz-icon-font-zelvxiangxiadanjiantouzhankai"></i>';
    }
    return $items;
}
add_filter( 'wp_nav_menu_objects', 'zrz_add_has_children_to_nav_items' );

/* 搜库资源网 soku.cc*
 * Register widget area.
 *
 * @link https:// 搜库资源网 soku.cc   developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function ziranzhi2_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( '首页和存档页面', '7b2' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( '显示位置：首页，存档，分类，专题存档页面', '7b2' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s mar16-b">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title l1 box-header">',
		'after_title'   => '</h2>',
	) );
    register_sidebar( array(
		'name'          => esc_html__( '文章内页', '7b2' ),
		'id'            => 'sidebar-2',
		'description'   => esc_html__( '显示位置：文章内页，页面', '7b2' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s mar16-b">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title l1 box-header">',
		'after_title'   => '</h2>',
	) );
    register_sidebar( array(
        'name'          => esc_html__( '商城页面', '7b2' ),
        'id'            => 'sidebar-3',
        'description'   => esc_html__( '显示位置：商城页面', '7b2' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s mar16-b">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title l1 box-header">',
        'after_title'   => '</h2>',
    ) );
    if(class_exists( 'bbPress' )){
        register_sidebar( array(
            'name'          => esc_html__( '论坛页面', '7b2' ),
            'id'            => 'sidebar-4',
            'description'   => esc_html__( '显示位置：论坛首页，论坛内页', '7b2' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s mar16-b">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title l1 box-header">',
            'after_title'   => '</h2>',
        ) );
    }
    register_sidebar( array(
        'name'          => esc_html__( '冒泡页面', '7b2' ),
        'id'            => 'sidebar-5',
        'description'   => esc_html__( '显示位置：冒泡页面', '7b2' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s mar16-b">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title l1 box-header">',
        'after_title'   => '</h2>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( '个人主页', '7b2' ),
        'id'            => 'sidebar-6',
        'description'   => esc_html__( '显示位置：用户页面', '7b2' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s mar16-b">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title l1  box-header">',
        'after_title'   => '</h2>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( '自定义页面', '7b2' ),
        'id'            => 'sidebar-7',
        'description'   => esc_html__( '显示位置：所有自定义的页面', '7b2' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s mar16-b">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title l1 box-header">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'ziranzhi2_widgets_init' );

// 搜库资源网 soku.cc   支持友情链接
add_filter('pre_option_link_manager_enabled','__return_true');

// 搜库资源网 soku.cc   禁用默认插件的样式和js文件
add_action('wp_print_styles', 'zrz_dequeue_css_from_plugins', 100);
function zrz_dequeue_css_from_plugins() {
	wp_dequeue_style('yarppWidgetCss');
	wp_dequeue_style('yarppRelatedCss');
    wp_dequeue_style('bbp-default');
    wp_dequeue_style('smartideo_css');
}

add_action( 'wp_footer', 'zrz_deregister_footer_styles' );
function zrz_deregister_footer_styles() {
   wp_dequeue_style('yarppRelatedCss');
   wp_dequeue_script('smartideo_js');
}

/* 搜库资源网 soku.cc*
 * Enqueue scripts and styles.
*/
function ziranzhi2_scripts() {
    /* 搜库资源网 soku.cc不要担心加载这么多 js， 文件都很小，请放心使用*/

    // 搜库资源网 soku.cc   主题样式
    wp_enqueue_style( 'ziranzhi2-style', get_stylesheet_uri() , array() , ZRZ_VERSION, 'all');
    $home_style = '';
    if(is_home() && !zrz_wp_is_mobile()){
        $post_count = zrz_get_theme_settings('pinterest_count');
        $count_pr = $post_count ? 100/(int)zrz_get_theme_settings('pinterest_count') : 3;
        $width = sprintf("%.5f",$count_pr);
        $home_style = '.grid-item{width:'.$width.'%}';
        $show_sidebar = zrz_get_theme_settings('show_sidebar');
        if(!$show_sidebar){
            $home_style .= '.home .mobile-full-width.content-area{width:100%}';
        }
    }

    
    $page_width = '
            .site-branding,.header-top-in,.site-content,.site-info,.cat-header-in,.site-navigation-in{
                    width: '.zrz_get_theme_settings('page_width').'px;
            }'.$home_style;

    // 搜库资源网 soku.cc   主题样式
    wp_add_inline_style('ziranzhi2-style', $page_width);

    // 搜库资源网 soku.cc   手机端样式
    wp_enqueue_style( 'ziranzhi2-mobile-style', ZRZ_THEME_URI.'/mobile.css' , array() , ZRZ_VERSION, 'all');

    // 搜库资源网 soku.cc    // 搜库资源网 soku.cc   阿里fonticon 图标
    wp_enqueue_style( 'fonticon', '// 搜库资源网 soku.cc   at.alicdn.com/t/font_416760_4qh32v621kt.css',array(), null ,'all' );

    $highlight = zrz_get_reading_settings('highlight');

    $js_local = zrz_get_theme_settings('js_local');

    // 搜库资源网 soku.cc   如果使用的是远程JS库
    $js = array(
        'vue'=>'// 搜库资源网 soku.cc   cdn.bootcss.com/vue/2.4.4/vue.min.js',
        'es6-promise'=>'// 搜库资源网 soku.cc   cdn.bootcss.com/es6-promise/4.1.1/es6-promise.auto.min.js',
        'axios'=>'// 搜库资源网 soku.cc   cdn.bootcss.com/axios/0.16.2/axios.min.js',
        'qs'=>'// 搜库资源网 soku.cc   cdn.bootcss.com/qs/6.5.0/qs.min.js',
        'timeago'=>'// 搜库资源网 soku.cc   cdn.bootcss.com/timeago.js/3.0.2/timeago.min.js',
        'swipe'=>'// 搜库资源网 soku.cc   cdn.bootcss.com/flickity/2.1.0/flickity.pkgd.min.js',
        'countUp'=>'// 搜库资源网 soku.cc   cdn.bootcss.com/countup.js/1.9.1/countUp.min.js',
        'autosize'=>'// 搜库资源网 soku.cc   cdn.bootcss.com/autosize.js/4.0.0/autosize.min.js',
        'highlight-style'=>$highlight ? '// 搜库资源网 soku.cc   cdn.bootcss.com/highlight.js/9.12.0/styles/xcode.min.css' : '',
        'highlight'=>$highlight ? '// 搜库资源网 soku.cc   cdn.bootcss.com/highlight.js/9.12.0/highlight.min.js' : ''
    );

    // 搜库资源网 soku.cc   如果使用的是本地JS库
    if($js_local == 0){
        $js = array(
            'vue'=>ZRZ_THEME_URI.'/js/lib/vue.min.js',
            'es6-promise'=>ZRZ_THEME_URI.'/js/lib/es6-promise.auto.min.js',
            'axios'=>ZRZ_THEME_URI.'/js/lib/axios.min.js',
            'qs'=>ZRZ_THEME_URI.'/js/lib/qs.min.js',
            'timeago'=>ZRZ_THEME_URI.'/js/lib/timeago.min.js',
            'swipe'=>ZRZ_THEME_URI.'/js/lib/flickity.pkgd.min.js',
            'countUp'=>ZRZ_THEME_URI.'/js/lib/countUp.min.js',
            'autosize'=>ZRZ_THEME_URI.'/js/lib/autosize.min.js',
            'highlight-style'=>$highlight ? ZRZ_THEME_URI.'/js/lib/xcode.min.css' : '',
            'highlight'=>$highlight ? ZRZ_THEME_URI.'/js/lib/highlight.min.js' : ''
        );
    }

    foreach ($js as $key => $val) {
        if($key == 'highlight-style' && $val != ''){
            wp_enqueue_style( $key,$val , array() , null, 'all');
        }elseif($val != ''){
            wp_enqueue_script( $key, $val ,array(), null ,'all' );
        }
    }

    // 搜库资源网 soku.cc   动画效果
    // 搜库资源网 soku.cc    wp_enqueue_style( 'animate', '// 搜库资源网 soku.cc   cdn.bootcss.com/animate.css/3.5.2/animate.min.css',array(), null ,'all' );

    // 搜库资源网 soku.cc   vue路由
    // 搜库资源网 soku.cc   wp_enqueue_script( 'vue-router', '// 搜库资源网 soku.cc   cdn.bootcss.com/vue-router/2.7.0/vue-router.min.js' , array(), null , true );

    if(zrz_is_weixin()){
        // 搜库资源网 soku.cc   微信js sdk
        wp_enqueue_script( 'weixin', '// 搜库资源网 soku.cc   res.wx.qq.com/open/js/jweixin-1.2.0.js' , array(),NULL , true );
    }

    // 搜库资源网 soku.cc   跟随滚动
	wp_enqueue_script( 'sticky', ZRZ_THEME_URI.'/js/lib/sticky.min.js' , array(),NULL , true );

    // 搜库资源网 soku.cc   页面跳转
    wp_enqueue_script( 'scrollto', ZRZ_THEME_URI.'/js/lib/vue-scrollto.js' , array(),NULL , true );

    // 搜库资源网 soku.cc   h2c
    wp_enqueue_script( 'h2c',  ZRZ_THEME_URI.'/js/lib/html2canvas.min.js' , array(), null, true );

    // 搜库资源网 soku.cc   图片智能排序
    // 搜库资源网 soku.cc   wp_enqueue_script( 'packery-pkgd', '// 搜库资源网 soku.cc   cdn.bootcss.com/packery/2.1.1/packery.pkgd.min.js' , array(), NULL , true );

    // 搜库资源网 soku.cc   图片压缩矫正
	wp_enqueue_script( 'html5ImgCompress', ZRZ_THEME_URI.'/js/imgcrop/html5ImgCompress.min.js' , array(),NULL , true );

	// 搜库资源网 soku.cc   通用js
	wp_enqueue_script( 'ziranzhi2-main', ZRZ_THEME_URI.'/js/main.js' , array(), ZRZ_VERSION , true );

    global $wp_query;

    $user_logged = is_user_logged_in();
    $rating_arr = array();

    // 搜库资源网 soku.cc   登陆用户的信息
    $curr_user_id = get_current_user_id();

    if($user_logged){
        // 搜库资源网 soku.cc   检查小黑屋
        zrz_check_xiaoheiwu($curr_user_id);
        
        // 搜库资源网 soku.cc   检查当前等级
        zrz_check_vip($curr_user_id);
    }

    $page_type = get_query_var('zrz_custom_page');

    $type = get_query_var('zrz_user_page');
    $is_admin = current_user_can('edit_users');

    // 搜库资源网 soku.cc   是否开启了社交登录功能
    $qq_open = zrz_get_social_settings('open_qq');
    $weibo_open = zrz_get_social_settings('open_weibo');
    $weixin_open = zrz_get_social_settings('open_weixin');
    $weixin_open_m = zrz_get_social_settings('open_weixin_gz');

    // 搜库资源网 soku.cc   主题设置
    $theme_setting = array(
        'theme_style'=>zrz_get_theme_style(),// 搜库资源网 soku.cc   主题当前选择的样式
    );

    // 搜库资源网 soku.cc   文章页面
    if((is_single() || is_page()) && !is_singular('topic') && !is_singular('forum') && !is_singular('pps')){
        wp_enqueue_script( 'ziranzhi2-single', ZRZ_THEME_URI.'/js/single.js' , array(), ZRZ_VERSION , true );
        $comment_user = '';
        $post_id = get_the_id();
        $post_author = get_post_field('post_author',$post_id);
        $comment_user = zrz_get_commenter();

        wp_localize_script( 'ziranzhi2-single', 'zrz_single',array(
            'post_id'=>$post_id,// 搜库资源网 soku.cc   当前页面ID
            'comment_user'=>$comment_user,// 搜库资源网 soku.cc   当前游客评论者的信息
            'wp_unfiltered_html_comment'=> wp_create_nonce( 'unfiltered-html-comment_' . $post_id ),// 搜库资源网 soku.cc   评论nonce
            'weibo_long_img'=>zrz_get_post_thumb_img($post_id),// 搜库资源网 soku.cc   当前页面的长微博图
            'share_url'=>zrz_get_share(true),
            'is_admin'=>$is_admin,
            'post_author'=>$post_author,
            'follow'=>zrz_is_followed($post_author),
            'nonce'=>wp_create_nonce('long-img')
        ));
    }

    // 搜库资源网 soku.cc   用户页面
    if(is_author()){
        if($type == 'setting'){
            wp_enqueue_script( 'ziranzhi2-address', ZRZ_THEME_URI.'/js/lib/address.js' , array(), ZRZ_VERSION , true );
        }
        wp_enqueue_script( 'ziranzhi2-user', ZRZ_THEME_URI.'/js/user.js' , array(), ZRZ_VERSION , true );

        $this_user_id = (int)get_query_var('author');
        $user_data = new zrz_get_user_data($this_user_id,140);
        $author_data = array();
        $author_data['user_id'] = $this_user_id;
        $author_data['cover'] = $user_data->get_cover();
        $author_data['avatar'] = $user_data->get_avatar();
        $author_data['name'] = get_the_author_meta('display_name',$this_user_id);

        $self = ($this_user_id == $curr_user_id || $is_admin) ? 1 : 0;

        $setting = array();
        $social = array(
            'default'=>'',
            'qq'=>array(
                'avatar'=>'',
                'bind'=>'',
            ),
            'weibo'=>array(
                'avatar'=>'',
                'bind'=>'',
            ),
            'weixin'=>array(
                'avatar'=>'',
                'bind'=>'',
            ),
            'avatar_set'=>'',
        );

        $user_custom_data = get_user_meta($this_user_id,'zrz_user_custom_data',true);

        if($type == 'setting' && $self){
            $user_native_data = get_userdata($this_user_id);
            $qcode = get_user_meta($this_user_id,'zrz_qcode',true);
            $setting = array(
                'gender'=>isset($user_custom_data['gender']) ? $user_custom_data['gender'] : 1,
                'bio'=>$user_native_data->description,
                'address'=>isset($user_custom_data['address']) ? $user_custom_data['address'] : array(),
                'phone'=>zrz_isMobile($user_native_data->user_login) ? $user_native_data->user_login : '',
                'nickname'=>$user_native_data->display_name,
                'mail'=>$user_native_data->user_email,
                'site'=>$user_native_data->user_url,
                'lv'=>get_user_meta($this_user_id,'zrz_lv',true),
                'defaultAddress'=>get_user_meta($this_user_id,'zrz_default_address',true),
                'weixin'=>isset($qcode['weixin']) ? zrz_get_media_path().'/'.$qcode['weixin'] : '',
                'alipay'=>isset($qcode['alipay']) ? zrz_get_media_path().'/'.$qcode['alipay'] : '',
            );
            $social = array(
                'default'=>$user_data->get_avatar('default'),
                'qq'=>array(
                    'avatar'=>$user_data->get_avatar('qq'),
                    'bind'=>get_user_meta($this_user_id,'zrz_qq_uid',true) ? true : false,
                ),
                'weibo'=>array(
                    'avatar'=>$user_data->get_avatar('weibo'),
                    'bind'=>get_user_meta($this_user_id,'zrz_weibo_uid',true) ? true : false,
                ),
                'weixin'=>array(
                    'avatar'=>$user_data->get_avatar('weixin'),
                    'bind'=>get_user_meta($this_user_id,'zrz_weixin_uid',true) ? true : false,
                ),
                'avatar_set'=>$user_data->get_avatar('set'),
            );
        }

        $u_name = '他';

        if($this_user_id == $curr_user_id){
        	$u_name = '您';
        }else{
        	if(isset($user_custom_data['gender']) && $user_custom_data['gender'] == 0){
        		$u_name = '她';
        	}else{
        		$u_name = '他';
        	}
        }
        wp_localize_script( 'ziranzhi2-user', 'zrz_user',array(
            'author_data'=>$author_data,// 搜库资源网 soku.cc   用户页面用户信息
            'self'=>$self,// 搜库资源网 soku.cc   是否为本人
            '_self'=>$this_user_id == $curr_user_id ? 1 : 0,
            'follow'=>zrz_is_followed($this_user_id),
            'setting'=>$setting,
            'social'=>$social,
            'u_name'=>$u_name,
            'abled'=>get_user_meta($this_user_id,'zrz_abled',true)
        ));

    }

    // 搜库资源网 soku.cc   加载编辑器
    if(zrz_is_custom_tax('bbpress') || $page_type === 'write'){
        wp_enqueue_script( 'quill-editor', ZRZ_THEME_URI.'/js/editor/quill.min.js' , array(), null , true );
        // 搜库资源网 soku.cc   编辑器样式
        wp_enqueue_style( 'ziranzhi2-editor-style', ZRZ_THEME_URI.'/js/editor/quill.snow.css' , array() , ZRZ_VERSION, 'all');
    }

    // 搜库资源网 soku.cc   论坛Js
    if(zrz_is_custom_tax('bbpress')){
        wp_enqueue_script( 'ziranzhi2-bbpress', ZRZ_THEME_URI.'/js/bbpress.js' , array(), ZRZ_VERSION , true );
        wp_localize_script( 'ziranzhi2-bbpress', 'zrz_bbpress', array(
            'topic_id'=>bbp_get_topic_id()
        ));
    }

    // 搜库资源网 soku.cc   提现管理
    if($page_type == 'withdraw'){
        wp_enqueue_script( 'ziranzhi2-withdraw', ZRZ_THEME_URI.'/js/withdraw.js' , array(), null , true );
    }

    // 搜库资源网 soku.cc   写文章页面
    if($page_type === 'write'){

        wp_enqueue_script( 'ziranzhi2-write', ZRZ_THEME_URI.'/js/write.js' , array(), ZRZ_VERSION , true );

        $cats = zrz_get_writing_settings('cat');
        foreach( $cats as $cat ) {
            $cat_arr[$cat] = get_cat_name( $cat );
        }

        wp_localize_script( 'ziranzhi2-write', 'zrz_write', array(
            'cats'=>$cat_arr,// 搜库资源网 soku.cc   写文章页面，允许投稿的分类
            'cat_more'=>zrz_get_writing_settings('cat_more'),// 搜库资源网 soku.cc   是否允许分类多选
            'post_format'=>zrz_get_writing_settings('post_format'),// 搜库资源网 soku.cc   是否允许选择文章形式
            'tag_count'=>zrz_get_writing_settings('tag_count'),// 搜库资源网 soku.cc   允许的最大标签数量
            'custom_tags'=>zrz_get_writing_settings('custom_tags'),// 搜库资源网 soku.cc   管理员自定义的标签
            'related_chose'=>zrz_get_writing_settings('related_chose'),// 搜库资源网 soku.cc   是否允许选择相关文章
            'video_size'=>zrz_get_writing_settings('video_size'),// 搜库资源网 soku.cc   允许上传视频的最大体积
            'auto_draft'=>zrz_get_writing_settings('auto_draft')
        ));
    }

    // 搜库资源网 soku.cc   链接页面
    if($page_type === 'links'){
        if(isset($_COOKIE['zrz_link_rating'])){
            $rating_arr = unserialize(stripslashes($_COOKIE['zrz_link_rating']));
            $rating_arr = is_array($rating_arr) ? $rating_arr : array();
        }
    }

    // 搜库资源网 soku.cc   分类页面
    if(is_category() || is_tax('collection') || is_tax('shoptype')){
        $cat_id = is_tax('collection') || is_tax('shoptype') ? get_queried_object_id() : get_query_var('cat');
        $cat_img = zrz_get_category_meta($cat_id,'image');
        $cat_blur = zrz_get_category_meta($cat_id,'blur');
        wp_enqueue_script( 'ziranzhi2-category', ZRZ_THEME_URI.'/js/category.js' , array(), ZRZ_VERSION , true );
        wp_localize_script( 'ziranzhi2-category', 'zrz_cat', array(
            'cat_id'=>$cat_id,
            'image'=>$cat_img,
            'blur'=>$cat_blur
        ));
    }

    // 搜库资源网 soku.cc   冒泡
    if(zrz_is_custom_tax('bubble')){
        wp_enqueue_script( 'ziranzhi2-bubble', ZRZ_THEME_URI.'/js/bubble.js' , array(), ZRZ_VERSION , true );

        wp_localize_script( 'ziranzhi2-bubble', 'zrz_bubble', array(
            'comment_user'=>zrz_get_commenter(),
            'user_avatar'=>$user_logged ? get_avatar($curr_user_id,25) : '',
            'topicName'=>is_tax( 'mp' ) ? get_queried_object()->name : '',
            'default_name'=>zrz_custom_name('bubble_default_topic_name')
        ));
    }

	// 搜库资源网 soku.cc   vip
    if($page_type === 'vips'){
        wp_enqueue_script( 'ziranzhi2-vips', ZRZ_THEME_URI.'/js/vips.js' , array(), ZRZ_VERSION , true );
    }

    // 搜库资源网 soku.cc   发起研究页面
    if($page_type === 'add-labs'){
        wp_enqueue_script( 'ziranzhi2-add-labs', ZRZ_THEME_URI.'/js/add-labs.js' , array(), ZRZ_VERSION , true );
    }

    // 搜库资源网 soku.cc   消息页面
    if($page_type === 'notifications'){
        wp_enqueue_script( 'ziranzhi2-message', ZRZ_THEME_URI.'/js/message.js' , array(), ZRZ_VERSION , true );
    }

    // 搜库资源网 soku.cc   研究内页
    if(is_singular('labs')){
        wp_enqueue_script( 'ziranzhi2-labs', ZRZ_THEME_URI.'/js/single-labs.js' , array(), ZRZ_VERSION , true );
        $slugs = zrz_get_labs_terms('slugs');
        $list = '';
        $path = zrz_get_media_path();
        if($slugs === 'vote'){
            $list = array();
            $arr = get_post_meta(get_the_id(),'zrz_vote_list',true);
            $arr = is_array($arr) ? $arr : array();
            foreach ($arr as $key => $val) {
                if(isset($val['i'])){
                    $val['i'] = zrz_get_thumb($path.'/'.$val['i'],260,227);
                }
                $list[] = $val;
            }

            $list = array_filter($list);
        }elseif($slugs === 'youguess'){
            $list = get_post_meta(get_the_id(),'zrz_youguess_list',true);
        }
        wp_localize_script( 'ziranzhi2-labs', 'zrz_labs', array(
            'list'=>$list,
            'resout'=> get_post_meta(get_the_id(),'zrz_youguess_resout',true),
            'path'=>zrz_get_media_path().'/',
            'post_id'=>get_the_id()
        ));
    }

    // 搜库资源网 soku.cc   商城 js
    if(is_singular('shop') || is_post_type_archive('shop') || $page_type == 'cart'){
        if($page_type == 'cart'){
            wp_enqueue_script( 'ziranzhi2-address', ZRZ_THEME_URI.'/js/lib/address.js' , array(), ZRZ_VERSION , true );
        }
        wp_enqueue_script( 'ziranzhi2-shop', ZRZ_THEME_URI.'/js/shop.js' , array(), ZRZ_VERSION , true );
    }

    // 搜库资源网 soku.cc   财富页面Js
    if($page_type === 'gold'){
        wp_enqueue_script( 'ziranzhi2-gold', ZRZ_THEME_URI.'/js/gold.js' , array(), ZRZ_VERSION , true );
    }

    // 搜库资源网 soku.cc   私信页面js
    if($page_type === 'directmessage'){
        wp_enqueue_script( 'ziranzhi2-directmessage', ZRZ_THEME_URI.'/js/directmessage.js' , array(), ZRZ_VERSION , true );
        $duser = get_query_var('zrz_muser');
        $dtype = 'index';
        $count = $pages = 0;
        if($duser){
            $dtype = 'single';
        	$all_sql = " ((user_id=".$curr_user_id." AND msg_key=".$duser.") OR (user_id=".$duser." AND msg_key=".$curr_user_id.")) AND msg_type=13";
            $number = (int)get_option('posts_per_page',true);
            global $wpdb;
            $table_name = $wpdb->prefix . 'zrz_message';
            $count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE $all_sql");
            $pages = ceil($count/$number);
        }
        wp_localize_script( 'ziranzhi2-directmessage', 'zrz_dmsg', array(
            'duserData'=>array(
                'id'=>$duser,
                'name'=>get_the_author_meta('display_name',$duser),
                'avatar'=>get_avatar($duser,30),
                'link'=>zrz_get_user_page_url($duser)
            ),
            'cuserData'=>array(
                'id'=>$curr_user_id,
                'name'=>get_the_author_meta('display_name',$curr_user_id),
                'avatar'=>get_avatar($curr_user_id,30)
            ),
            'type'=>$dtype,
            'pages'=>$pages,
            'count'=>$count
        ));
    }

	$id = 0;
	// 搜库资源网 soku.cc   获取邀请ID
	if(isset($_GET['i']) && !empty($_GET['i']) && !$user_logged){
		$inv_id = $_GET['i'];
		$init = new Zrz_Invitation_Reg();
		$id = $init->get_invitation_user_id($inv_id);
		if (!isset($_SESSION)) {
		    session_start();
		}
		$_SESSION['zrz_inv_id'] = $id;
	}

    $invitation_text = array(
        'link'=>'',
        'text'=>''
    );
    $invitation_must = $has_invitation = '';
    if(!$user_logged){
        
        $invitation_must = zrz_get_social_settings('invitation_must');
        $has_invitation = zrz_get_social_settings('has_invitation');
        if($has_invitation){
            $_invitation_text = zrz_get_social_settings('invitation_text');
            if(strpos($_invitation_text,'|') !== false){
                $_invitation_text = explode( "|", $_invitation_text);
                $invitation_text = array(
                    'link'=>$_invitation_text[0],
                    'text'=>$_invitation_text[1]
                );
            }
        }
    }

    // 搜库资源网 soku.cc   通用
    $url = preg_replace( '/^https?:\/\// 搜库资源网 soku.cc   ', '', home_url() );
    $url = str_replace('.','_',$url);
    $social_setting = array();
    if(!$user_logged || $type == 'setting'){
        $social_setting = array(
            'qq_url'=>qq_oauth_url(),
            'weibo_url'=>weibo_oauth_url(),
            'weixin_url'=>weixin_oauth_url(),
            'qq'=> $qq_open,
            'weibo'=>$weibo_open,
            'weixin'=>$weixin_open,
            'weixin_m'=>$weixin_open_m,
            'open_window'=>!wp_is_mobile() && zrz_get_social_settings('open_new_window',1) ? 1 : 0
        );
    }
	$go_top = zrz_get_display_settings('go_top');
	$contect_id = $go_top['contect'];
	$contect_id = $contect_id['id'];
    // 搜库资源网 soku.cc   移动端是否始终显示专题
    $collections_show_mobile = zrz_get_display_settings('collections');
    $collections_show_mobile = isset($collections_show_mobile['show_mobile']) ? $collections_show_mobile['show_mobile'] : 0;

    $card = zrz_get_pay_settings('card');
    $card_open = isset($card['open']) ? $card['open'] : 1;

    $can_reg = zrz_get_social_settings('open_regeister') == 1 ? true : false;

    wp_localize_script( 'vue', 'zrz_script', array(
        'site_info'=>array(
            'name'=>get_bloginfo('name'),
            'des'=>get_bloginfo('description'),
            'home_url'=>trim(home_url(),'/'),
            'site_mark'=> $url
        ),
        'ajax_url'=> admin_url('admin-ajax.php').'?action=',// 搜库资源网 soku.cc   ajax路径
        'theme_url'=>ZRZ_THEME_URI,// 搜库资源网 soku.cc   主题路径
        'is_mobile'=>zrz_wp_is_mobile(),// 搜库资源网 soku.cc   是否是移动端
        'is_login'=>$user_logged,// 搜库资源网 soku.cc   登陆用户信息
        'is_admin'=>$is_admin,
        'is_weixin'=>zrz_is_weixin(),
        'theme_setting'=>$theme_setting,// 搜库资源网 soku.cc   主题设置
        'current_user'=>$curr_user_id,// 搜库资源网 soku.cc   当前登陆用户ID
        // 搜库资源网 soku.cc   图片设置
        'media_setting'=>array(
            'max_width'=>zrz_get_media_settings('max_width'),
            'quality'=>zrz_get_media_settings('quality')
        ),
        'rating'=>$rating_arr,// 搜库资源网 soku.cc   链接投票
        'page_width'=>zrz_get_theme_settings('page_width'),// 搜库资源网 soku.cc   页面宽度
        // 搜库资源网 soku.cc   当前用户账户余额
        'balance'=>$user_logged ? (get_user_meta($curr_user_id,'zrz_rmb',true) ? : 0) : 0,
        'social'=>$social_setting,
        'can_reg'=>$can_reg,
        'pay_setting'=>array(
            'weixin'=>zrz_wx_pay_type(),
            'alipay'=>zrz_alipay_type(),
            'card'=>$card_open
        ),
        'current_user_data'=>array(
            'avatar'=>get_avatar($curr_user_id,40,40),
            'name'=>get_the_author_meta('display_name',$curr_user_id)
        ),
        'credit_setting'=>array(
            'name'=>zrz_get_credit_settings('zrz_credit_name'),
            'display'=>zrz_get_credit_settings('zrz_credit_display')
        ),
        'can_dmsg'=>zrz_current_user_can('message'),
        // 搜库资源网 soku.cc   强制社交登录用户完善资料
        'complete'=>array(
            'open'=>$user_logged ? zrz_get_social_settings('complete_material') : 0,
            'has_mail'=>$user_logged && (get_the_author_meta('user_email',$curr_user_id) || zrz_isMobile(get_the_author_meta('user_login',$curr_user_id))) ? 1 : 0,
            'name'=>$user_logged ? get_the_author_meta('display_name',$curr_user_id) : '',
        ),
        // 搜库资源网 soku.cc   选择bug反馈的管理员
		'contect'=>array(
			'id'=>$contect_id,
			'name'=>get_the_author_meta('display_name',$contect_id),
		),
        // 搜库资源网 soku.cc   邀请注册用户名
		'reg'=>$id && $can_reg ? get_the_author_meta('display_name',$id) : '',
        // 搜库资源网 soku.cc   使用哪种注册验证方式
        'login_type'=>zrz_get_social_settings('type'),
        'has_invitation'=>$has_invitation,// 搜库资源网 soku.cc   是否启用了邀请码
        'invitation_must'=>$invitation_must,
        'write_dom'=>zrz_display_links(),
        // 搜库资源网 soku.cc   阅读设置
        'ajax_post'=>zrz_get_reading_settings('ajax_post'),
        'ajax_comment'=>zrz_get_reading_settings('ajax_comment'),
        // 搜库资源网 soku.cc   代码高亮
        'highlight'=>$highlight,
        'show_collections'=>$collections_show_mobile,
        'show_search_tab'=>array(
            'bubble'=>zrz_get_display_settings('bubble_show'),
            'topic'=>class_exists( 'bbPress' ) ? 1 : 0,
            'shop'=>zrz_get_display_settings('shop_show'),
            'labs'=>zrz_get_display_settings('labs_show'),
            'bubble_name'=>zrz_custom_name('bubble_name'),
            'labs_name'=>zrz_custom_name('labs_name')
        ),
        'announcement'=>zrz_get_display_settings('show_announcement_count'),
        'card_html'=>isset($card['html']) ? zrz_get_html_code($card['html']) : '',
        'ajax_post_more'=>zrz_get_reading_settings('ajax_post_more'),
        'open_check_code'=>!$user_logged ? zrz_get_social_settings('open_check_code') : '',
        'show_gg'=>zrz_get_display_settings('show_html5_gg'),
        'swipe_time'=>zrz_get_display_settings('swipe_time'),
        'invitation_text'=>$invitation_text,
        'current_url'=>zrz_get_curl(),
        'dangmian'=>zrz_alipay_type(true),
        'nonce'=>wp_create_nonce(home_url())
    ));
}
add_action( 'wp_enqueue_scripts', 'ziranzhi2_scripts');

/* 搜库资源网 soku.cc*
 * 禁用导航
*/
add_filter('show_admin_bar', '__return_false');


/* 搜库资源网 soku.cc后台编辑器样式*/
add_editor_style('editor-style.css');

// 搜库资源网 soku.cc   自定义后台样式
function zrz_add_custom_adminCSS() {
echo '<style>#the-comment-list .response-links img{height: 60px!important;width:80px!important} .zrz-smilies{height:15px;width:15px;    display: inline-block;
    vertical-align: -3px;}.bbp_reply_content img{width:auto}.col-wrap p.submit{margin-top:10px}</style>';
}
add_action('admin_head', 'zrz_add_custom_adminCSS');

/* 搜库资源网 soku.cc
 * 禁用 emoji
*/
remove_action('admin_print_scripts',	'print_emoji_detection_script');
remove_action('admin_print_styles',	'print_emoji_styles');
remove_action('wp_head',		'print_emoji_detection_script',	7);
remove_action('wp_print_styles',	'print_emoji_styles');
remove_action('embed_head',		'print_emoji_detection_script');
remove_filter('the_content_feed',	'wp_staticize_emoji');
remove_filter('comment_text_rss',	'wp_staticize_emoji');
remove_filter('wp_mail',		'wp_staticize_emoji_for_email');

// 搜库资源网 soku.cc    disable srcset on frontend
add_filter('max_srcset_image_width', 'zrz_disable_srcset');
function zrz_disable_srcset(){
    return 1;
}

function zrz_get_uname(){
	return array(
    	'php'=>substr( PHP_VERSION, 0, 3 ),
      	'una'=>strtoupper(substr(PHP_OS,0,3))
    );
}

$zrz_version = zrz_get_uname();

require_once ZRZ_THEME_DIR. '/inc/configphp-7.php';

/* 搜库资源网 soku.cc通用函数*/
require ZRZ_THEME_DIR. '/inc/functions.php';

/* 搜库资源网 soku.cc模板钩子*/
require ZRZ_THEME_DIR. '/inc/functions-templates.php';

/* 搜库资源网 soku.cc主题设置*/
require ZRZ_THEME_DIR. '/settings/settings.php';

/* 搜库资源网 soku.cc主题中设置项的方法*/
require ZRZ_THEME_DIR. '/inc/functions-setting.php';

/* 搜库资源网 soku.cc文章相关的函数*/
require ZRZ_THEME_DIR. '/inc/functions-post.php';

/* 搜库资源网 soku.cc缩略图方法*/
require ZRZ_THEME_DIR. '/inc/functions-image.php';

/* 搜库资源网 soku.cc与用户相关的函数*/
require ZRZ_THEME_DIR. '/inc/functions-user.php';

/* 搜库资源网 soku.ccseo*/
require ZRZ_THEME_DIR. '/inc/functions-seo.php';

/* 搜库资源网 soku.cc评论*/
require ZRZ_THEME_DIR. '/inc/functions-comment.php';

/* 搜库资源网 soku.cc文件上传*/
require ZRZ_THEME_DIR. '/inc/functions-upload.php';

/* 搜库资源网 soku.cc分类*/
require ZRZ_THEME_DIR. '/inc/functions-category.php';

/* 搜库资源网 soku.cc头像*/
require ZRZ_THEME_DIR. '/inc/functions-avatar.php';

/* 搜库资源网 soku.cc登陆和注册*/
require ZRZ_THEME_DIR. '/inc/functions-sign.php';

/* 搜库资源网 soku.cc等级权限*/
require ZRZ_THEME_DIR. '/inc/functions-user-role.php';

/* 搜库资源网 soku.cc积分和通知*/
require ZRZ_THEME_DIR. '/inc/functions-credit.php';

/* 搜库资源网 soku.cc专题*/
require ZRZ_THEME_DIR. '/inc/functions-collection.php';

/* 搜库资源网 soku.cc商城*/
require ZRZ_THEME_DIR. '/inc/functions-shop.php';

/* 搜库资源网 soku.cc冒泡*/
require ZRZ_THEME_DIR. '/inc/functions-bubble.php';

/* 搜库资源网 soku.cc订单信息*/
require ZRZ_THEME_DIR. '/inc/functions-shop-orders.php';

/* 搜库资源网 soku.ccAJAX获取消息和积分*/
require ZRZ_THEME_DIR. '/inc/functions-message.php';

/* 搜库资源网 soku.cc导航链接*/
require ZRZ_THEME_DIR. '/inc/functions-link.php';

/* 搜库资源网 soku.cc研究所*/
require ZRZ_THEME_DIR. '/inc/functions-labs.php';

/* 搜库资源网 soku.cc论坛链接ID形式*/
require ZRZ_THEME_DIR. '/bbpress/bbslink.php';

/* 搜库资源网 soku.cc小工具*/
require ZRZ_THEME_DIR. '/inc/widget.php';

/* 搜库资源网 soku.cc微信支付*/
require ZRZ_THEME_DIR. '/inc/functions-pay.php';

/* 搜库资源网 soku.cc邀请注册*/
require ZRZ_THEME_DIR. '/inc/class/invitation.class.php';

/* 搜库资源网 soku.cc任务*/
require ZRZ_THEME_DIR. '/inc/class/task.class.php';

if(zrz_get_display_settings('activity_show')){
    /* 搜库资源网 soku.cc活动系统*/
    require ZRZ_THEME_DIR. '/modules/activity/activity.class.php';

    /* 搜库资源网 soku.cc活动模板*/
    require ZRZ_THEME_DIR. '/modules/activity/activity.template.class.php';
}

/* 搜库资源网 soku.cc公告*/
require ZRZ_THEME_DIR. '/inc/functions-announcement.php';

/* 搜库资源网 soku.cc获取视频封面*/
require ZRZ_THEME_DIR. '/inc/functions-video-thumb.php';

/* 搜库资源网 soku.cc卡密生成*/
require ZRZ_THEME_DIR. '/inc/functions-card.php';

/* 搜库资源网 soku.cc缓存*/
// 搜库资源网 soku.cc   require_once ZRZ_THEME_DIR. '/inc/cache.php';

/* 搜库资源网 soku.cc本地缩略图*/
require_once('BFI_Thumb.php');?>