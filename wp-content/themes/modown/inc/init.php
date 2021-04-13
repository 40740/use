<?php 
/**
 * remove actions from wp_head
 */
remove_action( 'wp_head',   'feed_links_extra', 3 ); 
remove_action( 'wp_head',   'rsd_link' ); 
remove_action( 'wp_head',   'wlwmanifest_link' ); 
remove_action( 'wp_head',   'index_rel_link' ); 
remove_action( 'wp_head',   'start_post_rel_link', 10, 0 ); 
remove_action( 'wp_head',   'wp_generator' ); 

/**
 * WordPress Emoji Delete
 */
remove_action( 'admin_print_scripts','print_emoji_detection_script');
remove_action( 'admin_print_styles','print_emoji_styles');
remove_action( 'wp_head',  'print_emoji_detection_script', 7);
remove_action( 'wp_print_styles','print_emoji_styles');
remove_filter( 'the_content_feed','wp_staticize_emoji');
remove_filter( 'comment_text_rss','wp_staticize_emoji');
remove_filter( 'wp_mail','wp_staticize_emoji_for_email');

/**
 * wp-json delete
 */
add_filter('rest_enabled', '_return_false');
add_filter('rest_jsonp_enabled', '_return_false');
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );

/**
 * open-sans delete
 */
function remove_open_sans() {
    wp_deregister_style( 'open-sans' );
    wp_register_style( 'open-sans', false );
    wp_enqueue_style('open-sans', '');
}
add_action( 'init', 'remove_open_sans' );

/**
 * Disable embeds
 */
if ( !function_exists( 'disable_embeds_init' ) ) :
    function disable_embeds_init(){
        global $wp;
        $wp->public_query_vars = array_diff($wp->public_query_vars, array('embed'));
        remove_action('rest_api_init', 'wp_oembed_register_route');
        add_filter('embed_oembed_discover', '__return_false');
        remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');
        add_filter('tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin');
        add_filter('rewrite_rules_array', 'disable_embeds_rewrites');
    }
    add_action('init', 'disable_embeds_init', 9999);

    function disable_embeds_tiny_mce_plugin($plugins){
        return array_diff($plugins, array('wpembed'));
    }
    function disable_embeds_rewrites($rules){
        foreach ($rules as $rule => $rewrite) {
            if (false !== strpos($rewrite, 'embed=true')) {
                unset($rules[$rule]);
            }
        }
        return $rules;
    }
    function disable_embeds_remove_rewrite_rules(){
        add_filter('rewrite_rules_array', 'disable_embeds_rewrites');
        flush_rewrite_rules();
    }
    register_activation_hook(__FILE__, 'disable_embeds_remove_rewrite_rules');

    function disable_embeds_flush_rewrite_rules(){
        remove_filter('rewrite_rules_array', 'disable_embeds_rewrites');
        flush_rewrite_rules();
    }
    register_deactivation_hook(__FILE__, 'disable_embeds_flush_rewrite_rules');
endif;

/**
 * hide admin bar
 */
add_filter('show_admin_bar','hide_admin_bar');
function hide_admin_bar($flag) {
    return false;
}

add_filter('upload_mimes','add_upload_webp');
function add_upload_webp ( $existing_mimes=array() ) {
  $existing_mimes['webp']='image/webp';
  return $existing_mimes;
}

/**
 * add theme thumbnail
 */
if ( function_exists( 'add_theme_support' ) ) {
    add_theme_support( 'post-thumbnails' );
}

function MBThemes_gallery_defaults( $settings ) {
    $settings['galleryDefaults']['columns'] = 4;
    $settings['galleryDefaults']['size'] = 'thumbnail';
    $settings['galleryDefaults']['link'] = 'file';
    return $settings;
}
add_filter( 'media_view_settings', 'MBThemes_gallery_defaults' );

add_filter( 'pre_option_link_manager_enabled', '__return_true' );

/**
 * get theme option         
 */
$current_theme = wp_get_theme();
function _MBT( $name, $default = false ) {
    global $current_theme;
    $option_name = 'Modown';
    $options = get_option( $option_name );
    if ( isset( $options[$name] ) ) {
        return $options[$name];
    }
    return $default;
}


add_filter('mce_buttons','MBThemes_add_next_page_button');
function MBThemes_add_next_page_button($mce_buttons) {
  $pos = array_search('wp_more',$mce_buttons,true);
  if ($pos !== false) {
    $tmp_buttons = array_slice($mce_buttons, 0, $pos+1);
    $tmp_buttons[] = 'wp_page';
    $mce_buttons = array_merge($tmp_buttons, array_slice($mce_buttons, $pos+1));
  }
  return $mce_buttons;
}

function MBThemes_del_tags($str){
  return trim(strip_tags($str));
}
add_filter('category_description', 'MBThemes_del_tags');

add_filter( 'login_headerurl', 'MBThemes_login_logo_url' );
function MBThemes_login_logo_url($url) {
  return home_url();
}

function MBThemes_login_logo_url_title() {
    return get_bloginfo("name");
}
add_filter( 'login_headertitle', 'MBThemes_login_logo_url_title' );

function MBThemes_login_logo() { 
?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo _MBT('logo_login');?>);
            background-size: cover;
            width:100px;
            height: 100px;
        }
        #login h1 a:before, .login h1 a:before {
          content:none;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'MBThemes_login_logo' );

function MBThemes_filter_smilies_src($img_src, $img, $siteurl) {
    return THEME_URI . '/static/img/smilies/' . $img;
}
add_filter('smilies_src', 'MBThemes_filter_smilies_src', 1, 10);

function smilies_reset() {
    global $wpsmiliestrans, $wp_smiliessearch, $wp_version;
    if ( !get_option( 'use_smilies' ) || $wp_version < 4.2)
        return;
    $wpsmiliestrans = array(
    ':mrgreen:' => 'mrgreen.png',
    ':exclaim:' => 'exclaim.png',
    ':neutral:' => 'neutral.png',
    ':twisted:' => 'twisted.png',
      ':arrow:' => 'arrow.png',
        ':eek:' => 'eek.png',
      ':smile:' => 'smile.png',
   ':confused:' => 'confused.png',
       ':cool:' => 'cool.png',
       ':evil:' => 'evil.png',
    ':biggrin:' => 'biggrin.png',
       ':idea:' => 'idea.png',
    ':redface:' => 'redface.png',
       ':razz:' => 'razz.png',
   ':rolleyes:' => 'rolleyes.png',
       ':wink:' => 'wink.png',
        ':cry:' => 'cry.png',
        ':lol:' => 'lol.png',
        ':mad:' => 'mad.png',
   ':drooling:' => 'drooling.png',
':persevering:' => 'persevering.png',
    );
}
smilies_reset();


function mbt_send_request($body, $method='POST'){
    $url = 'http://api.mobantu.com/auth/modown.php';
    $result = wp_remote_request($url, array('method' => $method, 'body'=>$body));
    if(is_array($result)){
        return $result['body'];
    }
}

function do_post($url, $data) {
  $ch = curl_init ();
  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
  curl_setopt ( $ch, CURLOPT_POST, TRUE );
  curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
  curl_setopt ( $ch, CURLOPT_URL, $url );
  curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  $ret = curl_exec ( $ch );
  curl_close ( $ch );
  return $ret;
}

function get_url_contents($url) {
  $ch = curl_init ();
  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  curl_setopt ( $ch, CURLOPT_URL, $url );
  $result = curl_exec ( $ch );
  curl_close ( $ch );
  return $result;
}

function wp_is_erphpdown_active(){
  include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
  if(!is_plugin_active( 'erphpdown/erphpdown.php' )){
    return 0;
  }else{
    return 1;
  }

}

function wp_menu_none(){
  return '<li><a href="'.admin_url('nav-menus.php').'">请到后台 外观-菜单 设置此导航</a></li>';
}


add_action( 'tgmpa_register', 'MBThemes_register_required_plugins' );
function MBThemes_register_required_plugins() {
  $plugins = array(
    array(
      'name'      => 'Advanced Custom Fields',
      'slug'      => 'advanced-custom-fields',
      'required'  => false,
    ),

    array(
      'name'        => 'Erphpdown',
      'slug'        => 'erphpdown',
      'is_callable' => 'erphpdod',
      'source' => '/插件请在模板兔网站购买！https://www.mobantu.com/1780.html',
      'external_url' => 'https://www.mobantu.com/1780.html',
      'required'  => true,
    ),

  );

  $config = array(
    'id'           => 'Mobantu',                 // Unique ID for hashing notices for multiple instances of TGMPA.
    'default_path' => '',                      // Default absolute path to bundled plugins.
    'menu'         => 'modown-install-plugins', // Menu slug.
    'parent_slug'  => 'themes.php',            // Parent menu slug.
    'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
    'has_notices'  => true,                    // Show admin notices or not.
    'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
    'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
    'is_automatic' => false,                   // Automatically activate plugins after installation or not.
    'message'      => '',                      // Message to output right before the plugins table.
  );

  tgmpa( $plugins, $config );
}


remove_shortcode( 'video', 'wp_video_shortcode' );
add_shortcode( 'video', 'MBT_video_shortcode' );
function MBT_video_shortcode( $attr, $content = '' ) {
	global $content_width;
	$post_id = get_post() ? get_the_ID() : 0;

	static $instance = 0;
	$instance++;
	$override = apply_filters( 'wp_video_shortcode_override', '', $attr, $content, $instance );
	if ( '' !== $override ) {
	return $override;
	}

	$video = null;

	$default_types = wp_get_video_extensions();
	$defaults_atts = array(
	'src' => '',
	'poster' => '',
	'loop' => '',
	'autoplay' => '',
	'preload' => 'metadata',
	'width' => 640,
	'height' => 360,
	//'class' => 'wp-video-shortcode',
	);

	foreach ( $default_types as $type ) {
	$defaults_atts[$type] = '';
	}

	$atts = shortcode_atts( $defaults_atts, $attr, 'video' );

	if ( is_admin() ) {
	if ( $atts['width'] > $defaults_atts['width'] ) {
	$atts['height'] = round( ( $atts['height'] * $defaults_atts['width'] ) / $atts['width'] );
	$atts['width'] = $defaults_atts['width'];
	}
	} else {
	if ( ! empty( $content_width ) && $atts['width'] > $content_width ) {
	$atts['height'] = round( ( $atts['height'] * $content_width ) / $atts['width'] );
	$atts['width'] = $content_width;
	}
	}

	$is_vimeo = $is_youtube = false;
	$yt_pattern = '#^https?://(?:www\.)?(?:youtube\.com/watch|youtu\.be/)#';
	$vimeo_pattern = '#^https?://(.+\.)?vimeo\.com/.*#';

	$primary = false;
	if ( ! empty( $atts['src'] ) ) {
	$is_vimeo = ( preg_match( $vimeo_pattern, $atts['src'] ) );
	$is_youtube = ( preg_match( $yt_pattern, $atts['src'] ) );
	if ( ! $is_youtube && ! $is_vimeo ) {
	$type = wp_check_filetype( $atts['src'], wp_get_mime_types() );
	if ( ! in_array( strtolower( $type['ext'] ), $default_types ) ) {
	return sprintf( '<a class="wp-embedded-video" href="%s">%s</a>', esc_url( $atts['src'] ), esc_html( $atts['src'] ) );
	}
	}

	if ( $is_vimeo ) {
	wp_enqueue_script( 'mediaelement-vimeo' );
	}

	$primary = true;
	array_unshift( $default_types, 'src' );
	} else {
	foreach ( $default_types as $ext ) {
	if ( ! empty( $atts[ $ext ] ) ) {
	$type = wp_check_filetype( $atts[ $ext ], wp_get_mime_types() );
	if ( strtolower( $type['ext'] ) === $ext ) {
	$primary = true;
	}
	}
	}
	}

	if ( ! $primary ) {
	$videos = get_attached_media( 'video', $post_id );
	if ( empty( $videos ) ) {
	return;
	}

	$video = reset( $videos );
	$atts['src'] = wp_get_attachment_url( $video->ID );
	if ( empty( $atts['src'] ) ) {
	return;
	}

	array_unshift( $default_types, 'src' );
	}

	$library = apply_filters( 'wp_video_shortcode_library', 'mediaelement' );
	if ( 'mediaelement' === $library && did_action( 'init' ) ) {
	wp_enqueue_style( 'wp-mediaelement' );
	wp_enqueue_script( 'wp-mediaelement' );
	wp_enqueue_script( 'mediaelement-vimeo' );
	}

	if ( 'mediaelement' === $library ) {
	if ( $is_youtube ) {
	$atts['src'] = remove_query_arg( 'feature', $atts['src'] );
	$atts['src'] = set_url_scheme( $atts['src'], 'https' );
	} elseif ( $is_vimeo ) {
	$parsed_vimeo_url = wp_parse_url( $atts['src'] );
	$vimeo_src = 'https://' . $parsed_vimeo_url['host'] . $parsed_vimeo_url['path'];

	$loop = $atts['loop'] ? '1' : '0';
	$atts['src'] = add_query_arg( 'loop', $loop, $vimeo_src );
	}
	}

	$atts['class'] = apply_filters( 'wp_video_shortcode_class', $atts['class'], $atts );

	$html_atts = array(
	//'class' => $atts['class'],
	//'id' => sprintf( 'video-%d-%d', $post_id, $instance ),
	//'width' => absint( $atts['width'] ),
	//'height' => absint( $atts['height'] ),
	'poster' => esc_url( $atts['poster'] ),
	'loop' => wp_validate_boolean( $atts['loop'] ),
	'autoplay' => wp_validate_boolean( $atts['autoplay'] ),
	//'preload' => $atts['preload'],
	);

	foreach ( array( 'poster', 'loop', 'autoplay', 'preload' ) as $a ) {
	if ( empty( $html_atts[$a] ) ) {
	unset( $html_atts[$a] );
	}
	}

	$attr_strings = array();
	foreach ( $html_atts as $k => $v ) {
	$attr_strings[] = $k . '="' . esc_attr( $v ) . '"';
	}

	$html = '';
	$fileurl = '';
	foreach ( $default_types as $fallback ) {
	if ( ! empty( $atts[ $fallback ] ) ) {
	if ( empty( $fileurl ) ) {
	$fileurl = $atts[ $fallback ];
	}
	if ( 'src' === $fallback && $is_youtube ) {
	$type = array( 'type' => 'video/youtube' );
	} elseif ( 'src' === $fallback && $is_vimeo ) {
	$type = array( 'type' => 'video/vimeo' );
	} else {
	$type = wp_check_filetype( $atts[ $fallback ], wp_get_mime_types() );
	}
	$url = add_query_arg( '_', $instance, $atts[ $fallback ] );
	}
	}

	$html .= sprintf( '<video %s src="'.esc_url( $url ).'" controls="controls">', join( ' ', $attr_strings ) );

	$html .= '</video>';

	$width_rule = '';
	if ( ! empty( $atts['width'] ) ) {
	$width_rule = sprintf( 'width: %dpx;', $atts['width'] );
	}
	//$output = sprintf( '<div style="%s" class="wp-video">%s</div>', $width_rule, $html );
	$output = $html;

	return apply_filters( 'MBT_video_shortcode', $output, $atts, $video, $post_id, $library );
}

add_filter( 'post_gallery', 'MBT_gallery_shortcode', 10, 2 );
function MBT_gallery_shortcode( $output, $attr ) {
    $post = get_post();

    static $instance = 0;
    $instance++;

    // override default link settings
    if ( empty(  $attr['link'] ) ) {
        $attr['link'] = 'none'; // set your default value here
    }

    if ( !empty( $attr['ids'] ) ) {
        // 'ids' is explicitly ordered, unless you specify otherwise.
        if ( empty( $attr['orderby'] ) )
            $attr['orderby'] = 'post__in';
        $attr['include'] = $attr['ids'];
    }

    // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
    if ( isset( $attr['orderby'] ) ) {
        $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
        if ( !$attr['orderby'] )
            unset( $attr['orderby'] );
    }

    extract(shortcode_atts(array(
        'order'      => 'ASC',
        'orderby'    => 'menu_order ID',
        'id'         => $post ? $post->ID : 0,
        'itemtag'    => 'div',
        'icontag'    => 'dt',
        'captiontag' => 'dd',
        'columns'    => 3,
        'size'       => 'full',
        'include'    => '',
        'exclude'    => ''
    ), $attr, 'gallery'));

    $id = intval($id);
    if ( 'RAND' == $order )
        $orderby = 'none';

    if ( !empty($include) ) {
        $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif ( !empty($exclude) ) {
        $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    } else {
        $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    }

    if ( empty($attachments) )
        return '';

    if ( is_feed() ) {
        $output = "\n";
        foreach ( $attachments as $att_id => $attachment )
            $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
        return $output;
    }

    $itemtag = tag_escape($itemtag);
    $captiontag = tag_escape($captiontag);
    $icontag = tag_escape($icontag);
    $valid_tags = wp_kses_allowed_html( 'post' );
    if ( ! isset( $valid_tags[ $itemtag ] ) )
        $itemtag = 'dl';
    if ( ! isset( $valid_tags[ $captiontag ] ) )
        $captiontag = 'dd';
    if ( ! isset( $valid_tags[ $icontag ] ) )
        $icontag = 'dt';

    $columns = intval($columns);
    $itemwidth = $columns > 0 ? floor(100/$columns) : 100;
    $float = is_rtl() ? 'right' : 'left';

    $selector = "gallery-{$instance}";

    $gallery_style = $gallery_div = '';

    $size_class = sanitize_html_class( $size );
    $gallery_div = "<div id='$selector' class='gallery galleryid-{$id} clearfix'>";
    $output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

    $i = 0;
    foreach ( $attachments as $id => $attachment ) {
        if ( ! empty( $attr['link'] ) && 'file' === $attr['link'] )
            $image_output = wp_get_attachment_link( $id, $size, false, false );
        elseif ( ! empty( $attr['link'] ) && 'none' === $attr['link'] )
            $image_output = wp_get_attachment_image( $id, $size, false );
        else
            $image_output = wp_get_attachment_link( $id, $size, true, false );

        $image_meta  = wp_get_attachment_metadata( $id );

        $orientation = '';
        if ( isset( $image_meta['height'], $image_meta['width'] ) )
            $orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';

        $output .= "<{$itemtag} class='gallery-item'>";
        $output .= "$image_output";
        $output .= "</{$itemtag}>";
    }

    $output .= "</div>\n";

    return $output;
}

function MBT_get_comment_list_by_user($clauses) {
  if (is_admin()) {
    global $current_user, $wpdb;
    //$clauses['join'] = ", wp_posts";
    $clauses['where'] .= " AND user_id = ".$current_user->ID;
  };
  return $clauses;
}

if(!current_user_can('edit_others_posts')) {
  add_filter('comments_clauses', 'MBT_get_comment_list_by_user');
}
