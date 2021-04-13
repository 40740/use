<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-09-29 13:18:37
 * @LastEditTime: 2021-01-09 14:47:28
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */
$functions = array(
    'zib-theme',
    'zib-head',
    'zib-header',
    'zib-content',
    'zib-footer',
    'zib-index',
    'zib-category',
    'zib-author',
    'zib-posts-list',
    'zib-share',
    'zib-user',
    'zib-page',
    'zib-single',
    'zib-comments-list',
    'zib-svg-icon',
    'zib_baidu',
    'zib_message',
    'zib_email',
    'zib-frontend-set',
    'message/functions',
);

foreach ($functions as $function) {
    require_once plugin_dir_path(__FILE__) . $function . '.php';
}

if (is_admin()) {
    require_once plugin_dir_path(__FILE__) . 'admin/admin-main.php';
    require_once plugin_dir_path(__FILE__) . 'admin/admin-set.php';
}

//老版slider
function zib_get_img_slider($args)
{
    $defaults = array(
        'class' => '',
        'type' => '',
        'lazy' => false,
        'pagination' => true,
        'effect' => 'slide',
        'button' => true,
        'loop' => true,
        'auto_height' => false,
        'm_height'   => '',
        'pc_height'   => '',
        'autoplay' => true,
        'interval' => 4000,
        'spaceBetween' => 15,
        'echo' => true,
    );
    $args = wp_parse_args((array) $args, $defaults);
    $class = $args['class'];
    $type = $args['type'];
    $lazy = $args['lazy'];
    $pagination = $args['pagination'];
    $effect = ' data-effect="' . $args['effect'] . '"';
    $button = $args['button'];
    $loop = $args['loop'] ? ' data-loop="true"' : '';
    $auto_h = $args['auto_height'] ? ' auto-height="true"' : '';
    $interval = $args['interval'] < 999 ? $args['interval'] * 1000 : $args['interval'];
    $interval = $args['autoplay'] ? ' data-autoplay="' . $args['autoplay'] . '"' : '';
    $interval = $args['interval'] && $args['autoplay'] ? ' data-interval="' . $interval . '"' : '';
    $spaceBetween = $args['spaceBetween'] ? ' data-spaceBetween="' . $args['spaceBetween'] . '"' : '';

    $style = '';
    if (!$auto_h) {
        $_h = !empty($args['m_height']) ? '--m-height :' . (int) $args['m_height'] . 'px;' : '';
        $_h .= !empty($args['pc_height']) ? '--pc-height :' . (int) $args['pc_height'] . 'px;' : '';
        $style = ' style="' . $_h . '"';
    }

    if (!$lazy && _pz('lazy_sider')) {
        $lazy = true;
    }
    if (empty($args['slides'])) {
        return;
    }
    $slides = '';
    $pagination_rigth = '';
    foreach ($args['slides'] as $slide) {
        $lazy_src = ZIB_STYLESHEET_DIRECTORY_URI . '/img/thumbnail-lg.svg';
        $s_class = isset($slide['class']) ? $slide['class'] : '';
        $s_href = isset($slide['href']) ? $slide['href'] : '';
        $s_image = isset($slide['image']) ? $slide['image'] : '';
        $s_blank = !empty($slide['blank']) ? ($s_href ? ' target="_blank"' : '') : '';
        $s_caption = isset($slide['caption']) ? $slide['caption'] : '';
        $s_desc = !empty($slide['desc']) ? '<div class="s-desc">' . $slide['desc'] . '</div>' : '';
        $pagination_rigth = !empty($slide['desc']) ? ' kaoyou' : ' kaoyou';
        $slides .= '<div class="swiper-slide' . ' ' . $s_class . '">' . $s_desc .
            '<a' . $s_blank . ($s_href ? ' href="' . $s_href . '"' : '') . '>
				<img class="lazyload swiper-lazy radius8" ' . ($lazy ? ' data-src="' . $s_image . '" src="' . $lazy_src . '"' : ' src="' . $s_image . '"') . '></a>'
            . ($s_caption ? '<div class="carousel-caption">' . $s_caption . '</div>' : '') . '</div>';
    }
    $pagination = $pagination ? '<div class="swiper-pagination' . $pagination_rigth . '"></div>' : '';
    $button = $button ? '<div class="swiper-button-prev"></div><div class="swiper-button-next"></div>' : '';

    $con = '<div class="new-swiper swiper-c ' . $class . '" ' . $effect . $loop . $auto_h . $interval . $spaceBetween . $style . '>
            <div class="swiper-wrapper">' . $slides . '</div>' .
        $button . $pagination . '</div>';
    if ($args['echo']) {
        echo '<div class="relative zib-slider theme-box">' . $con . '</div>';
    } else {
        return '<div class="relative zib-slider">' . $con . '</div>';
    }
}


/**
 * @description: slider构建函数
 * @param {*}
 * @return {*}
 */
function zib_new_slider($args, $echo = true)
{
    $defaults = array(
        'class' => 'mb20',
        'type' => '',
        'direction' => 'horizontal',
        'lazy' => false,
        'pagination' => true,
        'effect' => 'slide',
        'button' => true,
        'loop' => true,
        'auto_height' => false,
        'm_height'   => '',
        'pc_height'   => '',
        'autoplay' => true,
        'interval' => 4000,
        'speed' => 800,
        'slides' => array(),
    );
    $args = wp_parse_args((array) $args, $defaults);
    if (empty($args['slides'][0])) return;

    $class = $args['class'];
    $type = $args['type'];
    $lazy = $args['lazy'];
    $pagination = $args['pagination'];
    $effect = ' data-effect="' . $args['effect'] . '"';
    $button = $args['button'];
    $loop = $args['loop'] ? ' data-loop="true"' : '';
    $auto_h = ($args['auto_height'] && $args['direction'] != 'vertical') ? ' auto-height="true"' : '';
    $autoplay = $args['autoplay'] ? ' data-autoplay="' . $args['autoplay'] . '"' : '';
    $interval = $args['interval'] < 999 ? $args['interval'] * 1000 : $args['interval'];
    $autoplay .= ($interval && $args['autoplay']) ? ' data-interval="' . $interval . '"' : '';
    $speed = $args['speed'] ? ' data-speed="' . $args['speed'] . '"' : '';
    $direction = $args['direction'] ? ' data-direction="' . $args['direction'] . '"' : '';
    $spaceBetween = isset($args['spacebetween']) ? ' data-spaceBetween="' . $args['spacebetween'] . '"' : '';

    $style = '';
    if (!$auto_h) {
        $_h = !empty($args['m_height']) ? '--m-height :' . (int) $args['m_height'] . 'px;' : '';
        $_h .= !empty($args['pc_height']) ? '--pc-height :' . (int) $args['pc_height'] . 'px;' : '';
    } else {
        $_h = !empty($args['max_height']) ? '--max-height :' . (int) $args['max_height'] . 'px;' : '';
        $_h .= !empty($args['min_height']) ? '--min-height :' . (int) $args['min_height'] . 'px;' : '';
    }
    $style = ' style="' . $_h . '"';

    if (!$lazy && _pz('lazy_sider')) {
        $lazy = true;
    }

    $slides = '';
    foreach ($args['slides'] as $slide) {
        $lazy_src = ZIB_STYLESHEET_DIRECTORY_URI . '/img/thumbnail-lg.svg';
        //图片
        $s_background = isset($slide['background']) ? $slide['background'] : '';
        if (!$s_background) continue;
        //背景图
        $s_background = '<img class="lazyload swiper-lazy radius8" ' . ($lazy ? ' data-src="' . $s_background . '" src="' . $lazy_src . '"' : ' src="' . $s_background . '"') . '>';

        //更多图层
        $s_layers = '';
        if (!empty($slide['image_layer'][0]['image'])) {
            foreach ($slide['image_layer'] as $layer) {
                $layer_image = isset($layer['image']) ? $layer['image'] : '';
                if (!$layer_image) continue;
                $layer_image = $layer_image ? '<img class="lazyload swiper-lazy radius8" ' . ($lazy ? ' data-src="' . $layer_image . '" src="' . $lazy_src . '"' : ' src="' . $layer_image . '"') . '>' : '';

                //视差滚动
                $layer_parallax = isset($layer['parallax']) ? (int)$layer['parallax'] : 0;
                $layer_parallax = $layer_parallax ? ' data-swiper-parallax="' . $layer_parallax . '%"' : '';

                //视差透明度
                $layer_parallax_opacity = isset($layer['parallax_opacity']) ? (int)$layer['parallax_opacity'] / 100 : 0;
                $layer_parallax .= ($layer_parallax && $layer_parallax_opacity && $layer_parallax_opacity !== 1) ? ' data-swiper-parallax-opacity="' . $layer_parallax_opacity . '"' : '';

                //视差缩放
                $layer_parallax_scale = isset($layer['parallax_scale']) ? (int)$layer['parallax_scale'] / 100 : 0;
                $layer_parallax_scale = ($layer_parallax_scale && $layer_parallax_scale !== 1) ? ' data-swiper-parallax-scale="' . $layer_parallax_scale . '"' : '';

                //前景图对齐
                $layer_class = '';
                if (!empty($layer['free_size'])) {
                    $layer_class = ' slide-layer';
                    $layer_class .= isset($layer['align']) ? ' text-' . $layer['align'] : '';
                }
                //图层动画
                $animate_attr = '';
                /**
                $animate = array(
                    array(
                        'value' => 'rubberBand',
                        'duration' => '',
                        'loop' => '',
                        'delay' => '',
                    ),
                );
                if (!empty($animate[0]['value'])) {
                    $animate_attr = ' swiper-animate-effect="' . esc_attr(json_encode($animate)) . '"';
                    $layer_class .= ' ani';
                }
                 */
                $s_layers .= '<div' . $animate_attr . ' class="absolute' . $layer_class . '"' . $layer_parallax . $layer_parallax_scale . '>' . $layer_image . '</div>';
            }
        }
        $s_class = isset($slide['class']) ? $slide['class'] : '';
        //链接
        $s_href = isset($slide['link']['url']) ? $slide['link']['url'] : '';
        $s_blank = !empty($slide['link']['target']) ? ($s_href ? ' target="_blank"' : '') : '';
        //文案
        $slide_text = !empty($slide['text']['title']) ? $slide['text'] : '';
        $s_text = !empty($slide_text['title']) ? '<div class="slide-title">' . $slide_text['title'] . '</div>'  : '';
        $s_text .= !empty($slide_text['desc']) ? '<div class="slide-desc">' . $slide_text['desc'] . '</div>' : '';

        if ($s_text) {
            //控制位置class
            $s_text_class = 'abs-center slide-text';
            $s_text_class .=  isset($slide_text['text_align']) ? ' ' . $slide_text['text_align'] : '';
            //字体大小
            $s_text_size = !empty($slide_text['text_size_pc']) ? '--text-size-pc:' . (int) $slide_text['text_size_pc'] . 'px;' : '';
            $s_text_size .= !empty($slide_text['text_size_m']) ? '--text-size-m:' . (int) $slide_text['text_size_m'] . 'px;' : '';

            $s_text_style =  $s_text_size ? ' style="' . $s_text_size . '"' : '';
            $s_text = '<div class="' . $s_text_class . '"' . $s_text_style . '>' . $s_text . '</div>';
            //视差滚动
            $s_text_parallax = isset($slide_text['parallax']) ? $slide_text['parallax'] : 0;
            if ($s_text_parallax) {
                $s_text_parallax = $s_text_parallax ? ' data-swiper-parallax="' . $s_text_parallax . '"' : '';
                $s_text = '<div class="absolute"' . $s_text_parallax .  '>' . $s_text . '</div>';
            }
        }
        $slides .= '<div class="swiper-slide' . ' ' . $s_class . '">';
        $slides .= '<a' . $s_blank . ($s_href ? ' href="' . $s_href . '"' : '') . '>';
        $slides .= $s_background;
        $slides .= $s_layers;
        $slides .= $s_text;
        $slides .= '</a>';
        $slides .= '</div>';
    }
    if (!$slides) return;
    $slides = '<div class="swiper-wrapper">' . $slides . '</div>';

    $pagination = $pagination ? '<div class="swiper-pagination kaoyou"></div>' : '';
    $button = $button ? '<div class="swiper-button-prev"></div><div class="swiper-button-next"></div>' : '';

    $con = '<div class="new-swiper ' . $class . '" ' . $direction . $effect . $loop . $speed . $auto_h . $autoplay . $spaceBetween . $style . '>';
    $con .=  $slides;
    $con .=  $button;
    $con .=     $pagination;
    $con .= '</div>';

    if ($echo) {
        echo '<div class="relative zib-slider">' . $con . '</div>';
    } else {
        return '<div class="relative zib-slider">' . $con . '</div>';
    }
}

/**
 * @description: 获取用户点赞、查看数量
 * @param {*}
 * @return {*}
 */
function zib_avatar_metas($user_id, $echo = true)
{
    if (!$user_id) return;
    //$avatar = zib_get_data_avatar($user_id);
    $like_n = get_user_posts_meta_count($user_id, 'like');
    $view_n = get_user_posts_meta_count($user_id, 'views');
    $com_n = get_user_comment_count($user_id);
    $post_n = (int) count_user_posts($user_id, 'post', true);

    $html = '';
    if ($post_n) {
        $html .= '<a class="but c-blue tag-posts" data-toggle="tooltip" title="查看更多文章" href="' . get_author_posts_url(get_the_author_meta('ID')) . '">' . zib_svg('post') . $post_n . '</a>';
    }
    if ($com_n) {
        $html .= '<a class="but c-green tag-view" data-toggle="tooltip" title="共计' . $com_n . '条评论">' . zib_svg('comment') . $com_n . '</a>';
    }
    if ($view_n) {
        $html .= '<a class="but c-red tag-view" data-toggle="tooltip" title="人气值 ' . $view_n . '">' . zib_svg('huo') . $view_n . '</a>';
    }
    if ($like_n) {
        $html .= '<a class="but c-yellow tag-like" data-toggle="tooltip" title="获得' . $like_n . '个点赞">' . zib_svg('like') . $like_n . '</a>';
    }
    if (!$echo) return $html;
    echo $html;
}

function zib_yiyan($class = 'zib-yiyan', $before = '', $after = '')
{
    $yiyan = '<div class="' . $class . '">' . $before . '<div data-toggle="tooltip" data-original-title="点击切换一言" class="yiyan"></div>' . $after . '</div>';
    echo $yiyan;
}

function zib_posts_prevnext()
{
    $current_category = get_the_category();
    $prev_post = get_previous_post($current_category, '');
    $next_post = get_next_post($current_category, '');
    if (!empty($prev_post)) :
        $prev_title = $prev_post->post_title;
        $prev_link = 'href="' . get_permalink($prev_post->ID) . '"';
    else :
        $prev_title = '无更多文章';
        $prev_link = '';
    endif;
    if (!empty($next_post)) :
        $next_title = $next_post->post_title;
        $next_link = 'href="' . get_permalink($next_post->ID) . '"';
    else :
        $next_title = '无更多文章';
        $next_link = '';
    endif;
?>
    <div class="theme-box" style="height:99px">
        <nav class="article-nav">
            <div class="main-bg box-body radius8 main-shadow">
                <a <?php echo $prev_link; ?>>
                    <p class="muted-2-color">
                        << 上一篇</p>
                            <div class="text-ellipsis-2">
                                <?php echo $prev_title; ?>
                            </div>
                </a>
            </div>
            <div class="main-bg box-body radius8 main-shadow">
                <a <?php echo $next_link; ?>>
                    <p class="muted-2-color">下一篇 >></p>
                    <div class="text-ellipsis-2">
                        <?php echo $next_title; ?>
                    </div>
                </a>
            </div>
        </nav>

    </div>
<?php
}

function zib_posts_related($title = '相关阅读', $limit = 6)
{
    global $post;
    $exclude_id = $post->ID;
    $posttags = get_the_tags();
    $i = 0;
    $thumb_s = _pz('post_related_type') == 'img';

    echo '<div class="theme-box relates' . ($thumb_s ? ' relates-thumb' : '') . '">
		<div class="box-body notop">
			<div class="title-theme">' . $title . '</div>
			<div class="re-an"></div>
        </div>';

    echo '<div ' . ($thumb_s ? 'data-scroll="x" ' : '') . 'class="box-body main-bg radius8 main-shadow relates-content">';
    echo '<ul class="' . ($thumb_s ? 'scroll-x mini-scrollbar list-inline' : 'no-thumb') . '">';
    if ($posttags) {
        $tags = '';
        foreach ($posttags as $tag) $tags .= $tag->slug . ',';
        $args = array(
            'post_status'         => 'publish',
            'tag_slug__in'        => explode(',', $tags),
            'post__not_in'        => explode(',', $exclude_id),
            'ignore_sticky_posts' => 1,
            'orderby'             => 'comment_date',
            'posts_per_page'      => $limit
        );

        query_posts($args);
        while (have_posts()) {
            the_post();
            if (_pz('post_related_type') == 'list') {

                $_thumb = zib_post_thumbnail('', 'fit-cover radius8');
                $author = get_the_author();
                $title = get_the_title() . '<span class="focus-color">' . get_the_subtitle(false) . '</span>';
                $author = '<a href="' . get_author_posts_url(get_the_author_meta('ID')) . '">' . $author . '</a>';

                $lists_class = 'posts-mini';
                $title_l = '<div class="item-heading text-ellipsis-2">
                        <a' . _post_target_blank() . ' href="' . get_permalink() . '">' . $title . '</a>
                        </div>
                        ';
                $time_ago = zib_get_time_ago(get_the_time('U'));
                $meta_l = '<item class="meta-author">' . $author . '<span class="icon-spot">' . $time_ago . '</span></item>';

                echo '<div class="' . $lists_class . '">';
                echo '<a' . _post_target_blank() . ' class="item-thumbnail" href="' . get_permalink() . '">' . $_thumb . '</a>';
                echo '<div class="posts-mini-con">';
                echo $title_l;
                echo '<div class="item-meta muted-3-color">';
                echo $meta_l;
                echo '</div>';
                echo '</div>';
                echo '</div>';
            } else {
                if ($thumb_s) {
                    $title = get_the_title() . get_the_subtitle(false);
                    $time_ago = zib_get_time_ago(get_the_time('U'));
                    $info = '<item>' . $time_ago . '</item><item class="pull-right">' . zib_svg('view') . ' ' . get_post_view_count($before = '', $after = '') . '</item>';
                    $img = zib_post_thumbnail('', 'fit-cover', true);
                    $img = $img ? $img : zib_default_thumb();
                    echo '<li>';
                    $card = array(
                        'type' => 'style-3',
                        'class' => 'mb10',
                        'img' => $img,
                        'alt' => $title,
                        'link' => array(
                            'url' => get_permalink(),
                            'target' => '',
                        ),
                        'text1' => $title,
                        'text2' => zib_str_cut($title, 0, 45, '...'),
                        'text3' => $info,
                        'lazy' => true,
                        'height_scale' => 70,
                    );
                    zib_graphic_card($card, true);
                    echo '</li>';
                } else {
                    echo '<li><a class="icon-circle" href="' . get_permalink() . '">' . get_the_title() . get_the_subtitle() . '</a></li>';
                }
            }
            $i++;
            $exclude_id .= ',' . $post->ID;
        };
        wp_reset_query();
    }
    if ($i < $limit) {
        $cats = '';
        foreach (get_the_category() as $cat) $cats .= $cat->cat_ID . ',';
        $args = array(
            'category__in'        => explode(',', $cats),
            'post__not_in'        => explode(',', $exclude_id),
            'ignore_sticky_posts' => 1,
            'orderby'             => 'comment_date',
            'posts_per_page'      => $limit - $i
        );

        query_posts($args);
        while (have_posts()) {
            the_post();
            if (_pz('post_related_type') == 'list') {

                $_thumb = zib_post_thumbnail('', 'fit-cover radius8');
                $author = get_the_author();
                $title = get_the_title() . '<span class="focus-color">' . get_the_subtitle(false) . '</span>';
                $author = '<a href="' . get_author_posts_url(get_the_author_meta('ID')) . '">' . $author . '</a>';

                $lists_class = 'posts-mini';
                $title_l = '<div class="item-heading text-ellipsis-2">
                        <a' . _post_target_blank() . ' href="' . get_permalink() . '">' . $title . '</a>
                        </div>
                        ';
                $time_ago = zib_get_time_ago(get_the_time('U'));
                $meta_l = '<item class="meta-author">' . $author . '<span class="icon-spot">' . $time_ago . '</span></item>';

                echo '<div class="' . $lists_class . '">';
                echo '<a' . _post_target_blank() . ' class="item-thumbnail" href="' . get_permalink() . '">' . $_thumb . '</a>';
                echo '<div class="posts-mini-con">';
                echo $title_l;
                echo '<div class="item-meta muted-3-color">';
                echo $meta_l;
                echo '</div>';
                echo '</div>';
                echo '</div>';
            } else {
                if ($thumb_s) {
                    $title = get_the_title() . get_the_subtitle(false);
                    $time_ago = zib_get_time_ago(get_the_time('U'));
                    $info = '<item>' . $time_ago . '</item><item class="pull-right">' . zib_svg('view') . ' ' . get_post_view_count($before = '', $after = '') . '</item>';
                    $img = zib_post_thumbnail('', 'fit-cover', true);
                    $img = $img ? $img : zib_default_thumb();
                    echo '<li>';
                    $card = array(
                        'type' => 'style-3',
                        'class' => 'mb10',
                        'img' => $img,
                        'alt' => $title,
                        'link' => array(
                            'url' => get_permalink(),
                            'target' => '',
                        ),
                        'text1' => $title,
                        'text2' => zib_str_cut($title, 0, 45, '...'),
                        'text3' => $info,
                        'lazy' => true,
                        'height_scale' => 70,
                    );
                    zib_graphic_card($card, true);
                    echo '</li>';
                } else {
                    echo '<li><a class="icon-circle" href="' . get_permalink() . '">' . get_the_title() . get_the_subtitle() . '</a></li>';
                }
            }
            $i++;
        };
        wp_reset_query();
    }

    if ($i == 0) {
        echo '<li>暂无相关文章</li>';
    }
    echo '</ul></div></div>';
}

// 获取文章标签
function zib_get_posts_tags($class = 'but', $before = '', $after = '', $count = 0)
{
    global $post;
    $tags = get_the_tags($post->ID);
    return zib_get_tags($tags, $class, $before, $after, $count);
}

//数组按一个值从新排序
function arraySort($arrays, $sort_key, $sort_order = SORT_DESC, $sort_type = SORT_NUMERIC)
{
    if (is_array($arrays)) {
        foreach ($arrays as $array) {
            $key_arrays[] = $array->$sort_key;
        }
    } else {
        return false;
    }
    array_multisort($key_arrays, $sort_order, $sort_type, $arrays);
    return $arrays;
}

// 获取标签
function zib_get_tags($tags, $class = 'but', $before = '', $after = '', $count = 0, $ajax_replace = false)
{
    $html = '';
    if (!empty($tags[0])) {
        $ii = 0;
        $tags_s =  arraySort($tags, 'count');
        foreach ($tags_s as $tag_id) {
            $ii++;
            $url = get_tag_link($tag_id);
            $tag = get_tag($tag_id);
            $html .= '<a href="' . $url . '"' . ($ajax_replace ? ' ajax-replace="true"' : '') . ' title="查看此标签更多文章" class="' . $class . '">' . $before . $tag->name . $after . '</a>';
            if ($count && $count == $ii) {
                break;
            }
        }
    }
    return $html;
}

// 获取专题标签
function zib_get_topics_tags($pid = '', $class = 'but', $before = '', $after = '', $count = 0)
{
    if (!$pid) {
        global $post;
        $pid = $post->ID;
    }
    $category = get_the_terms($pid, 'topics');
    $cat = '';
    if (!empty($category[0])) {
        $ii = 0;
        foreach ($category as $category1) {
            $ii++;
            $cls = array('c-yellow', 'c-green', 'c-purple', 'c-red', 'c-blue', 'c-yellow', 'c-green', 'c-purple', 'c-red', 'c-blue', 'c-yellow', 'c-green', 'c-purple', 'c-red', 'c-blue', 'c-yellow', 'c-green', 'c-purple', 'c-red', 'c-blue', 'c-yellow', 'c-green', 'c-purple', 'c-red', 'c-blue', 'c-yellow', 'c-green', 'c-purple', 'c-red', 'c-blue', 'c-yellow', 'c-green', 'c-purple', 'c-red');
            $cat .=  '<a class="' . $class . ' ' . $cls[$ii - 1] . '" title="查看此专题更多文章" href="' . get_category_link($category1->term_id) . '">' . $before . $category1->name . $after . '</a>';
            if ($count && $ii == $count) break;
        }
    }
    return $cat;
}
// 获取分类标签
function zib_get_cat_tags($class = 'but', $before = '', $after = '', $count = 0)
{
    $category = get_the_category();
    $cat = '';
    if (!empty($category[0])) {
        $ii = 0;
        foreach ($category as $category1) {
            $ii++;
            $cls = array('c-blue', 'c-yellow', 'c-green', 'c-purple', 'c-red', 'c-blue', 'c-yellow', 'c-green', 'c-purple', 'c-red', 'c-blue', 'c-yellow', 'c-green', 'c-purple', 'c-red', 'c-blue', 'c-yellow', 'c-green', 'c-purple', 'c-red', 'c-blue', 'c-yellow', 'c-green', 'c-purple', 'c-red', 'c-blue', 'c-yellow', 'c-green', 'c-purple', 'c-red', 'c-blue', 'c-yellow', 'c-green', 'c-purple', 'c-red');
            if ($ii == 0) continue;
            $cat .=  '<a class="' . $class . ' ' . $cls[$ii - 1] . '" title="查看更多分类文章" href="' . get_category_link($category1->term_id) . '">' . $before . $category1->cat_name . $after . '</a>';
            if ($count && $ii == $count) break;
        }
    }
    return $cat;
}

// 获取文章meta标签
function zib_get_posts_meta($post_id = '')
{
    $meta = '';
    $comment_href = '';

    if (comments_open() && !_pz('close_comments')) {
        if (is_single()) {
            global $post;
            $pid = $post->ID;
            $object_id = get_queried_object_id();
            if ($object_id == $pid || ($post_id && $object_id == $post_id)) {
                $comment_href = 'javascript:(scrollTo(\'#comments\',-100));';
            }
        }
        if (!$comment_href) {
            $comment_href = get_comments_link();
        }
        $meta .= '<item class="meta-comm"><a data-toggle="tooltip" title="去评论" href="' . $comment_href . '">' . zib_svg('comment') . get_comments_number('0', '1', '%') . '</a></item>';
    }
    $meta .= '<item class="meta-view">' . zib_svg('view') . get_post_view_count($before = '', $after = '') . '</item>';
    $meta .= '<item class="meta-like">' . zib_svg('like') . (zib_get_post_like('', '', '', true) ? zib_get_post_like('', '', '', true) : '0') . '</item>';
    return $meta;
}

//打赏模态框
function zib_rewards_modal($user_ID = '')
{
    $weixin = get_user_meta($user_ID, 'rewards_wechat_image_id', true);
    $alipay = get_user_meta($user_ID, 'rewards_alipay_image_id', true);
    $rewards_title = get_user_meta($user_ID, 'rewards_title', true);
    $rewards_title = $rewards_title ? $rewards_title : '文章很赞！支持一下吧';
    $s_src = ZIB_STYLESHEET_DIRECTORY_URI . '/img/thumbnail-sm.svg';
    $weixin_img = '';
    $alipay_img = '';
    if ($weixin) {
        $weixin = wp_get_attachment_image_src($weixin, 'medium');
        $weixin_img = '<img class="lazyload fit-cover" src="' . $s_src . '" data-src="' . $weixin[0] . '">';
    }
    if ($alipay) {
        $alipay = wp_get_attachment_image_src($alipay, 'medium');
        $alipay_img = '<img class="lazyload fit-cover" src="' . $s_src . '" data-src="' . $alipay[0] . '">';
    }
    if (!$user_ID || !_pz('post_rewards_s') || (!$weixin && !$alipay)) return;
?>
    <div class="modal fade" id="rewards-popover" tabindex="-1">
        <div class="modal-dialog rewards-popover" style="max-width: 400px;margin: auto;" role="document">
            <div class="modal-content">
                <div class="box-body">
                    <i class="fa fa-heart c-red em12 ml10"></i>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i data-svg="close" data-class="ic-close" data-viewbox="0 0 1024 1024"></i></button>
                </div>
                <div class="box-body notop">
                    <div class="box-body box-body notop focus-color"><b><?php echo $rewards_title; ?></b></div>
                    <ul class="avatar-upload text-center theme-box list-inline rewards-box">
                        <?php if ($weixin) { ?>
                            <li>
                                <p class="muted-2-color">微信扫一扫</p>
                                <div class="rewards-img">
                                    <?php echo $weixin_img ?>
                                </div>
                            </li>
                        <?php } ?>
                        <?php if ($alipay) { ?>
                            <li>
                                <p class="muted-2-color">支付宝扫一扫</p>
                                <div class="rewards-img">
                                    <?php echo $alipay_img ?>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php }


function zib_single_cat_search($cat_id)
{
    $cat_obj = get_category($cat_id);
?>
    <div class="theme-box zib-widget dosc-search">
        <div class="title-h-left"><b>搜索<?php echo $cat_obj->cat_name ?></b></div>

        <?php
        $more_cats = array();
        $more_cats = get_term_children($cat_id, 'category');
        array_push($more_cats, $cat_id);
        $args = array(
            'class' => '',
            'show_keywords' => false,
            'show_input_cat' => true,
            'show_more_cat' => true,
            'placeholder' => '搜索' . $cat_obj->cat_name,
            'in_cat' => $cat_id,
            'more_cats' => $more_cats,
        );
        zib_get_search($args);
        ?>
    </div>
<?php
}

//保存热门关键词
function zib_update_search_keywords($s)
{
    $s = strip_tags($s);
    if (_pz('search_popular_key', true) && _new_strlen($s) >= 2 && _new_strlen($s) <= 8) {
        $keywords = get_option('search_keywords');
        if (!is_array($keywords)) $keywords = array();
        $max_num = (int) _pz('search_popular_key_num', 20);
        $keywords = array_slice($keywords, 0, $max_num, true);
        $keywords[$s] = !empty($keywords[$s]) ? (int) $keywords[$s] + 1 : 1;
        arsort($keywords);
        update_option('search_keywords', $keywords);
    }
}

//获取热门关键词
function zib_get_search_keywords()
{
    //置顶关键词
    $sticky = _pz('search_popular_sticky');
    $sticky = preg_split("/,|，|\s|\n/", $sticky);
    $keywords = get_option('search_keywords');
    if (!$keywords || !is_array($keywords)) $keywords = array();
    $sticky_a = array();
    foreach ($sticky as $key) {
        if (_new_strlen($key) < 2) continue;
        unset($keywords[$key]);
        $sticky_a[$key] = 999999;
    }
    $max_num = (int) _pz('search_popular_key_num', 20);
    $keywords = array_slice($keywords, 0, $max_num, true);
    return array_merge($sticky_a, $keywords);
}

//获取搜索历史关键词
function zib_get_search_history_keywords()
{
    $old_k = !empty($_COOKIE["history_search"]) ? json_decode(stripslashes($_COOKIE["history_search"])) : '';
    if (!is_array($old_k)) return false;
    return $old_k;
}
//获取搜索历史关键词
function zib_get_search_keywords_but($keywords = array(), $type = '')
{
    $k_i = 1;
    $keyword_link = '';
    //echo var_dump($keywords);
    if (!is_array($keywords)) return;
    foreach ($keywords as $key => $keyword) {
        $key = $type == 'history' ? $keyword : $key;
        if (_new_strlen($key) < 2) continue;
        $keyword_link .= '<a class="search_keywords muted-2-color but em09 mr6 mb6" href="' . esc_url(home_url('/')) . '?s=' . esc_attr($key) . '">' . esc_attr($key) . '</a>';
    }
    return $keyword_link;
}


//获取搜索框
function zib_get_search($args = array())
{
    $defaults = array(
        'class' => '',
        'show_keywords' => true,
        'show_history' => true,
        'keywords_title' => _pz('search_popular_title', '热门搜索'),
        'placeholder' => _pz('search_placeholder', '开启精彩搜索'),
        'show_input_cat' => true,
        'show_more_cat' => true,
        'show_posts' => false,
        'in_cat' => '',
        'more_cats' => array(),
    );

    $args = wp_parse_args((array) $args, $defaults);

    if (!_pz('search_popular_key', true)) $args['show_keywords'] = false;
    $all_cat = $args['show_more_cat'] ? zib_get_search_cat($args['more_cats'], 'text-ellipsis') : '';
?>
    <div class="search-input">
        <div class="padding-10">
            <form method="get" class="line-form" action="<?php echo esc_url(home_url('/')); ?>">
                <?php if ($args['show_input_cat']) {
                    $input_cat = '';
                    $in_cat_name = $args['in_cat'] ? get_category($args['in_cat'])->cat_name : '';
                    if ($in_cat_name || $all_cat) {
                        $input_cat_name =  $in_cat_name ? zib_str_cut($in_cat_name, 0, 5)  : '选择分类';
                        $input_cat_name = '<span name="cat">' . $input_cat_name . '</span>';
                        $input_cat_name .= $all_cat ? '<i class="fa ml6 fa-sort opacity5" aria-hidden="true"></i>' : '';
                        $input_cat .= $all_cat ? '<a href="javascript:;" class="padding-h10" data-toggle="dropdown">' . $input_cat_name . '</a>' : $input_cat_name;
                        $input_cat .= '<input type="hidden" name="cat" tabindex="1" value="' . ($args['in_cat'] ? $args['in_cat'] : '') . '">';
                    }
                    if ($all_cat) {
                        $input_cat .= $all_cat;
                        $input_cat = '<div class="dropdown">' . $input_cat . '</div>';
                    }
                    if ($input_cat) {
                        echo '<div class="search-input-cat option-dropdown splitters-this-r show-more-cat">';
                        echo $input_cat;
                        echo '</div>';
                    }
                ?>
                <?php } ?>
                <div class="search-input-text">
                    <input type="text" name="s" class="line-form-input" tabindex="2">
                    <div class="scale-placeholder"><?php echo $args['placeholder'] ?></div>
                </div>
                <div class="abs-right muted-color">
                    <button type="submit" tabindex="3" class="null"><?php echo zib_svg('search'); ?></button>
                </div>
                <i class="line-form-line"></i>
            </form>
        </div>
        <?php if ($args['show_keywords']) {
            $keywords = zib_get_search_keywords();
            $keyword_link = zib_get_search_keywords_but($keywords);
            if ($keyword_link) { //如果没有关键词，则不显示
        ?>
                <div class="search-keywords">
                    <p class="muted-color"><?php echo $args['keywords_title'] ?></p>
                    <div class="">
                        <?php echo $keyword_link; ?>
                    </div>
                </div>
        <?php }
        } ?>
        <?php
        if ($args['show_history']) {
            //历史关键词
            $keywords = zib_get_search_history_keywords();
            $keyword_link = zib_get_search_keywords_but($keywords, 'history');
            if ($keyword_link) { //如果没有关键词，则不显示
        ?>
                <div class="search-keywords history-search">
                    <p class="muted-color"><span>历史搜索</span><a class="pull-right trash-history-search muted-3-color" href="javascript:;"><i class="fa fa-trash-o em12" aria-hidden="true"></i></a></p>
                    <div>
                        <?php echo $keyword_link; ?>
                    </div>
                </div>
        <?php }
        } ?>
        <?php
        //热门文章
        if ($args['show_posts']) {
        ?>
            <div class="padding-10 relates relates-thumb">
                <div class="muted-color">热门文章</div>
                <div class="relates-content" data-scroll="x">
                    <ul class="scroll-x mini-scrollbar list-inline">
                        <?php zib_search_posts(); ?>
                    </ul>
                </div>
            </div>
        <?php } ?>
    </div>
<?php }

//搜索框热门文章
function zib_search_posts($count = 6, $orderby = 'views', $show_img = true)
{
    $args = array(
        'showposts' => $count,
        'ignore_sticky_posts' => 1
    );

    if ($orderby !== 'views') {
        $args['orderby'] = $orderby;
    } else {
        $args['orderby'] = 'meta_value_num';
        $args['meta_query'] = array(
            array(
                'key' => 'views',
                'order' => 'DESC'
            )
        );
    }

    $new_query = new WP_Query($args);
    while ($new_query->have_posts()) {
        $new_query->the_post();
        $title = get_the_title() . get_the_subtitle(false);
        if ($show_img) {
            //$author = get_the_author();
            $time_ago = zib_get_time_ago(get_the_time('U'));
            $info = '<item>' . $time_ago . '</item><item class="pull-right">' . zib_svg('view') . ' ' . get_post_view_count($before = '', $after = '') . '</item>';
            $img = zib_post_thumbnail('', 'fit-cover', true);
            $img = $img ? $img : zib_default_thumb();
            echo '<li class="padding-6">';
            $card = array(
                'type' => 'style-3',
                'class' => 'mb10',
                'img' => $img,
                'alt' => $title,
                'link' => array(
                    'url' => get_permalink(),
                    'target' => '',
                ),
                'text1' => $title,
                'text2' => zib_str_cut($title, 0, 45, '...'),
                'text3' => $info,
                'lazy' => true,
                'height_scale' => 70,
            );
            zib_graphic_card($card, true);
            echo '</li>';
        } else {
            echo '<li><a class="icon-circle text-ellipsis" href="' . get_permalink() . '">' . get_the_title() . get_the_subtitle() . '</a></li>';
        }
    };
    wp_reset_query();
    wp_reset_postdata();
}


/**
 * 搜索卡片
 */
function zib_get_search_cat($cat_ids = array(), $link_class = '', $before = '', $after = '', $shou_count = false)
{
    if (!$cat_ids) return false;
    $cats = get_categories(array(
        'include' => $cat_ids,
        'orderby' => 'include',
    ));
    if (!$cats) return false;
    $links = '';
    foreach ($cats as $cat) {
        $links .= '<li><a href="javascript:;" class="' . $link_class . '"  data-for="cat" data-value="' . $cat->cat_ID . '">' . zib_str_cut($cat->cat_name, 0, 8) . '</a></li>';
    }
    return $links ?  '<ul class="dropdown-menu">' . $links . '</ul>' : false;
}

// 链接列表盒子
function zib_links_box($links = array(), $type = 'card', $echo = true, $go_link = false)
{
    if (!$links) return false;

    $html = '';
    $card = '';
    $image = '';
    $simple = '';
    $i = 0;
    foreach ($links as $link) {
        $link = (array)$link;

        if (empty($link['href']) && !empty($link['link_url'])) {
            $link['href'] = $link['link_url'];
        }
        if (empty($link['title']) && !empty($link['link_name'])) {
            $link['title'] = $link['link_name'];
        }
        if (empty($link['src']) && !empty($link['link_image'])) {
            $link['src'] = $link['link_image'];
        }
        if (empty($link['desc']) && !empty($link['link_description'])) {
            $link['desc'] = $link['link_description'];
        }
        if (empty($link['blank']) && !empty($link['link_target'])) {
            $link['blank'] = $link['link_target'];
        }

        if (!empty($link['href']) && !empty($link['title'])) {
            $href = empty($link['href']) ? '' : esc_url($link['href']);

            if (!empty($link['go_link']) || $go_link) $href = go_link($href, true);

            $title = empty($link['title']) ? '' : esc_attr($link['title']);
            $src = empty($link['src']) ? '' : esc_attr($link['src']);

            $blank = empty($link['blank']) ? '' : ' target="_blank"';
            $dec = empty($link['desc']) ? '' : esc_attr($link['desc']);
            $img = '<img class="lazyload avatar" src="' . ZIB_STYLESHEET_DIRECTORY_URI . '/img/thumbnail-sm.svg" data-src="' . $src . '">';
            $data_dec = $dec ? ' title="' . $title . '" data-content="' . $dec . '" ' : ' data-content="' . $title . '"';
            $card .= '<div class="author-minicard links-card radius8">
                <ul class="list-inline">
                    <li><a ' . $blank . ' class="avatar-img link-img" href="' . $href . '">' . $img . '</a>
                    </li>
                    <li>
                        <dl>
                            <dt><a' . $blank . ' href="' . $href . '" title="' . $dec . '">' . $title . '</a></dt>
                            <dd class="avatar-dest em09 muted-3-color text-ellipsis">' . $dec . '</dd>
                        </dl>
                    </li>
                </ul>
            </div>';
            $image .= '<a ' . $blank . ' class="avatar-img link-only-img"  data-trigger="hover" data-toggle="popover" data-placement="top"' . $data_dec . ' href="' . $href . '">' . $img . '</a>';
            $sc = $i == 0 ? '' : 'icon-spot';
            $simple .= '<a ' . $blank . ' class="' . $sc . '" data-trigger="hover" data-toggle="popover" data-placement="top"' . $data_dec . ' href="' . $href . '">' . $title . '</a>';
            $i++;
        }
    }
    if ($type == 'card') {
        $html = $card;
    }
    if ($type == 'image') {
        $html = $image;
    }
    if ($type == 'simple') {
        $html = $simple;
    }

    if ($echo) {
        echo $html;
    } else {
        return $html;
    }
}


// 公告栏
function zib_notice($args = array(), $echo = true)
{
    $defaults = array(
        'class' => 'c-blue',
        'interval' => 5000,
        'notice' => array(),
    );

    $args = wp_parse_args((array) $args, $defaults);

    $interval = ' data-interval="' . $args['interval'] . '"';
    $i = 0;
    $slides = '';
    foreach ($args['notice'] as $notice) {
        if (!empty($notice['title'])) {
            $href = empty($notice['href']) ? '' : $notice['href'];
            $title = empty($notice['title']) ? '' : $notice['title'];
            $icon = empty($notice['icon']) ? '' : '<div class="relative bulletin-icon mr6"><i class="abs-center fa ' . $notice['icon'] . '"></i></div>';
            $blank = empty($notice['blank']) ? '' : ' target="_blank"';
            $s_class = ' notice-slide';
            $slides .= '<div class="swiper-slide' . ' ' . $s_class . '">
            <a class="text-ellipsis"' . $blank . ($href ? ' href="' . $href . '"' : '') . '>'
                . $icon . $title . '</a>
            </div>';
            $i++;
        }
    }

    $html = '<div class="new-swiper" ' . $interval . ' data-direction="vertical" data-loop="true" data-autoplay="1">
            <div class="swiper-wrapper">' . $slides . '</div>
            </div>';

    if ($echo) {
        echo '<div class="swiper-bulletin ' . $args['class'] . '">' . $html . '</div>';
    } else {
        return $html;
    }
}

// 弹出通知
function zib_system_notice()
{
    if (isset($_COOKIE["showed_system_notice"]) || !_pz('system_notice_s', true)) return;
    $args = array(
        'id' => 'modal-system-notice',
        'class' => _pz('system_notice_size', 'modal-sm'),
        'style' => '',
        'title' => _pz('system_notice_title'),
        'content' => _pz('system_notice_content'),
        'buttons' => _pz('system_notice_button'),
        'buttons_class' => 'but' . (_pz('system_notice_radius') ? ' radius' : ''),
    );
    zib_modal($args);
}
add_action('wp_footer', 'zib_system_notice', 10);

//模态框构建
function zib_modal($args = array())
{
    $defaults = array(
        'id' => '',
        'class' => '',
        'style' => '',
        'title' => '',
        'content' => '',
        'buttons' => array(),
        'buttons_class' => 'but',
    );

    $args = wp_parse_args((array) $args, $defaults);
    if (!$args['title'] && !$args['content']) return;
    $buttons = '';
    $args['buttons'] = (array)$args['buttons'];
    if (!empty($args['buttons'][0])) {
        foreach ($args['buttons'] as $but_args) {
            if (!empty($but_args['link']['url']) && !empty($but_args['link']['text'])) {
                $buttons_class = !empty($but_args['class']) ? ' ' . $but_args['class'] : '';
                $target = !empty($but_args['link']['target']) ? ' target=' . $but_args['link']['target'] : '';
                $buttons .= '<a type="button"' . $target . ' class="ml10 ' . $args['buttons_class'] . $buttons_class . '" href="' . $but_args['link']['url'] . '">' . $but_args['link']['text'] . '</a>';
            }
        }
    }
?>
    <div class="modal fade" id="<?php echo $args['id'] ?>" tabindex="-1" role="dialog">
        <div class="modal-dialog <?php echo $args['class'] ?>" <?php echo 'style="' . $args['style'] . '"' ?> role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button class="close" data-dismiss="modal">
                        <i data-svg="close" data-class="ic-close" data-viewbox="0 0 1024 1024"></i>
                    </button>
                    <h4><?php echo $args['title'] ?></h4>
                    <div><?php echo $args['content'] ?></div>
                </div>
                <?php if ($buttons) {
                    echo '<div class="box-body notop text-right">' . $buttons . '</div>';
                } ?>
            </div>
        </div>
    </div>
<?php
}


function zib_get_blank_modal_link($args = array())
{
    $defaults = array(
        'id' => 'blank_modal_' . mt_rand(100, 999),
        'link_class' => '',
        'remote' => '',
        'text' => '',
    );
    $args = wp_parse_args((array) $args, $defaults);

    $link = '<a class="' . esc_attr($args['link_class']) . '" href="javascript:;" data-toggle="modal" data-target="#' . esc_attr($args['id']) . '" data-remote="' . esc_url($args['remote']) . '">' . $args['text'] . '</a>';
    return $link . zib_get_blank_modal($args);
}

/**
 * @description: 空白模态框构建，适用于带AJAX的模态框
 * @param {*}
 * @return {*}
 */
function zib_get_blank_modal($args = array())
{
    $defaults = array(
        'id' => '',
        'class' => '',
        'style' => '',
        'title' => '',
        'content' => '<div class="modal-body"><div class="box-body"><p class="placeholder t1"></p> <h4 style="height:120px;" class="placeholder k1"></h4><p class="placeholder k2"></p><i class="placeholder s1"></i><i class="placeholder s1 ml10"></i></div></div>',
    );
    $args = wp_parse_args((array) $args, $defaults);

    $html = '';
    $html .= '<div class="modal fade" id="' . $args['id'] . '" tabindex="-1" role="dialog">';
    $html .= '<div class="modal-dialog ' . $args['class'] . '" style="' . $args['style'] . '" role="document">';
    $html .= '<div class="modal-content">';
    $html .= $args['content'];
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';

    return $html;
}


/**
 * @description: 万能-构建AJAX的tab内容
 * @param {*}
 * @return {*}
 */
function zib_get_ajax_tab($type = 'nav', $tabs = array(), $args = array())
{

    $example[] = array(
        'name' => '例子',
        'id' => 'posts-example',
        'ajax_url' => '',
        'action' => 'posts_example',
        'class' => 'example',
        'loader' => '',
    );
    $defaults = array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nav_class' => '',
        'loader' => zib_placeholder('posts-item') . zib_placeholder('posts-item'),
    );
    $args = wp_parse_args((array) $args, $defaults);

    $html = '';
    foreach ($tabs as $tab) {
        $action = !empty($tab['action']) ? $tab['action'] : '';
        $id = !empty($tab['id']) ? $tab['id'] : 'tab_' . $action;
        $name = !empty($tab['name']) ? $tab['name'] : '';
        $class = !empty($tab['class']) ? ' ' . $tab['class'] : '';
        $ajax_url = !empty($tab['ajax_url']) ? $tab['ajax_url'] : $args['ajax_url'];
        $loader = !empty($tab['loader']) ? $tab['loader'] : $args['loader'];

        if (!$action) continue;
        if ($type == 'nav') {
            $html .= '<li><a class="' . $args['nav_class'] . '" data-toggle="tab" data-ajax="" href="#' . $id . '">' . $name . '</a></li>';
        } else {
            $html .= '<div class="tab-pane fade ajaxpager' . $class . '" id="' . $id . '">';
            $html .= '<span class="post_ajax_trigger hide"><a ajax-href="' . esc_url(add_query_arg('action', $action, $ajax_url)) . '" class="ajax_load ajax-next ajax-open"></a></span>';
            $html .= '<div class="post_ajax_loader">' . $loader . '</div>';
            $html .= '</div>';
        }
    }
    return $html;
}


/**
 * @description: 上传文件的模态框构建
 * @param array $args
 * @param bool $echo
 * @return $html
 */
function zib_upload_modal($args = array(), $echo = true)
{
    $defaults = array(
        'id' => '', //必须
        'action' => 'img-upload',  //必须
        'class' => '',
        'style' => '',
        'before' => '<h4><i class="fa fa-cloud-upload em12 mr10 focus-color"></i></h4><h4>上传图片</h4><div class="muted-2-color">请选择上传图片，支持jpg/png/gif，大小不能超过' . _pz("up_max_size", '4') . 'M</div>',
        'after' => '',
        'action_url' => '',
        'button1_title' => '<i class="fa fa-cloud-upload mr10"></i>选择图片',
        'button1_class' => 'but padding-lg c-yellow',
        'button2_title' => '<i class="fa fa-check mr10"></i>确认上传',
        'button2_class' => 'but jb-blue padding-lg',
        'success' => '',
    );
    $args = wp_parse_args((array) $args, $defaults);

    $action = $args['action_url'] ? ' action="' . esc_url($args['action_url']) . '"' : '';
    $success = $args['success'] ? ' zibupload-success="' . esc_attr($args['success']) . '"' : '';
    $from = '<form' . $action . '>
        <div class="box-body">
            <div class="preview text-center"><img style="width: 100%;" src="' . ZIB_STYLESHEET_DIRECTORY_URI . '/img/thumbnail-sm.svg' . '"></div>
        </div>
        <div class="text-right">
        <label>
            <a class="' . $args['button1_class'] . '">' . $args['button1_title'] . '</a>
            <input class="hide" type="file" zibupload="image_upload" accept="image/gif,image/jpeg,image/jpg,image/png" name="image_upload" action="image_upload" multiple="false">
        </label>
            <button type="button" zibupload="submit"' . $success . ' class="' . $args['button2_class'] . '" name="submit">' . $args['button2_title'] . '</button>
            <input type="hidden" name="action" value="' . $args['action'] . '">
            ' . wp_nonce_field($args['action'], $args['action'] . '_nonce', false, false) . '
        </div>
</form>';


    $html = '<div class="modal modal-upload fade" id="' . $args['id'] . '" tabindex="-1" role="dialog">';
    $html .= '<div class="modal-dialog ' . $args['class'] . '" style="' . $args['style'] . '" role="document">';
    $html .= '<div class="modal-content"><div class="modal-body">';
    $html .= '<button class="close" data-dismiss="modal"><i data-svg="close" data-class="ic-close" data-viewbox="0 0 1024 1024"></i></button>';
    $html .= $args['before'];
    $html .= $from;
    $html .= $args['after'];
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';

    if ($echo) echo $html;
    return $html;
}


/**
 * @description: 获取AJAX分页按钮的函数
 * @param int $count_all  列表总数量
 * @param int $page  当前页码
 * @param int $ice_perpage  每页加载数量
 * @param int string $ajax_url 链接
 * @return {*}
 */
function zibpay_get_ajax_next_paging($count_all, $page = 1, $ice_perpage = 10, $ajax_url = '', $pag_class = 'text-center theme-pagination ajax-pag', $next_class = 'next-page ajax-next', $nex = '')
{
    $total_pages    = ceil($count_all / $ice_perpage);
    $con = '';
    if ($total_pages > $page) {
        $nex = $nex ? $nex : _pz("ajax_trigger", '加载更多');
        if (!$ajax_url) {
            $ajax_url = home_url(add_query_arg(null, null));
        }
        $href = esc_url(add_query_arg(array('paged' => $page + 1), $ajax_url));
        $con .= '<div class="' . $pag_class . '"><div class="' . $next_class . '">';
        $con .= '<a href="' . $href . '">' . $nex . '</a>';
        $con .= '</div></div>';
    }
    return $con;
}


function zib_social_login($echo = true)
{
    if (zib_is_close_sign()) return;
    $buttons = '';
    if (_pz('social') && function_exists('xh_social_loginbar')) {
        $buttons = xh_social_loginbar('', false);
    } else {
        $b_c = _pz('oauth_button_lg') ? ' button-lg' : '';
        $rurl = !empty($_GET['redirect_to']) ? $_GET['redirect_to'] : home_url(add_query_arg(null, null));
        $args = array();
        $args[] = array(
            'name' => 'QQ',
            'type' => 'qq',
            'class' => 'c-blue',
            'icon' => 'fa-qq',
        );
        $args[] = array(
            'name' => '微信',
            'type' => 'weixin',
            'class' => 'c-green',
            'icon' => 'fa-weixin',
        );
        $args[] = array(
            'name' => '微博',
            'type' => 'weibo',
            'class' => 'c-red',
            'icon' => 'fa-weibo',
        );

        $args[] = array(
            'name' => 'GitHub',
            'type' => 'github',
            'class' => '',
            'icon' => 'fa-github',
        );
        $args[] = array(
            'name' => '码云',
            'type' => 'gitee',
            'class' => '',
            'icon' => '',
        );

        $args[] = array(
            'name' => '百度',
            'type' => 'baidu',
            'class' => '',
            'icon' => 'baidu',
        );
        $args[] = array(
            'name' => '支付宝',
            'type' => 'alipay',
            'class' => 'c-blue',
            'icon' => 'alipay',
        );
        foreach ($args as $arg) {
            $type = $arg['type'];
            $name = $arg['name'];
            $icon = '<i class="fa ' . $arg['icon'] . '"></i>';
            if ($type == 'alipay') {
                $icon = zib_svg('alipay');
                if (wp_is_mobile()) continue; //移动端不显示支付宝
            }
            if ($type == 'baidu') $icon = zib_svg('baidu');
            if ($type == 'gitee') $icon = zib_svg('gitee');
            if (_pz('oauth_' . $type . '_s')) {
                $buttons .= '<a title="' . $name . '登录" href="' . esc_url(add_query_arg('rurl', $rurl, home_url('/oauth/' . $type))) . '" class="social-login-item ' . $type . ($b_c ? $b_c : ' toggle-radius') . '">' . $icon . ($b_c ? $name . '登录' : '') . '</a>';
            }
        }
    }
    if ($echo && $buttons) {
        echo '<p class="social-separator separator muted-3-color em09">社交帐号登录</p>';
        echo '<div class="social_loginbar">';
        echo $buttons;
        echo '</div>';
    } else {
        return $buttons;
    }
}

// 链接提交的卡片
function zib_submit_links_card($args = array())
{
    $defaults = array(
        'class' => '',
        'title' => '',
        'subtitle' => '',
        'dec' => '',
        'show_title' => true,
    );

    $args = wp_parse_args((array) $args, $defaults);

    $subtitle = $args['subtitle'];
    if ($subtitle) {
        $subtitle = '<small class="ml10">' . esc_attr($subtitle) . '</small>';
    }
    $title = $args['title'];
    if ($title) {
        $title = '<div class="box-body notop"><div class="title-theme">' . $title . $subtitle . '</div></div>';
    }

    $card = '<div class="zib-widget">';
    $card .= '<form class="form-horizontal mt10">';
    if ($args['dec']) {
        $card .= '<div class="form-group">
                <label class="col-sm-2 control-label c-red">提交说明</label>
                <div class="col-sm-9 mb10">
                    ' . $args['dec'] . '
                </div>
            </div>';
    }
    $card .= '<div class="form-group">
                <label for="link_name" class="col-sm-2 control-label">链接名称</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="link_name" name="link_name" placeholder="链接名称（必填）">
                </div>
            </div>';
    $card .= '<div class="form-group">
                <label for="link_url" class="col-sm-2 control-label">链接地址</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="link_url" name="link_url" placeholder="链接地址（必填）">
                </div>
            </div>';
    $card .= '<div class="form-group">
                <label for="link_description" class="col-sm-2 control-label">链接简介</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="link_description" name="link_description" placeholder="链接简介">
                </div>
            </div>';
    $card .= '<div class="form-group">
                <label for="link_image" class="col-sm-2 control-label">LOGO地址</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="link_image" name="link_image" placeholder="LOGO图像地址">
                </div>
            </div>';
    $card .= '<div class="form-group">
                <label class="col-sm-2"></label>
                <div class="col-sm-9">
                    <button class="but c-blue padding-lg wp-ajax-submit"><i class="fa fa-check" aria-hidden="true"></i>提交链接</button>
                </div>
            </div>';

    $card .= '<input type="hidden" name="action" value="frontend_links_submit">';
    $card .= '</form>';

    $card .= '</div>';

    $html = $title . $card;
    return $html;
}


//文章多重筛选代码
//通过pre_get_posts钩子筛选
add_action('pre_get_posts', 'zib_sift_posts_per_page', 999);
function zib_sift_posts_per_page($query)
{
    //is_category()即为分类页面有效，自行更换。
    //$query->is_main_query()使得仅对默认的页面主查询有效
    //!is_admin()避免影响后台文章列表

    if ((is_category() || is_tag() || is_home() || is_tax('topics')) && $query->is_main_query() && !is_admin()) {
        // 分类
        if (isset($_GET['cat'])) {
            $cat = $_GET['cat'];
            $query->set('cat', $cat);
        }
        //  标签
        if (isset($_GET['tag'])) {
            $tag = $_GET['tag'];
            $query->set('tag', $tag);
        }
        // 自定义分类法：taxonomy  topics
        if (isset($_GET['topics'])) {
            $array_temp = array(array('taxonomy' => 'topics', 'terms' => preg_split("/,|，|\s|\n/", $_GET['topics'])));
            $query->set('tax_query', $array_temp);
        }

        // 自定义字段：mate type
        if (isset($_GET['type'])) {
            $array_temp = array('key' => 'type', 'value' => $_GET['type'], 'compare' => '=');
        }
    }
}

//文章多重筛选代码
//通过pre_get_posts钩子筛选
add_action('pre_get_posts', 'zib_sift_posts_per_orde', 9999);
function zib_sift_posts_per_orde($query)
{
    //正反顺序
    if (isset($_GET['order']) && $query->is_main_query() && !is_admin()) {
        $order = $_GET['order'] == 'DESC' ? 'DESC' : 'ASC';
        $query->set('order', $order);
    }
    //按照什么排序
    if (isset($_GET['orderby']) && $query->is_main_query() && !is_admin()) {
        if (in_array($_GET['orderby'], array('views', 'favorite', 'like'))) {
            //如果用户设置了按文章特定字段价排序
            $orderby = 'meta_value_num';
            $meta_query['meta_query'] = array(
                array(
                    'key' => $_GET['orderby'],
                    'order' => 'DESC'
                )
            );
            $query->set('meta_query', $meta_query);
        } else {
            $orderby = $_GET['orderby']; //否则按wp自带排序字段
        }
        $query->set('orderby', $orderby);
    }
}


/**
 * @description: 编辑器按钮扩展
 * @param {*}
 * @return {*}
 */
function zib_get_input_expand_but($type = 'smilie', $upload = true)
{
    $but = '';
    $dropdown = '';

    //表情
    if ($type == 'smilie') {
        $but = '<a class="but input-smilie mr6" data-toggle="dropdown" href="javascript:;"><i class="fa fa-fw fa-smile-o"></i><span class="hide-sm">表情</span></a>';
        $smilie_icon_args = array('aoman', 'baiyan', 'bishi', 'bizui', 'cahan', 'ciya', 'dabing', 'daku', 'deyi', 'doge', 'fadai', 'fanu', 'fendou', 'ganga', 'guzhang', 'haixiu', 'hanxiao', 'zuohengheng', 'zhuakuang', 'zhouma', 'zhemo', 'zhayanjian', 'zaijian', 'yun', 'youhengheng', 'yiwen', 'yinxian', 'xu', 'xieyanxiao', 'xiaoku', 'xiaojiujie', 'xia', 'wunai', 'wozuimei', 'weixiao', 'weiqu', 'tuosai', 'tu', 'touxiao', 'tiaopi', 'shui', 'se', 'saorao', 'qiudale', 'se', 'qinqin', 'qiaoda', 'piezui', 'penxue', 'nanguo', 'liulei', 'liuhan', 'lenghan', 'leiben', 'kun', 'kuaikule', 'ku', 'koubi', 'kelian', 'keai', 'jingya', 'jingxi', 'jingkong', 'jie', 'huaixiao', 'haqian', 'aini', 'OK', 'qiang', 'quantou', 'shengli', 'woshou', 'gouyin', 'baoquan', 'aixin', 'bangbangtang', 'xiaoyanger', 'xigua', 'hexie', 'pijiu', 'lanqiu', 'juhua', 'hecai', 'haobang', 'caidao', 'baojin', 'chi', 'dan', 'kulou', 'shuai', 'shouqiang', 'yangtuo', 'youling');
        $smilie_icon = '';
        $img_url = ZIB_STYLESHEET_DIRECTORY_URI . '/img/smilies/';
        foreach ($smilie_icon_args as $smilie_i) {
            $smilie_icon .= '<a class="smilie-icon" href="javascript:;" data-smilie="' . $smilie_i . '"><img class="lazyload" data-src="' . $img_url . $smilie_i . '.gif" /></a>';
        }
        $dropdown = '<div class="dropdown-smilie scroll-y mini-scrollbar">' . $smilie_icon . '</div>';
    }
    if ($type == 'code') {
        $but = '<a class="but input-code mr6" href="javascript:;" data-toggle-class="open" data-target=".dropup.' . $type . '"><i class="fa fa-fw fa-code"></i><span class="hide-sm">代码</span></a>';

        $dropdown = '<div class="dropdown-code">';
        $dropdown .= '<p>请输入代码：</p>';
        $dropdown .= '<p><textarea rows="6" tabindex="1" class="form-control input-textarea" placeholder="在此处粘贴或输入代码"></textarea></p>';
        $dropdown .= '<div class="text-right"><a type="submit" class="but c-blue" href="javascript:;">确认</a></div>';
        $dropdown .= '</div>';
    }
    if ($type == 'image') {
        $but = '<a class="but input-image mr6" href="javascript:;" data-toggle-class="open" data-target=".dropup.' . $type . '"><i class="fa fa-fw fa-image"></i><span class="hide-sm">图片</span></a>';

        $dropdown = '<div class="tab-content">';

        //第一个tab|输入图片地址
        $dropdown .= '<div class="tab-pane fade in active dropdown-image" id="image-tab-1">';
        $dropdown .= '<p>请填写图片地址：</p>';
        $dropdown .= '<p><textarea rows="2" tabindex="1" class="form-control input-textarea" style="height:95px;" placeholder="http://..."></textarea></p>';
        $dropdown .= '<div class="text-right">';
        if ($upload) {
            $dropdown .= '<a class="but c-yellow mr10" data-toggle="tab" href="#image-tab-2" data-onclick="#input_image_upload">上传图片</a>';
        }
        $dropdown .= '<a type="submit" class="but c-blue" href="javascript:;">确认</a>';
        $dropdown .= '</div>';
        $dropdown .= '</div>';

        if ($upload) {
            //第二个tab|上传图片
            $dropdown .= '<div class="tab-pane fade dropdown-image" id="image-tab-2">';
            $dropdown .= '<p><a class="muted-color" data-toggle="tab" href="#image-tab-1"><i class="fa fa-angle-left mr6"></i>填写图片地址</a></p>';

            $from = '<div class="form-upload">
                        <label style="width:100%;" class="hover-show pointer">
                            <div class="hover-show-con abs-center text-center"><i style="margin-top: -.5em;" class="fa fa-plus-circle fa-3x opacity8"></i></div>
                            <div class="preview text-center mb6"><img style="width:100%;height:96px;object-fit:cover;" src="' . ZIB_STYLESHEET_DIRECTORY_URI . '/img/thumbnail-lg.svg' . '"></div>
                            <input class="hide" type="file" id="input_image_upload" zibupload="image_upload" accept="image/gif,image/jpeg,image/jpg,image/png" name="image_upload" action="image_upload" multiple="false">
                        </label>
                        <div class="text-right">
                            <button type="button" zibupload="submit" auto-submit="true" zibupload-success="expand_upload_img" class="but jb-blue" name="submit">确认上传</button>
                            <input type="hidden" data-name="action" data-value="user_upload_image">
                            ' . wp_nonce_field('upload_image', 'upload_image_nonce', false, false) . '
                        </div>
                </div>';

            $dropdown .= $from;

            $dropdown .= '</div>';
        }


        $dropdown .= '</div>';
    }

    $con = $but . '<div class="dropdown-menu">' . $dropdown . '</div>';

    return '<span class="dropup relative ' . $type . '">' .  $con . '</span>';
}

/**
 * @description: 图文卡片
 * @param {*}
 * @return {*}
 */
function zib_graphic_card($args = array(), $echo = false)
{
    $defaults = array(
        'type' => '',
        'class' => 'mb20',
        'img' => '',
        'alt' => '',
        'link' => array(
            'url' => '',
            'target' => '',
        ),
        'text' => '',
        'text1' => '',
        'text2' => '',
        'text3' => '',
        'lazy' => true,
        'height_scale' => 0,
        'mask_opacity' => 0,
    );

    $args = wp_parse_args((array) $args, $defaults);
    if (!$args['img']) return;
    $args['class'] .= ' ' . $args['type'];

    $lazy = $args['lazy'];
    $lazy_src = ZIB_STYLESHEET_DIRECTORY_URI . '/img/thumbnail.svg';
    $img = '<img class="lazyload fit-cover" ' . ($lazy ? ' alt="' . $args['alt'] . '" data-src="' . $args['img'] . '" src="' . $lazy_src . '"' : ' src="' . $args['img'] . '"') . '>';
    $mask = $args['mask_opacity'] ? '<div class="absolute graphic-mask" style="opacity: ' . ((int)$args['mask_opacity'] / 100) . ';"></div>' : '';
    $text = '';
    if ($args['type'] == 'style-2') {
        $text = '<div class="abs-center conter-conter graphic-text">';
        $text .= '<div class="title-h-center"><b>' . $args['text1'] . '</b></div>';
        $text .= '<div class="em09 opacity8">' . $args['text2'] . '</div>';
        $text .= '</div>';

        $text .= '<div class="abs-center right-top">';
        $text .= '<badge class="b-black opacity8">' . $args['text3'] . '</badge>';
        $text .= '</div>';
    } elseif ($args['type'] == 'style-3') {
        $text = '<div class="abs-center left-bottom graphic-text text-ellipsis">';
        $text .=  $args['text1'];
        $text .= '</div>';
        $text .= '<div class="abs-center left-bottom graphic-text">';
        $text .= '<div class="em09 opacity8">' . $args['text2'] . '</div>';
        $text .= $args['text3'] ? '<div class="px12 opacity8 mt6">' . $args['text3'] . '</div>' : '';
        $text .= '</div>';
    } else {
        $text = '<div class="abs-center left-bottom graphic-text">';
        $text .= '<div class="title-h-left"><b>' . $args['text1'] . '</b></div>';
        $text .= '<div class="em09 opacity8">' . $args['text3'] . '</div>';
        $text .= '<div class="em09">' . $args['text2'] . '</div>';
        $text .= '</div>';
    }

    $height_scale = $args['height_scale'] ? ' style="padding-bottom: ' . (int)$args['height_scale'] . '%!important;"' : '';

    $html = $args['link']['url'] ? '<a' . ($args['link']['target'] ? ' target="' . $args['link']['target'] . '"' : '') . ' href="' . $args['link']['url'] . '">' : '';
    $html .= '<div class="graphic ' . $args['class'] . '"' . $height_scale . '>';
    $html .= $img;
    $html .= $mask;
    $html .= $text;
    $html .= '';
    $html .= '</div>';
    $html .= $args['link']['url'] ? '</a>' : '';

    if ($echo) {
        echo $html;
    } else {
        return $html;
    }
}
