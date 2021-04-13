<?php
if (!defined('ABSPATH')) {die;} // Cannot access directly.

function _get_delimiter() {
    return _cao('connector') ? _cao('connector') : '-';
}
remove_action('wp_head', '_wp_render_title_tag', 1);

function _title() {

    if (_cao('del_ripro_seo', '0')) {
        return wp_title('-', true, 'right');
    }
    global $paged, $post;

    $html = '';
    $t    = trim(wp_title('', false));

    if ($t) {
        $html .= $t . _get_delimiter();
    }

    if (get_query_var('page')) {
        $html .= '第' . get_query_var('page') . '页' . _get_delimiter();
    }

    $html .= get_bloginfo('name');

    if (is_home()) {
        if ($paged > 1) {
            $html .= _get_delimiter() . '最新发布';
        } elseif (get_option('blogdescription')) {
            $html .= _get_delimiter() . get_option('blogdescription');
        }
    }

    if (is_category()) {
        global $wp_query;
        $cat_ID  = get_query_var('cat');
        $seo_str = get_term_meta($cat_ID, 'seo-title', true);
        $cat_tit = ($seo_str) ? $seo_str : _get_tax_meta($cat_ID, 'title');
        if ($cat_tit) {
            $html = $cat_tit;
        }
    } elseif (is_tag()) {
        $tagName   = single_tag_title('', false);
        $tagObject = get_term_by('name', $tagName, 'post_tag');
        $tagID     = $tagObject->term_id;
        $seo_str   = get_term_meta($tagID, 'seo-title', true);
        $html      = ($seo_str) ? trim($seo_str) : $tagName;
    } elseif (is_singular() && get_post_meta($post->ID, 'post_titie_s', true)) {
        $html = get_post_meta($post->ID, 'post_titie', true);
    }

    if ($paged > 1) {
        $html .= _get_delimiter() . '第' . $paged . '页';
    }

    return $html;
}

function _the_head() {
    _keywords();
    _description();
    _post_views_record();
    $css_str = _cao('web_css');
    if ($css_str) {
        echo '<style type="text/css">' . $css_str . '</style>';
    }
}
add_action('wp_head', '_the_head');

/**
 * [_keywords SEO关键词优化]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:17:48+0800
 * @return   [type]                   [description]
 */
function _keywords() {
    if (_cao('del_ripro_seo', '0')) {
        return;
    }
    global $s, $post;
    $keywords = '';
    if (is_singular()) {
        if (get_the_tags($post->ID)) {
            foreach (get_the_tags($post->ID) as $tag) {
                $keywords .= $tag->name . ',';
            }

        }
        foreach (get_the_category($post->ID) as $category) {
            $keywords .= $category->cat_name . ', ';
        }

        if (get_post_meta($post->ID, 'post_keywords_s', true)) {
            $the = trim(get_post_meta($post->ID, 'keywords', true));
            if ($the) {
                $keywords = $the;
            }
        } else {
            $keywords = substr_replace($keywords, '', -2);
        }

    } elseif (is_home()) {
        $seo_opt  = _cao('seo');
        $keywords = (!empty($seo_opt['web_keywords'])) ? $seo_opt['web_keywords'] : 'RiPro主题是最好的资源下载付费主题';
    } elseif (is_tag()) {
        $tagName   = single_tag_title('', false);
        $tagObject = get_term_by('name', $tagName, 'post_tag');
        $tagID     = $tagObject->term_id;
        $seo_str   = get_term_meta($tagID, 'seo-keywords', true);
        $keywords  = ($seo_str) ? trim($seo_str) : $tagName;
    } elseif (is_category()) {
        global $wp_query;
        $cat_ID   = get_query_var('cat');
        $seo_str  = get_term_meta($cat_ID, 'seo-keywords', true);
        $keywords = ($seo_str) ? trim($seo_str) : trim(wp_title('', false));
    } elseif (is_search()) {
        $keywords = esc_html($s, 1);
    } else {
        $keywords = trim(wp_title('', false));
    }
    if ($keywords) {
        echo "<meta name=\"keywords\" content=\"$keywords\">\n";
    }
}

/**
 * [_description SEO描述优化]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:18:02+0800
 * @return   [type]                   [description]
 */
function _description() {
    if (_cao('del_ripro_seo', '0')) {
        return;
    }
    global $s, $post;
    $description = '';
    $blog_name   = get_bloginfo('name');
    if (is_singular()) {
        if (!empty($post->post_excerpt)) {
            $text = $post->post_excerpt;
        } else {
            $text = $post->post_content;
        }
        $description = trim(str_replace(array("\r\n", "\r", "\n", "　", " "), " ", str_replace("\"", "'", strip_tags($text))));
        $description = substr_ext(strip_tags(strip_shortcodes($description)), 0, 140, 'utf-8', '...');
        if (!($description)) {
            $description = $blog_name . "-" . trim(wp_title('', false));
        }
        if (get_post_meta($post->ID, 'post_description_s', true)) {
            $the = trim(get_post_meta($post->ID, 'description', true));
            if ($the) {
                $description = $the;
            }
        }

    } elseif (is_home()) {
        $seo_opt     = _cao('seo');
        $description = (!empty($seo_opt['web_description'])) ? $seo_opt['web_description'] : 'RiPro主题是最好的资源下载付费主题';
    } elseif (is_tag()) {
        $tagName     = single_tag_title('', false);
        $tagObject   = get_term_by('name', $tagName, 'post_tag');
        $tagID       = $tagObject->term_id;
        $seo_str     = get_term_meta($tagID, 'seo-description', true);
        $description = ($seo_str) ? trim($seo_str) : trim(wp_title('', false));
    } elseif (is_category()) {
        global $wp_query;
        $cat_ID      = get_query_var('cat');
        $seo_str     = get_term_meta($cat_ID, 'seo-description', true);
        $description = ($seo_str) ? trim($seo_str) : trim(wp_title('', false));
    } elseif (is_archive()) {
        $description = $blog_name . "-" . trim(wp_title('', false));
    } elseif (is_search()) {
        $description = $blog_name . ": '" . esc_html($s, 1) . "' " . __('的搜索結果', 'haoui');
    } elseif (is_tag()) {

    } else {
        $description = $blog_name . "'" . trim(wp_title('', false)) . "'";
    }
    $description = mb_substr($description, 0, _get_description_max_length(), 'utf-8');
    echo "<meta name=\"description\" content=\"$description\">\n";
}

// Open Graph
function meta_og() {
    global $post;

    if (is_single()) {
        $img_src      = _get_post_thumbnail_url($post);
        $excerpt      = wp_trim_words(strip_shortcodes($post->post_content),120,'...');
        $excerpt_more = '';
        if (strlen($excerpt) > 155) {
            $excerpt      = substr($excerpt, 0, 155);
            $excerpt_more = ' ...';
        }
        $excerpt      = str_replace('"', '', $excerpt);
        $excerpt      = str_replace("'", '', $excerpt);
        $excerptwords = preg_split('/[\n\r\t ]+/', $excerpt, -1, PREG_SPLIT_NO_EMPTY);
        array_pop($excerptwords);
        $excerpt = implode(' ', $excerptwords) . $excerpt_more;
        ?>
        <meta property="og:title" content="<?php echo get_the_title(); ?>">
        <meta property="og:description" content="<?php echo $excerpt; ?>">
        <meta property="og:type" content="article">
        <meta property="og:url" content="<?php echo the_permalink(); ?>">
        <meta property="og:site_name" content="<?php echo get_bloginfo('name'); ?>">
        <meta property="og:image" content="<?php echo $img_src; ?>">
    <?php
} else {
        return;
    }
}

//是否开启meta_og协议
if (_cao('is_post_meta_og')) {
    add_action('wp_head', 'meta_og', 5);
}
