<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-09-29 13:18:38
 * @LastEditTime: 2020-12-25 13:44:12
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

function zib_breadcrumbs()
{
    if (!is_single()) return false;
    if (!_pz('breadcrumbs_single_s', true)) return false;
    $categorys = get_the_category();
    if ($categorys) {
        $category = $categorys[0];
        $lin =  '<ul class="breadcrumb">
		<li><a href="' . get_bloginfo('url') . '"><i class="fa fa-map-marker"></i> ' . (!_pz('breadcrumbs_home_text', true) ? get_bloginfo('name') : '首页') . '</a></li><li>
		' . get_category_parents($category->term_id, true, ' </li><li> ') . (!_pz('breadcrumbs_single_text', true) ? get_the_title() : '正文') . '</li></ul>';
        return $lin;
    } else {
        return false;
    }
}

function zib_single_cover()
{
    $breadcrumbs = zib_breadcrumbs();
    $breadcrumbs = tb_xzh_post_imgs(99) ? '' : $breadcrumbs;
    $imgs = zib_get_post_img('full', '', 0, false, true);
    $title = get_the_title() . get_the_subtitle();
    $metas = '<div class="article-meta abs-right radius">' . zib_get_posts_meta() . '</div>';

    //echo json_encode($imgs);
    if (has_post_format(array('image', 'gallery'))) {
        $img = zib_post_thumbnail('full', 'fit-cover', true);
        if (has_post_format(array('gallery')) && count($imgs) > 1 && _pz('article_slide_cover', true)) {

            $defaults = array(
                'class'   => 'mb20',
            );
            $args = wp_parse_args((array) _pz('article_slide_cover_option'), $defaults);

            foreach ($imgs as $src) {
                $slide = array(
                    'background'  => $src
                );
                $args['slides'][] = $slide;
            }
            echo '<div class="main-shadow page-cover article-cover-slide">';
            zib_new_slider($args);
            echo '<div class="box-body page-cover-con">';
            echo '<div class="title-h-left">';
            echo '<b>' . $title . '</b>';
            echo '</div>';
            echo '<div class="em09 page-desc">' . $breadcrumbs . '</div>';
            echo '</div>';
            echo $metas;
            echo '</div>';
        } else {
            zib_page_cover($title, $img, $breadcrumbs, $metas);
        }
    } else {
        echo $breadcrumbs;
    }
}
function zib_single_header()
{
    $user_id = get_the_author_meta('ID');
    $user_img = zib_get_data_avatar($user_id);
    $title = get_the_title() . get_the_subtitle();
    $author = get_the_author();
    $vip_icon = '';
    if ($user_id) {
        $vip_icon = zibpay_get_vip_icon(zib_get_user_vip_level($user_id), "");
        $vip_icon = $vip_icon ? '<div class="avatar-icontag">' . $vip_icon . '</div>' : '';
    }

    $time_up = zib_get_time_ago(get_the_modified_time('U'));
    $time = zib_get_time_ago(get_the_time('U'));
    $category = get_the_category();
    $posts_meta = zib_get_posts_meta();
    $is_show_cover = has_post_format(array('image', 'gallery'));
    if (!empty($category[0])) {
        $category = '<span class="icon-circle">' . $category[0]->cat_name . '</span>';
    }
    if ((get_the_modified_time('Y') * 365 + get_the_modified_time('z')) > (get_the_time('Y') * 365 + get_the_time('z'))) {
        $time_html = '<span data-toggle="tooltip" data-placement="bottom" title="' . get_the_time('Y年m月d日 H:i') . '发布">' . $time_up . '更新</span>';
    } else {
        $time_html = '<span data-toggle="tooltip" data-placement="bottom" title="' . get_the_time('Y年m月d日 H:i') . '发布">' . $time . '发布</span>';
    }
?>
    <div class="article-header theme-box clearfix">
        <?php if (!$is_show_cover) { ?>
            <h1 class="article-title">
                <a href="<?php the_permalink() ?>"><?php echo $title; ?></a>
            </h1>
        <?php } ?>
        <div class="article-avatar">
            <ul class="list-inline">
                <li>
                    <div class="avatar-img"><?php echo $user_img . $vip_icon; ?></div>
                </li>
                <li>
                    <dl>
                        <dt class="avatar-name"> <a href="<?php echo get_author_posts_url($user_id); ?>">
                                <?php echo $author; ?>
                            </a></dt>
                        <dd class="meta-time px12 muted-2-color"><?php echo $time_html; ?></dd>
                    </dl>
                </li>
                <li class="avatar-button">
                    <?php echo zib_get_user_follow('btn ml6 but c-red', $user_id); ?>
                    <?php
                    $Private_but = Zib_Private::get_but($user_id, '<i><i class="fa fa-envelope-o mr6"></i></i>私信', 'ml6 but c-blue');
                    if ($Private_but) {
                        echo $Private_but;
                    } else {
                        echo zib_get_rewards_button($user_id, 'ml6 but c-blue');
                    }
                    ?>
                </li>
            </ul>
            <div class="relative">
                <i class="line-form-line"></i>
                <?php if (!$is_show_cover) { ?>
                    <div class="article-meta abs-right muted-color radius">
                        <?php echo $posts_meta; ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php }

function zib_is_show_posts_nav()
{
    global $post;
    $show_nav = get_post_meta($post->ID, "no_article-navs", true);
    if (_pz('article_nav') && !($show_nav)) {
        return true;
    }
    return false;
}

function zib_single_content()
{
    global $post;
    $show_nav = zib_is_show_posts_nav();
    $is_max_height = get_post_meta($post->ID, "article_maxheight_xz", true);
    $max_height_style = '';
    $max_height_class = '';
    $show_nav_data = '';
    if ($show_nav) {
        $show_nav_data .= 'data-nav="posts"';
    }
    if (_pz('article_maxheight_kg') || $is_max_height) {
        $max_height_class .= ' limit-height';
        $max_height_style = ' style="max-height:' . (_pz('article_maxheight') + 80) . 'px;" data-maxheight="' . _pz('article_maxheight') . '"';
    }
?>
    <div class="article-content<?php echo $max_height_class; ?>" <?php echo $max_height_style; ?>>
        <?php zib_single_content_header(); ?>
        <?php echo _pz('post_front_content'); ?>
        <div <?php echo $show_nav_data; ?>class="theme-box wp-posts-content">
            <?php

            do_action('zib_posts_content_before', $post); //添加钩子
            the_content();
            //文章分页
            wp_link_pages(
                array(
                    'before'           => '<p class="text-center post-nav-links radius8 padding-6">',
                    'after'            => '</p>',
                )
            );
            do_action('zib_posts_content_after', $post); //添加钩子

            ?>
            <?php tb_xzh_render_tail(); ?>
        </div>
        <?php echo _pz('post_after_content'); ?>
        <?php zib_single_content_footer(); ?>
    </div>
<?php }

function zib_single_content_header()
{
    if (_pz('yiyan_single_content_header')) {
        zib_yiyan('article-yiyan theme-box text-center radius8 main-shadow yiyan-box');
    }
}


function zib_single_content_footer()
{
    $user_id = get_the_author_meta('ID');
    $cat = zib_get_topics_tags('', 'but ml6 radius', '<i class="fa fa-cube" aria-hidden="true"></i>');
    $cat .= zib_get_cat_tags('but ml6 radius', '<i class="fa fa-folder-open-o" aria-hidden="true"></i>');
    $tags = zib_get_posts_tags('but ml6 radius', '# ');


    $like_button = zib_get_post_like($class = 'action action-like');
    $favorite_button = zib_get_post_favorite($class = 'action action-favorite');

    $rewards_button = zib_get_rewards_button($user_id, 'action action-rewards');
    $share_button = '<div href="javascript:;" class="action action-share hover-show">
    ' . zib_svg('share') . '<text>分享</text><div class="zib-widget hover-show-con share-button">' . zib_get_share(false) . '</div></div>';

    $share_button .= '';

    if (_pz('yiyan_single_content_footer')) {
        zib_yiyan('article-yiyan theme-box text-center radius8 main-shadow yiyan-box');
    }

    if (_pz('post_copyright_s')) {
        echo '<div class="em09 muted-3-color"><div><span>©</span> 版权声明</div><div class="posts-copyright">' . _pz('post_copyright') . '</div></div>';
    }

    echo '<div class="text-center theme-box muted-3-color box-body separator em09">THE END</div>';
    if ($cat || $tags) {
        echo '<div class="theme-box article-tags">' . $cat . '<br>' . $tags . '</div>';
    }

    echo '<div class="text-center muted-3-color box-body em09">' . _pz('post_button_toptext', '喜欢就支持一下吧') . '</div>';
    echo '<div class="text-center post-actions">';
    if (_pz('post_like_s')) {
        echo $like_button;
    }
    if (_pz('post_rewards_s')) {
        echo $rewards_button;
    }
    if (_pz('share_s')) {
        echo $share_button;
    }

    echo $favorite_button;
    echo '</div>';
}
