<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-09-29 13:18:37
 * @LastEditTime: 2021-01-09 00:18:18
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

/**获取分类全部文章数量 */
function zib_get_cat_postcount($id, $type = 'category')
{

    $args = array(
        'posts_per_page' => 1,
        'paged' => 1,
        'tax_query' => array(
            array(
                'taxonomy' => $type,
                'field' => 'id',
                'terms' => array($id),
                'include_children' => true,
            )
        ),
    );
    $the_query = new WP_Query($args);
    $count = $the_query->found_posts;
    wp_reset_postdata();
    return $count;
}

function zib_topics_cover($cat_id = '')
{
    $desc = trim(strip_tags(category_description()));
    if (is_super_admin() && !$desc) {
        $desc = '请在Wordress后台-文章-文章专题中添加专题描述！';
    }

    $desc .= zib_get_admin_edit('编辑此专题');

    global $wp_query;
    if (!$cat_id) {
        $cat_id = get_queried_object_id();
    }
    $cat = get_term($cat_id, 'topics');
    $count = $cat->count;
    $title = '<b class="em12"><i class="fa fa-cube mr6" aria-hidden="true"></i>' . $cat->name . '</b>';
    // $title .= '<span class="icon-spot">共' . $count . '篇</span>';
    //$title .='<pre>'. json_encode($cat) .'</pre>';
    $img = zib_get_taxonomy_img_url(null, null, _pz('topics_default_cover'));
    zib_page_cover($title, $img, $desc, '', true);
}

function zib_cat_cover($cat_id = '')
{
    $desc = trim(strip_tags(category_description()));
    if (is_super_admin() && !$desc) {
        $desc = '请在Wordress后台-文章-文章分类中添加分类描述！';
    }

    $desc .= zib_get_admin_edit('编辑此分类');

    //global $wp_query;
    if (!$cat_id) {
        $cat_id = get_queried_object_id();
    }
    $cat = get_category($cat_id);
    $count = zib_get_cat_postcount($cat_id, 'category');
    $title = '<i class="fa fa-folder-open em12 mr10 ml6" aria-hidden="true"></i>' . $cat->cat_name;
    $title .= '<span class="icon-spot">共' . $count . '篇</span>';
    //$title .='<pre>'. json_encode($wp_query) .'</pre>';
    if (_pz('page_cover_cat_s', true)) {
        $img = zib_get_taxonomy_img_url(null, null, _pz('cat_default_cover'));
        zib_page_cover($title, $img, $desc);
    } elseif (zib_random_true(5) && zib_author_main_tab('nav', 'posts')) {
        return;
    } else {
        echo '<div class="zib-widget">';
        echo '<h4 class="title-h-center">' . $title . '</h4>';
        echo '<div class="muted-2-color">' . $desc . '</div>';
        echo '</div>';
    }
}

function zib_tag_cover()
{
    $desc = trim(strip_tags(tag_description()));
    if (is_super_admin() && !$desc) {
        $desc = '请在Wordress后台-文章-文章分类中添加标签描述！';
    }

    $desc .= zib_get_admin_edit('编辑此标签');
    global $wp_query;
    $tag_id = get_queried_object_id();
    $tag = get_tag($tag_id);
    $count = $tag->count;
    $title = '<i class="fa fa-tag em12 mr10 ml6" aria-hidden="true"></i>' . $tag->name;
    $title .= '<span class="icon-spot">共' . $count . '篇</span>';
    if (_pz('page_cover_tag_s', true)) {
        $img = zib_get_taxonomy_img_url(null, null, _pz('tag_default_cover'));
        zib_page_cover($title, $img, $desc);
    } else {
        echo '<div class="zib-widget">';
        echo '<h4 class="title-h-center">' . $title . '</h4>';
        echo '<div class="muted-2-color">' . $desc . '</div>';
        echo '</div>';
    }
}

function zib_page_cover($title, $img, $desc, $more = '', $center = false)
{
    $paged = (get_query_var('paged', 1));
    if ($paged && $paged > 1) {
        $title .= ' <small class="icon-spot">第' . $paged . '页</small>';
    }
    $src = ZIB_STYLESHEET_DIRECTORY_URI . '/img/thumbnail-lg.svg';
    $img = $img ? $img : _pz('page_cover_img', ZIB_STYLESHEET_DIRECTORY_URI . '/img/user_t.jpg');
?>
    <div win-ajax-replace="page-cover" class="page-cover zib-widget">
        <img class="lazyload fit-cover" <?php echo _pz('lazy_cover', true) ? 'src="' . $src . '" data-src="' . $img . '"' : 'src="' . $img . '"'; ?>>
        <div class="absolute page-mask"></div>
        <div class="list-inline box-body <?php echo $center ? 'abs-center text-center' : 'page-cover-con'; ?>">
            <div class="<?php echo $center ? 'title-h-center' : 'title-h-left'; ?>">
                <b><?php echo $title; ?></b>
            </div>
            <div class="em09 page-desc"><?php echo $desc; ?></div>
        </div>
        <?php echo $more; ?>
    </div>
<?php }

/**
 * @description: 页面AJAX菜单
 * @param {*}
 * @return {*}
 */
function zib_ajax_option_menu($page = 'home', $class = 'ajax-option ajax-replace', $link_class = 'ajax-next', $attr = 'win-ajax-replace="filter"')
{
    if (!empty($_GET['nofilter'])) return;
    $page_args = array();
    if ($page == 'home') {
        $page_args['home'] = array(
            'cat' => false,
            'cat_option' => false,
            'topics' => false,
            'topics_option' => false,
            'tag' => false,
            'tag_option' => false,
            'orderby' => _pz('home_list1_orderby_s'),
            'orderby_option' => _pz('home_list1_orderby_option'),
        );
    } else {
        $page_args[$page] = array(
            'cat' =>  _pz('ajax_list_' . $page . '_cat'),
            'cat_option' => _pz('ajax_list_option_' . $page . '_cat'),
            'topics' => _pz('ajax_list_' . $page . '_topics'),
            'topics_option' =>  _pz('ajax_list_option_' . $page . '_topics'),
            'tag' => _pz('ajax_list_' . $page . '_tag'),
            'tag_option' =>  _pz('ajax_list_option_' . $page . '_tag'),
            'orderby' => _pz($page . '_orderby_s'),
            'orderby_option' => _pz($page . '_orderby_option'),
        );
    }

    $con = '';
    if ($page_args[$page]['cat']) {
        $con .= zib_get_option_terms_but($page_args[$page]['cat_option'], $link_class, '分类');
    }
    if ($page_args[$page]['topics']) {
        $con .= zib_get_option_terms_but($page_args[$page]['topics_option'], $link_class, '专题');
    }
    if ($page_args[$page]['tag']) {
        $con .= zib_get_option_terms_but($page_args[$page]['tag_option'], $link_class, '标签');
    }
    if ($page_args[$page]['orderby']) {
        $con .= zib_get_option_orderby_but($page_args[$page]['orderby_option'], $link_class);
    }

    if (!$con) return;
    $html = '<div class="' . $class . '" ' . $attr . '>' . $con . '</div>';
    $html .= '<div></div>'; //空白内容，解决css奇数偶数行
    echo $html;
}


function zib_get_option_list_orderby()
{
    $args = array(
        'modified' => '更新',
        'date' => '发布',
        'views' => '浏览',
        'like' => '点赞',
        'comment_count' => '评论',
        'favorite' => '收藏',
        'rand' => '随机',
    );
    return $args;
}
//排序方式
function zib_get_option_orderby_but($option = array(), $link_class = 'ajax-next')
{
    $defaults = array(
        'lists' => array(),
        'dropdown' => false,
    );

    $option = wp_parse_args((array) $option, $defaults);
    if (!$option['lists'] && !$option['dropdown']) return '';

    $html = '';
    $all_args = zib_get_option_list_orderby();

    $dropdown_but = '';
    $but = '';
    $uri = home_url(add_query_arg(array()));
    if ($uri) {
        $uri =  preg_replace('/page\/([\d]*)/', '', $uri);
    }

    foreach ($option['lists'] as $key) {
        $_class = $link_class;
        if (isset($_GET['orderby']) && $_GET['orderby'] == $key) {
            $_class = $link_class . ' focus-color';
        }
        $href = add_query_arg(array('orderby' => $key, 'paged' => false), $uri);
        $but .= '<a ajax-replace="true" class="' . $_class  . '" href="' . $href . '">' . $all_args[$key] . '</a>';
    }
    if ($option['dropdown']) {
        foreach ($all_args as $key => $value) {
            $_class = $link_class;
            if (isset($_GET['orderby']) && $_GET['orderby'] == $key) {
                $_class = $link_class . ' focus-color';
            }
            $href = add_query_arg(array('orderby' => $key, 'paged' => false), $uri);
            $dropdown_but .= '<li><a ajax-replace="true" class="' . $_class  . '" href="' . $href . '">' . $value . '</a></li>';
        }
    }

    if (!$but && !$dropdown_but) return '';

    $is_dropdown = ($option['dropdown'] && $dropdown_but) ? true : false;
    $d_but = $is_dropdown ? '<a href="javascript:;" data-toggle="dropdown"><span name="cat">排序</span><i class="fa fa-fw fa-sort opacity5" aria-hidden="true"></i></a>' : '排序';

    $html .= '<div>';
    $html .= '<div class="option-dropdown splitters-this-r dropdown">';
    $html .= $d_but;
    $html .= $is_dropdown ? '<ul class="dropdown-menu">' . $dropdown_but . '</ul>' : '';
    $html .= '</div>';
    $html .= '<ul class="list-inline scroll-x mini-scrollbar option-items">' . $but . '</ul>';
    $html .= '</div>';

    return $html;
}


function zib_get_option_terms_but($option = array(), $link_class = 'ajax-next', $text = '分类')
{
    $defaults = array(
        'lists' => array(),
        'dropdown' => false,
        'dropdown_lists' => array(),
    );

    $option = wp_parse_args((array) $option, $defaults);
    if (!$option['lists'] && (!$option['dropdown'] || !$option['dropdown_lists'])) return '';

    $html = '';
    $dropdown_but = '';
    $but = '';
    $this_id = get_queried_object_id();
    if ($option['lists']) {
        $lists = get_terms(array(
            'include' => $option['lists'],
            'orderby' => 'include',
        ));
        foreach ($lists as $term) {
            $_class = $link_class;
            if ($this_id == $term->term_id) {
                $_class = $link_class . ' focus-color';
            }
            $name = zib_str_cut($term->name, 0, 8, '...');
            $href = get_term_link($term);
            $but .= '<a ajax-replace="true" class="' . $_class  . '" href="' . $href . '">' . $name . '</a>';
        }
    }
    if ($option['dropdown'] || $option['dropdown_lists']) {
        $lists = get_terms(array(
            'include' => $option['dropdown_lists'],
            'orderby' => 'include',
        ));
        foreach ($lists as $term) {
            $_class = $link_class;
            if ($this_id == $term->term_id) {
                $_class = $link_class . ' focus-color';
            }
            $name = zib_str_cut($term->name, 0, 8, '...');
            $href = get_term_link($term);
            $dropdown_but .= '<li><a ajax-replace="true" class="' . $_class  . '" href="' . $href . '">' . $name . '</a></li>';
        }
    }
    if (!$but && !$dropdown_but) return '';

    $is_dropdown = ($option['dropdown'] && $dropdown_but) ? true : false;
    $d_but = $is_dropdown ? '<a href="javascript:;" data-toggle="dropdown"><span name="cat">' . $text . '</span><i class="fa fa-fw fa-sort opacity5" aria-hidden="true"></i></a>' : $text;

    $html .= '<div>';
    $html .= '<div class="option-dropdown splitters-this-r dropdown">';
    $html .= $d_but;
    $html .= $is_dropdown ? '<ul class="dropdown-menu">' . $dropdown_but . '</ul>' : '';
    $html .= '</div>';
    $html .= '<ul class="list-inline scroll-x mini-scrollbar option-items">' . $but . '</ul>';
    $html .= '</div>';

    return $html;
}
