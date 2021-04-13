<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-09-29 13:18:38
 * @LastEditTime: 2020-12-24 12:27:09
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

function zib_posts_list($args = array(), $new_query = false)
{

    $defaults = array(
        'type' => 'auto',
        'no_author' => false,
        'no_margin' => false,
        'is_mult_thumb' => false,
        'is_no_thumb' => false,
        'is_card' => false,
        'is_category' => is_category(),
        'is_search' => is_search(),
        'is_home' => is_home(),
        'is_author' => is_author(),
        'is_tag' => is_tag(),
        'is_topics' => is_tax('topics'),
    );
    if (_pz('list_show_type', 'no_margin') == 'no_margin') {
        $defaults['no_margin'] = true;
    }

    $args = wp_parse_args((array) $args, $defaults);

    if ($new_query) {
        while ($new_query->have_posts()) : $new_query->the_post();
            zib_mian_posts_while($args);
        endwhile;
    } else {
        while (have_posts()) : the_post();
            zib_mian_posts_while($args);
        endwhile;
    }
    wp_reset_query();
    wp_reset_postdata();
}

function zib_mian_posts_while($args = array())
{

    $defaults = array(
        'type' => 'auto',
        'no_author' => false,
        'no_margin' => false,
        'is_mult_thumb' => false,
        'is_no_thumb' => false,
        'is_card' => false,
        'is_category' => false,
        'is_search' => false,
        'is_home' => false,
        'is_author' => false,
        'is_tag' => false,
        'is_topics' => false,
    );

    $args = wp_parse_args((array) $args, $defaults);

    $is_mult_thumb = $args['is_mult_thumb'];
    $is_no_thumb = $args['is_no_thumb'];
    $is_card = $args['is_card'];
    $is_no_margin = $args['no_margin'];

    $is_category = $args['is_category'];
    $is_search = $args['is_search'];
    $is_home = $args['is_home'];
    $is_author = $args['is_author'];
    $is_tag = $args['is_tag'];
    $is_topics = $args['is_topics'];
    if ($args['type'] == 'card') {
        $is_card = true;
    } elseif ($args['type'] == 'mult_thumb') {
        $is_mult_thumb = true;
    } elseif ($args['type'] == 'no_thumb') {
        $is_no_thumb = true;
    }

    global $post;

    //准备必要参数
    $get_permalink = get_permalink();
    $_post_target_blank = _post_target_blank();
    $_thumb_count = zib_get_post_img('', '', '', true);
    $is_show_sidebar = zib_is_show_sidebar();

    $_thumb = zib_post_thumbnail('', 'fit-cover radius8');
    //"数据库查询": 104,"执行时间": 46.91791534423828

    if (has_post_format(array('image'))) {
        $_thumb .= '<div class="abs-right radius"><i class="fa fa-image"></i> ' . $_thumb_count . '</div>';
    }
    if (has_post_format(array('video'))) {
        $_thumb .= '<div class="abs-right radius"><i class="fa fa-play-circle" aria-hidden="true"></i></div>';
    }

    //"数据库查询": 371,执行时间": 814.8789405822754
    $author = get_the_author();
    $title = get_the_title() . '<span class="focus-color">' . get_the_subtitle(false) . '</span>';
    $posts_meta = zib_get_posts_meta();
    //"数据库查询": 371,执行时间": 964.8789405822754

    $fl_card = (array)_pz('list_card_cat');

    $cat_ID = get_queried_object_id();
    $list_type  = _pz('list_type');
    if ($list_type == 'card') {
        $is_card = true;
    } elseif (($is_tag && _pz('list_card_tag')) || ($is_home && _pz('list_card_home')) || ($is_author && _pz('list_card_author')) || ($is_topics && _pz('list_card_topics'))) {
        $is_card = true;
    } elseif ($fl_card && $cat_ID && in_array($cat_ID, $fl_card)) {
        $is_card = true;
    }

    //  echo json_encode($fl_card);

    if ($is_show_sidebar) {
        if (!$is_card && $_thumb_count > 2) {
            if (has_post_format(array('image', 'gallery'))) {
                $is_mult_thumb = true;
            }
            if (!$is_mult_thumb) {
                $category = get_the_category();
                $mult_thumb_cat = _pz('mult_thumb_cat');
                if (!empty($category[0]) && $mult_thumb_cat) {
                    foreach ($category as $category1) {
                        if (in_array($category1->term_id, (array)$mult_thumb_cat)) {
                            $is_mult_thumb = true;
                            break;
                        }
                    }
                }
            }
        } else {
            $is_mult_thumb = false;
        }

        if (!$is_no_thumb && (($list_type == 'text' || ($list_type == 'thumb_if_has' && strstr($_thumb, 'data-thumb="default"'))) && !$is_card)) {
            $is_no_thumb = true;
        }
    } else {
        $is_no_thumb = false;
        $is_mult_thumb = false;
    }

    $lists_class = 'posts-item ajax-item';
    $lists_class .= ($is_no_margin && !$is_card) ? ' no_margin' : ' main-shadow radius8';

    $lists_class .= $is_mult_thumb ? ' mult-thumb' : '';
    $lists_class .= ($is_no_thumb && !$is_mult_thumb) ? ' no-thumb' : '';
    $lists_class .= $is_card ? ' card' : '';

    $cat = '';

    /** 付费金额 */
    $posts_pay = get_post_meta($post->ID, 'posts_zibpay', true);
    $pay_mate = '';
    $mark = _pz('pay_mark', '￥');
    //"数据库查询": 371,执行时间": 964.8789405822754

    if (!empty($posts_pay['pay_type']) && $posts_pay['pay_type'] != 'no') {
        $order_type = zibpay_get_pay_type_name($posts_pay['pay_type']);
        $pay = $posts_pay['pay_price'] ? '<span class="em09">' . $mark . '</span>' . $posts_pay['pay_price'] : '';
        $pay_mate = '<a href="' . $get_permalink . '#posts-pay" class="meta-pay but jb-yellow"><span class="mr3">' . $order_type . '</span>' . $pay . '</a>';

        if ($posts_pay['pay_price']) {
            $pay_mate = $pay_mate;
            $vip_price_con = '';
            for ($vi = 1; $vi <= 2; $vi++) {
                if ($vi == 2 && empty($posts_pay['vip_1_price'])) continue;
                if (_pz('pay_user_vip_' . $vi . '_s', true)) {
                    $pay = !empty($posts_pay['vip_' . $vi . '_price']) ? '<span class="em09">' . $mark . '</span>' . round($posts_pay['vip_' . $vi . '_price'], 2) : '免费';
                    $pay_mate .= '<a href="' . $get_permalink . '#posts-pay" class="meta-pay but jb-vip' . $vi . '"><span class="mr3">' . zibpay_get_vip_icon($vi, "", 1) . '</span>' . $pay . '</a>';
                }
            }
        } else {
            $pay_mate = '<a href="' . $get_permalink . '#posts-pay" class="meta-pay but jb-yellow">免费资源</a>';
        }

        $cat .= $pay_mate;
    }

    if (is_sticky()) {
        $cat .=  '<a class="but c-red but-ripple"><i class="fa fa-heart-o" aria-hidden="true"></i>置顶推荐</a>';
    }
    $cat_count = 1;
    if ($is_tag || $is_no_thumb || $is_mult_thumb) {
        $cat_count = 3;
    }
    if (!$is_category) {
        $cat .= zib_get_cat_tags('but', '<i class="fa fa-folder-open-o" aria-hidden="true"></i>', '', $cat_count);
    };
    if (!is_tax('topics')) {
        $cat .= zib_get_topics_tags($post->ID, 'but', '<i class="fa fa-cube" aria-hidden="true"></i>', '', 3);
    };
    $cat .=  zib_get_posts_tags('but', '# ', '', 3);

    if (_pz('list_orderby') == 'modified') {
        $time_ago = zib_get_time_ago(get_the_modified_time('U'));
    } else {
        $time_ago = zib_get_time_ago(get_the_time('U'));
    }

    if (_pz('post_list_author') && !$is_card) {
        $author = '<a class="avatar-mini" href="' . get_author_posts_url(get_the_author_meta('ID')) . '">' . zib_get_data_avatar(get_the_author_meta('ID')) . $author . '</a>';
    } else {
        $author = '';
    }
    if ($args['no_author']) {
        $author = '';
    }
    $meta_l = '<item class="meta-author">' . $author . '<span class="icon-circle">' . $time_ago . '</span></item>';
    $title_l = '<h2 class="item-heading text-ellipsis' . ($is_card ? '-2' : '') . '"><a' . $_post_target_blank . ' href="' . $get_permalink . '">' . $title . '</a></h2>';

    //开始输出
    echo '<posts class="' . $lists_class . '">';
    if (!$is_card) {
        echo $title_l;
    }
    if (!$is_no_thumb && !$is_mult_thumb) {
        if (has_post_format(array('gallery')) && $_thumb_count > 1 && _pz('list_thumb_slides_s')) {
            $_thumb_all = zib_get_post_img('', '', 0, false, true);
            $slides_args = array(
                'class'   => 'item-thumbnail',
                'button'   => false,
                'pagination'   => false,
                'echo' => false,
            );

            foreach ($_thumb_all as $src) {
                $slide = array(
                    'background'  => $src,
                    'href'  => $get_permalink,
                    'blank'  => $_post_target_blank
                );
                $slides_args['slides'][] = $slide;
            }
            echo zib_new_slider($slides_args);
        } else {
            echo    '<a' . $_post_target_blank . ' class="item-thumbnail" href="' . $get_permalink . '">' . $_thumb . '</a>';
        }
    }

    echo '<div class="' . ($is_no_thumb || $is_mult_thumb ? '' : 'item-body') . '">';
    if ($is_card) {
        echo $title_l;
    } else {
        if ($is_mult_thumb) {
            $_thumb_x4 = zib_posts_multi_thumbnail('', $class = 'fit-cover radius8');
            echo '<a' . $_post_target_blank . ' class="thumb-items" href="' . $get_permalink . '">' . $_thumb_x4 . '</a>';
        } else {
            echo '<p class="item-excerpt muted-color text-ellipsis-2">' . zib_get_excerpt() . '</p>';
        }
    }
    echo '<p class="item-tags scroll-x no-scrollbar">' . $cat . '</p>';
    echo '<div class="item-meta muted-3-color">';
    echo $meta_l;
    echo '<div class="meta-right pull-right">';
    echo $posts_meta;
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</posts>';
}




function zib_posts_mini_list($args = array(), $new_query = false)
{

    $defaults = array(
        'type' => 'auto',
        'no_author' => false,
        'no_margin' => false,
        'is_mult_thumb' => false,
        'is_no_thumb' => false,
        'is_card' => false,
        'is_category' => is_category(),
        'is_search' => is_search(),
        'is_home' => is_home(),
        'is_author' => is_author(),
        'is_tag' => is_tag(),
    );

    if (_pz('list_show_type', 'no_margin') == 'no_margin') {
        $defaults['no_margin'] = true;
    }
    $args = wp_parse_args((array) $args, $defaults);
    $number = 0;
    if ($new_query) {
        while ($new_query->have_posts()) : $new_query->the_post();
            $number++;
            zib_posts_mini_while($args, $number);
        endwhile;
    } else {
        while (have_posts()) : the_post();
            zib_posts_mini_while($args);
        endwhile;
    }
    wp_reset_query();
    wp_reset_postdata();
}
function zib_posts_mini_while($args = array(), $number)
{
    $defaults = array(
        'show_thumb' => true,
        'show_meta' => true,
        'show_number' => true,
    );

    $args = wp_parse_args((array) $args, $defaults);

    global $post;

    $_thumb = zib_post_thumbnail('', 'fit-cover radius8');
    $author = get_the_author();
    $title = '<a ' . _post_target_blank() . ' href="' . get_permalink() . '">' . get_the_title() . '<span class="focus-color">' . get_the_subtitle(false) . '</span></a>';
    $author = '<a href="' . get_author_posts_url(get_the_author_meta('ID')) . '">' . $author . '</a>';
    if ($args['show_number']) {
        $cls = array('c-red', 'c-yellow', 'c-purple', 'c-blue', 'c-green');
        $title = '<span class="badg badg-sm mr3 ' . (!empty($cls[$number - 1]) ? $cls[$number - 1] : '') . '">' . $number . '</span>' . $title;
    }
    $lists_class = 'posts-mini';
    $title_l = '<h2 class="item-heading' . ($args['show_thumb'] ? ' text-ellipsis-2' : ' text-ellipsis') . '">' . $title . '</h2>';
    $time_ago = zib_get_time_ago(get_the_time('U'));
    $meta_l = '<item class="meta-author">' . $author . '<span class="icon-spot">' . $time_ago . '</span></item>';

    /** 付费金额 */
    $posts_pay = get_post_meta($post->ID, 'posts_zibpay', true);
    $pay_mate = '';
    $mark = _pz('pay_mark', '￥');

    if (!empty($posts_pay['pay_type']) && $posts_pay['pay_type'] != 'no') {
        $order_type = zibpay_get_pay_type_name($posts_pay['pay_type']);
        $pay = $posts_pay['pay_price'] ? '<span class="em09">' . $mark . '</span>' . $posts_pay['pay_price'] : '免费';
        $pay_mate = '<item class="meta-pay badg badg-sm ml6 c-yellow" data-toggle="tooltip" title="' . $order_type . '">' . $pay . '</item>';
        $meta_l .= $pay_mate;
    }

    echo '<div class="' . $lists_class . '">';

    if ($args['show_thumb']) {
        echo '<a' . _post_target_blank() . ' class="item-thumbnail" href="' . get_permalink() . '">' . $_thumb . '</a>';
    }
    echo '<div class="posts-mini-con">';
    echo $title_l;
    if ($args['show_meta']) {
        echo '<div class="item-meta muted-3-color">';
        echo $meta_l;
        echo '</div>';
    }
    echo '</div>';
    echo '</div>';
}

/**
 * 分页函数
 */
function zib_paging($ajax = true)
{
    $p = 2;
    if (is_singular()) return;
    global $wp_query, $paged;
    $max_page = $wp_query->max_num_pages;
    $nex = _pz("ajax_trigger", '加载更多');
    if ($max_page == 1) return;
    $ajax = _pz('paging_ajax_s', true);
    $next_posts_link = get_next_posts_link();
    if ($ajax) {
        //ias自动加载
        $ias = _pz('paging_ajax_ias_s', true) ? ' class="next-page ajax-next lazyload" lazyload-action="ias"' : '  class="next-page ajax-next"';
        echo  $next_posts_link ? '<div class="text-center theme-pagination ajax-pag"><div' . $ias . '>' . get_next_posts_link($nex) . '</div></div>' : '';
        return;
    }
    if ($max_page && $max_page > 1) {
        echo '<ul class="text-center theme-pagination noajax-pag">';
        if (empty($paged)) $paged = 1;
        if (get_previous_posts_link()) {
            echo '<li class="prev-page" title="上一页">' . get_previous_posts_link('<i class="fa fa-angle-double-left" aria-hidden="true"></i>') . '</li>';
        }
        if ($paged > $p + 1) zib_paging_link(1);
        if ($paged > $p + 2) echo "<li><span>···</span></li>";
        for ($i = $paged - $p; $i <= $paged + $p; $i++) {
            if ($i > 0 && $i <= $max_page) $i == $paged ? print "<li class=\"active\"><span>{$i}</span></li>" : zib_paging_link($i);
        }
        if ($paged < $max_page - $p - 1) echo '<li><span><i class="fa fa-ellipsis-h" aria-hidden="true"></i></span></li>';
        if ($paged < $max_page - $p) zib_paging_link($max_page);

        if ($next_posts_link) {
            echo '<li class="next-page" title="下一页">' . get_next_posts_link('<i class="fa fa-angle-double-right" aria-hidden="true"></i>') . '</li>';
        }
        echo '</ul>';
    }
}

function zib_paging_link($i, $title = '')
{
    if (!$title) $title = "第 {$i} 页";
    echo "<li><a title='{$title}' href='" . esc_html(get_pagenum_link($i)) . "'>{$i}</a></li>";
}

/**
 * @description: 简单的骨架屏幕构架
 * @param {*}
 * @return {*}
 */
function zib_placeholder($class = 'posts-item main-shadow radius8')
{
    return '<div class="' . $class . '"><i class="radius8 item-thumbnail placeholder"></i> <div class="item-body"> <p class="placeholder t1"></p> <h4 class="item-excerpt placeholder k1"></h4><p class="placeholder k2"></p><i class="placeholder s1"></i><i class="placeholder s1 ml10"></i></div></div>';
}
