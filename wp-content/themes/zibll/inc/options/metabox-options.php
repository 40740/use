<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-11-11 11:41:45
 * @LastEditTime: 2020-11-26 10:59:40
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

//文章扩展
CSF::createMetabox('posts_main', array(
    'title'     => '文章扩展',
    'post_type' => array('post'),
    'context'   => 'side',
    'data_type' => 'unserialize',
));
CSF::createSection('posts_main', array(
    'fields' => array(
        array(
            'id'    => 'subtitle',
            'type'  => 'text',
            'title' => '副标题',
        ),
        array(
            'id'    => 'views',
            'type'  => 'number',
            'title' => '阅读量',
            'default' => '0',
            'validate' => 'csf_validate_numeric',
        ),
        array(
            'id'    => 'like',
            'type'  => 'number',
            'title' => '点赞数',
            'default' => '0',
            'validate' => 'csf_validate_numeric',
        ),
        array(
            'id'      => 'show_layout',
            'type'    => 'radio',
            'title'   => '显示布局',
            'default' => 'false',
            'options' => array(
                'false'   => '跟随主题',
                'no_sidebar' => '无侧边栏',
                'sidebar_left' => '侧边栏靠左',
                'sidebar_right' => '侧边栏靠右',
            ),
        ),
        array(
            'id'    => 'no_article-navs',
            'type'  => 'checkbox',
            'label' => '不显示目录树',
        ),
        array(
            'id'    => 'article_maxheight_xz',
            'type'  => 'checkbox',
            'label' => '限制内容最大高度',
        ),
    )
));

//页面扩展
CSF::createMetabox('page_main', array(
    'title'     => '页面扩展',
    'post_type' => array('page'),
    'context'   => 'side',
    'data_type' => 'unserialize',
));
CSF::createSection('page_main', array(
    'fields' => array(
        array(
            'id'      => 'show_layout',
            'type'    => 'radio',
            'title'   => '显示布局',
            'default' => '',
            'options' => array(
                ''   => '跟随主题',
                'no_sidebar' => '无侧边栏',
                'sidebar_left' => '侧边栏靠左',
                'sidebar_right' => '侧边栏靠右',
            ),
        ),
        array(
            'id'      => 'page_header_style',
            'type'    => 'radio',
            'title'   => '标题样式',
            'default' => '',
            'options' => array(
                '' => __('跟随主题', 'zib_language'),
                1 => __('简单样式', 'zib_language'),
                2 => __('卡片样式', 'zib_language'),
                3 => __('图文样式', 'zib_language'),
            ),
        ),
    )
));

//文章和页面seo
if (_pz('post_keywords_description_s')) {
    CSF::createMetabox('posts_seo', array(
        'title'     => '独立SEO',
        'post_type' => array('post', 'page'),
        'context'   => 'advanced',
        'data_type' => 'unserialize',
    ));
    CSF::createSection('posts_seo', array(
        'fields' => array(
            array(
                'title' => __('SEO预览', 'zib_language'),
                'type'    => 'content',
                'content' => zib_get_seo_preview_box(),
            ),
            array(
                'title' => __('标题', 'zib_language'),
                'id' => 'title',
                'desc' => 'Title 一般建议15到30个字符',
                'std' => '',
                'type' => 'text',
            ),
            array(
                'title' => __('关键词', 'zib_language'),
                'id' => 'keywords',
                'desc' => 'Keywords 每个关键词用逗号隔开',
                'std' => '',
                'type' => 'text',
            ),
            array(
                'title' => __('描述', 'zib_language'),
                'id' => 'description',
                'desc' => 'Description 一般建议50到150个字符',
                'std' => '',
                'type' => 'textarea',
            ),
            array(
                'type'       => 'accordion',
                'id'       => 'accordion',
                'accordions' => array(
                    array(
                        'title'  => 'SEO优化建议',
                        'icon'   => 'fas fa-star',
                        'fields' => array(
                            array(
                                'title' => ' ',
                                'type'    => 'content',
                                'content' => '<div style="color:#048cf0;margin-bottom:5px;">SEO标题优化建议：</div>
                                <li>主题默认会自动获取标题、副标题、网站名称作为SEO标题</li>
                                <li>标题内容应该紧扣页面的主要内容有吸引力</li>
                                <li>网站标题不要有过多的重复</li>
                                <li>第一个词放最重要的关键词</li>
                                <li>关键词只能重复2次，不要堆砌关键词</li>
                                <li>最后一个词放品牌词，不重要的词语</li>
                                <div style="color:#048cf0;margin-bottom:5px;margin-top:15px;">SEO关键词优化建议：</div>
                                <li>主题默认会自动获取分类及标签作为关键词，页面请单独自定义</li>
                                <li>关键词一般建议4到8个</li>
                                <li>尽量与网站定位一致</li>
                                <li>添加网站专属关键词</li>
                                <div style="color:#048cf0;margin-bottom:5px;margin-top:15px;">SEO描述优化建议：</div>
                                <li>主题默认会自动获取摘要、内容为SEO描述</li>
                                <li>description是对网页内容的精练概括</li>
                                <li>写成一段通顺有意义的话，要有吸引力</li>
                                <li>建议加入多个关键词，但不宜重复太多</li>
                                <div style="color:#f7497e;margin-bottom:5px;margin-top:15px;">优化建议来自互联网，仅供参考</div>',
                            ),
                        )
                    ),
                )
            ),
        )
    ));
}


function zib_get_seo_preview_box($type = 'post')
{
    $title = '';
    $keywords = '';
    $description = '';
    $html = '';
    $permalink = '';

    $after = (_pz('connector') ? _pz('connector') : '-') . get_bloginfo('name');
    if ($type == 'post') {
        if (isset($_GET['post'])) {
            $post_id = (int) $_GET['post'];
        } elseif (isset($_POST['post_ID'])) {
            $post_id = (int) $_POST['post_ID'];
        } else {
            $post_id = 0;
        }
        if ($post_id) {
            $post = get_post($post_id);
            $permalink = get_permalink($post);

            $title = get_post_meta($post->ID, 'title', true);
            $title = $title ? $title : $post->post_title . get_post_meta($post->ID, 'subtitle', true) . $after;

            $keywords = get_post_meta($post->ID, 'keywords', true);

            if (!$keywords) {
                if (get_the_tags($post->ID)) {
                    foreach (get_the_tags($post->ID) as $tag) {
                        $keywords .= $tag->name . ', ';
                    }
                }
                foreach (get_the_category($post->ID) as $category) {
                    $keywords .= $category->cat_name . ', ';
                }
                $keywords = substr_replace($keywords, '', -2);
            }
            $description = get_post_meta($post->ID, 'description', true);
            if (!$description) {
                if (!empty($post->post_excerpt)) {
                    $description = $post->post_excerpt;
                } else {
                    $description = $post->post_content;
                }
                $description = trim(str_replace(array("\r\n", "\r", "\n", "　", " "), " ", str_replace("\"", "'", strip_tags($description))));

                /**删除短代码内容 */
                $description = preg_replace('/\[payshow.*payshow\]||\[hidecontent.*hidecontent\]||\[reply.*reply\]||\[postsbox.*\]/', '', $description);

                $description = mb_substr($description, 0, 200, 'utf-8');
                if (!$description) {
                    $description = get_bloginfo('name') . "-" . trim(wp_title('', false));
                }
            }
        }
    }
    $html .= '<style>
    .zib-widget.seo-preview {
        padding: 15px 20px;
        border-radius: 10px;
        max-width: 600px;
        box-shadow: 0 0 10px rgb(0 0 0 / 8%);
    }
    .seo-title a{
        font-size: 18px;
        line-height: 22px;
        color: #2440b3;
        text-decoration: none;
    }
    .seo-description {
        margin:10px 0 5px 0;
    }
    .seo-keywords {
        opacity: .6;
        margin-top: 5px;
    }
    </style>';

    if (!$permalink) {
        return $html . '<div style=" text-align: center; padding: 30px 15px; color: #fc61a5; font-size: 14px; " class="zib-widget seo-preview"><div class="seo-title"><span class="dashicons dashicons-warning"></span> 请保存内容后 刷新页面查看SEO预览</div></div>';
    }
    $title = $title ? $title : '<span style=" color: #fa4784; "><span class="dashicons dashicons-warning"></span> SEO标题或者文章标题为空</span>';
    $keywords = $keywords ? $keywords : '<span style=" color: #fa4784; "><span class="dashicons dashicons-warning"></span> SEO关键词为空</span>';
    $description = $description ? $description : '<span style=" color: #fa4784; "><span class="dashicons dashicons-warning"></span> SEO描述或文章内容为空</span>';

    $html .= '<div class="zib-widget seo-preview">';
    $html .= '<div class="seo-header"></div>';
    $html .= '<div class="seo-title">';
    $html .= '<a class="" href="javascript:;">' . $title . '</a>';
    $html .= '</div>';

    $html .= '<div class="seo-description">' . $description . '</div>';
    $html .= '<a class="" href="javascript:;">' . $permalink . '</a>';
    $html .= '<div class="seo-keywords">';
    $html .= '<div class="">' . $keywords . '</div>';
    $html .= '</div>';

    $html .= '</div>';

    return $html;
}
