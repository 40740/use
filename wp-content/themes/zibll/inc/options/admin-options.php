<?php

$prefix = 'zibll_options';
function zib_csf_admin_options()
{
    //只有后台才执行此代码
    if (!is_admin()) return;
    //执行数据转换
    zib_pz_to_csf();
    $prefix = 'zibll_options';

    $imagepath =  get_template_directory_uri() . '/img/';
    $f_imgpath =  get_template_directory_uri() . '/inc/csf-framework/assets/images/';
    //开始构建
    CSF::createOptions($prefix, array(
        'menu_title' => 'Zibll主题设置',
        'menu_slug'  => 'zibll_options',
        'framework_title'         => '子比主题',
        'show_in_customizer'      => true, //在wp-customize中也显示相同的选项
        'footer_text'             => '更优雅的wordpress主题-Zibll主题 V' . wp_get_theme()['Version'],
        'footer_credit'           => '<i class="fa fa-fw fa-heart-o" aria-hidden="true"></i> ',
        'theme'  => 'light'
    ));

    CSF::createSection($prefix, array(
        'id'    => 'basic',
        'title'  => '全局&功能',
        'icon'   => 'fa fa-fw fa-bullseye',
    ));
    CSF::createSection($prefix, array(
        'id'    => 'page',
        'title'  => '页面&显示',
        'icon'   => 'fa fa-fw fa-calendar-check-o',
    ));
    CSF::createSection($prefix, array(
        'id'    => 'post',
        'title'  => '文章&列表',
        'icon'   => 'fa fa-fw fa-map-o',
    ));
    CSF::createSection($prefix, array(
        'id'    => 'user',
        'title'  => '用户&互动',
        'icon'   => 'fa fa-fw fa-user-o',
    ));
    CSF::createSection($prefix, array(
        'id'    => 'pay',
        'title'  => '商城&付费',
        'icon'   => 'fa fa-fw fa-cart-plus',
    ));

    CSF::createSection($prefix, array(
        'id'    => 'over',
        'title'  => '扩展&增强',
        'icon'   => 'fa fa-fw fa-puzzle-piece',
    ));


    //图片设置
    CSF::createSection($prefix, array(
        'parent'      => 'basic',
        'title'       => 'LOGO图像',
        'icon'        => 'fa fa-fw fa-image',
        'description' => '',
        'fields'      => array(
            array(
                'title' => __('网站图标', 'zib_language'),
                'id' => 'favicon',
                'desc' => __('自定义网站图标，也就是favicon.ico(建议48x48)'),
                'default' => $imagepath . 'favicon.png',
                'preview' => true,
                'library' => 'image', 'type' => 'upload'
            ),
            array(
                'title' => __('桌面图标', 'zib_language'),
                'id' => 'iconpng',
                'desc' => __('桌面图标，建议148x148(苹果手机添加到桌面的图标)'),
                'default' => $imagepath . 'icon.png',
                'preview' => true,
                'library' => 'image', 'type' => 'upload'
            ),
            array(
                'title' => __('网站Logo', 'zib_language'),
                'subtitle' => __('日间主题', 'zib_language'),
                'id' => 'logo_src',
                'desc' => __('显示在顶部的Logo 建议高度60px，请使用png格式的透明图片', 'zib_language'),
                'help' => '如果单张LOGO图能同时适应日间和夜间主题，则仅设置日间主题的logo即可（推荐这样设置）',
                'default' => $imagepath . 'logo.png',
                'preview' => true,
                'library' => 'image', 'type' => 'upload'
            ),
            array(
                'title' => __('网站Logo', 'zib_language'),
                'subtitle' => __('夜间主题', 'zib_language'),
                'id' => 'logo_src_dark',
                'class' => 'compact',
                'default' => $imagepath . 'logo_dark.png',
                'preview' => true,
                'library' => 'image', 'type' => 'upload'
            ),
        )
    ));

    //SEO优化
    CSF::createSection($prefix, array(
        'parent'      => 'basic',
        'title'       => 'SEO优化',
        'icon'        => 'fa fa-fw fa-superpowers',
        'description' => '',
        'fields'      => array(
            array(
                'title' => __('核心SEO优化', 'zib_language'),
                'subtitle' => __('文章、页面独立SEO设置', 'zib_language'),
                'id' => 'post_keywords_description_s',
                'desc' => '开启后每一篇文章、分类和页面都可以独立设置SEO内容',
                'type' => "switcher",
                'default' => true,
            ),
            array(
                'title' => __('SEO连接符', 'zib_language'),
                'id' => 'connector',
                'desc' => __('SEO标题连接符（一般为“-”或“_”或者“|”）', 'zib_language'),
                'default' => '-',
                'type' => 'text',
            ),
            array(
                'id' => 'hometitle',
                'title' => __('网站SEO', 'zib_language'),
                'subtitle' => 'SEO标题(title)',
                'placeholder' => '自定义网站的SEO标题(title)',
                'help' => '站点一句话有吸引力的标题，建议25—35字，如果未设置，则采用“站点标题+副标题”',
                'default' => '',
                'attributes' => array(
                    'rows' => 2
                ),
                'sanitize' => false,
                'type' => 'textarea'
            ),
            array(
                'id' => 'keywords',
                'title' => ' ',
                'subtitle' => __('SEO关键字(keywords)', 'zib_language'),
                'placeholder' => '自定义网站的SEO关键字(keywords)',
                'help' => '关键字有利于SEO优化，建议个数在5-8个之间，用英文逗号隔开',
                'default' => '',
                'class' => 'compact',
                'attributes' => array(
                    'rows' => 2
                ),
                'sanitize' => false,
                'type' => 'textarea'
            ),
            array(
                'id' => 'description',
                'title' => ' ',
                'subtitle' => __('SEO描述(description)', 'zib_language'),
                'placeholder' => __('自定义网站的SEO描述(description)', 'zib_language'),
                'class' => 'compact',
                'help' => '介绍、描述您的网站，建议字数在40-70之间',
                'default' => '',
                'attributes' => array(
                    'rows' => 3
                ),
                'sanitize' => false,
                'type' => 'textarea'
            ),
            array(
                'id' => 'zib_baidu_push_js',
                'title' => __('百度SEO', 'zib_language'),
                'subtitle' => __('全站链接自动提交', 'zib_language'),
                'desc' => '采用百度最新自动提交接口，无需其他设置。开启后自动将网站所有链接推送到百度，可提高收录速度。</br>官方文档：https://ziyuan.baidu.com/college/courseinfo?id=267&page=2#h2_article_title12',
                'type' => "switcher",
                'default' => false,
            ),
            array(
                'id' => 'xzh_post_on',
                'title' => ' ',
                'subtitle' => __('百度普通收录', 'zib_language'),
                'desc' => '普通收录，每天可提交最多10万条有价值内容，收录速度较慢',
                'class' => 'compact',
                'type' => "switcher",
                'default' => false,
            ),
            array(
                'id' => 'xzh_post_daily_push',
                'title' => ' ',
                'subtitle' => __('百度快速收录', 'zib_language'),
                'desc' => '快速收录是百度新推出的高效收录接口，目前仅对部分优质站点开放，请确保您的站点以开放快速收录功能',
                'class' => 'compact',
                'type' => "switcher",
                'default' => false,
            ),
            array(
                'id' => 'xzh_post_token',
                'title' => ' ',
                'subtitle' => __('百度准入密钥', 'zib_language'),
                'desc' => __('开启普通收录或快速收录，则必填此项</br>密钥获取：https://zn.baidu.com/linksubmit', 'zib_language'),
                'default' => '',
                'placeholder' => '必填项',
                'class' => 'compact',
                'type' => 'text',
            ),
            array(
                'title' => '外链重定向',
                'id' => 'go_link_s',
                'type' => 'switcher',
                'desc' => "开启此功能后，非本站的链接将会重定向至内部链接，点击后延迟跳转，有利于SEO。如果对正常链接造成了影响，请关闭此功能",
                'default' => true,
            ),
            array(
                'dependency' => array('go_link_s', '!=', ''),
                'id' => 'go_link_post',
                'class' => 'compact',
                'type' => 'switcher',
                'default' => true,
                'title' => ' ',
                'subtitle' => '外链重定向对文章内容开启'
            ),
            array(
                'title' => '系统优化',
                'id' => 'no_categoty',
                'type' => 'switcher',
                'desc' => "该功能和no-category插件作用相同，不能同时使用</br>开启后有利于SEO，建议在建站时设置好，后期不轻易修改",
                'default' => false,
                'title' => '分类url去除category'
            ),
        )
    ));


    CSF::createSection($prefix, array(
        'parent'      => 'basic',
        'title'       => '常用功能',
        'icon'        => 'fa fa-fw fa-bolt',
        'description' => '',
        'fields'      => array(

            array(
                'title' => '图像异步懒加载',
                'id' => 'lazy_posts_thumb',
                'default' => true,
                'subtitle' => __('文章缩略图懒加载', 'zib_language'),
                'help' => '开启图片懒加载，当页面滚动到图像位置时候才加载图片，可极大的提高页面访问速度。',
                'type' => 'switcher'
            ),
            array(
                'title' => ' ',
                'id' => 'lazy_avatar',
                'class' => 'compact',
                'default' => true,
                'subtitle' => __('头像懒加载', 'zib_language'),
                'type' => 'switcher'
            ),

            array(
                'title' => ' ',
                'id' => 'lazy_posts_content',
                'class' => 'compact',
                'default' => false,
                'help' => '对SEO有一点影响，请酌情开启！',
                'subtitle' => __('文章内容图片懒加载', 'zib_language'),
                'type' => 'switcher'
            ),
            array(
                'title' => ' ',
                'id' => 'lazy_comment',
                'class' => 'compact',
                'default' => true,
                'subtitle' => __('评论内容图片懒加载', 'zib_language'),
                'type' => 'switcher'
            ),

            array(
                'title' => ' ',
                'id' => 'lazy_private',
                'class' => 'compact',
                'default' => true,
                'subtitle' => __('私信聊天图片懒加载', 'zib_language'),
                'type' => 'switcher'
            ),
            array(
                'title' => ' ',
                'id' => 'lazy_sider',
                'class' => 'compact',
                'default' => true,
                'subtitle' => __('幻灯片图片懒加载', 'zib_language'),
                'type' => 'switcher'
            ),
            array(
                'title' => ' ',
                'id' => 'lazy_cover',
                'class' => 'compact',
                'default' => true,
                'subtitle' => __('封面图片懒加载', 'zib_language'),
                'type' => 'switcher'
            ),

            array(
                'title' => ' ',
                'subtitle' => '懒加载动画效果',
                'id' => 'lazy_animation',
                'default' => 'blur',
                'class' => 'compact',
                'type' => 'select',
                'options' => array(
                    'fade' => __('淡出淡入', 'zib_language'),
                    'blur' => __('高斯模糊', 'zib_language'),
                    'scale' => __('放大缩小', 'zib_language'),
                )
            ),

            array(
                'title' => '搜索功能',
                'id' => 'search_placeholder',
                'subtitle' => '搜索框占位符',
                'type' => 'text',
                'default' => '开启精彩搜索',
            ),

            array(
                'title' => '热门搜索',
                'class' => 'compact',
                'subtitle' => '展示网站热门搜索关键词',
                'id' => 'search_popular_key',
                'default' => true,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('search_popular_key', '!=', ''),
                'title' => ' ',
                'subtitle' => '热门搜索-默认标题',
                'id' => 'search_popular_title',
                'class' => 'compact',
                'type' => 'text',
                'default' => '热门搜索',
            ),

            array(
                'dependency' => array('search_popular_key', '!=', ''),
                'title' => ' ',
                'subtitle' => '置顶搜索词',
                'id' => 'search_popular_sticky',
                'class' => 'compact',
                'placeholder' => '教程,分享,网络科技',
                'desc' => '在热门搜索中固定的关键词（使用逗号分割，例如：教程,分享,网络科技）',
                'type' => 'text',
                'default' => '',
            ),

            array(
                'dependency' => array('search_popular_key', '!=', ''),
                'title' => ' ',
                'subtitle' => '关键词最大保留数量',
                'id' => 'search_popular_key_num',
                'class' => 'compact',
                'default' => 20,
                'type'        => 'spinner',
                'min'         => 10,
                'max'         => 50,
                'step'        => 2,
                'unit'        => '个',
            ),

            array(
                'title' => '代码高亮',
                'id' => 'highlight_kg',
                'type' => 'switcher',
                'default' => true,
                'label' => '全局开关，不会影响古腾堡块-代码高亮块',
            ),

            array(
                'title' => ' ',
                'subtitle' => '代码高亮显示行号',
                'id' => 'highlight_hh',
                'type' => 'switcher',
                'class' => 'compact',
                'default' => false,
            ),

            array(
                'title' => ' ',
                'subtitle' => '代码高亮显示扩展按钮',
                'id' => 'highlight_btn',
                'type' => 'switcher',
                'class' => 'compact',
                'label' => '显示切换高亮、复制、新窗口打开三个扩展按钮',
                'default' => false,
            ),

            array(
                'title' => '默认主题',
                'subtitle' => '日间亮色模式下->默认主题',
                'id' => 'highlight_zt',
                'type' => 'select',
                'class' => 'compact',
                'default' => 'enlighter',
                'options' => array(
                    'enlighter' => __('浅色: Enlighter'),
                    'bootstrap4' => __('浅色：Bootstrap'),
                    'classic' => __('浅色：Classic'),
                    'beyond' => __('浅色：Beyond'),
                    'mowtwo' => __('浅色：Mowtwo'),
                    'eclipse' => __('浅色：Eclipse'),
                    'droide' => __('浅色：Droide'),
                    'minimal' => __('浅色：Minimal'),
                    'rowhammer' => __('浅色：Rowhammer'),
                    'godzilla' => __('浅色：Godzilla')
                )
            ),

            array(
                'id' => 'highlight_dark_zt',
                'title' => ' ',
                'subtitle' => '夜间深色模式下->默认主题',
                'type' => 'select',
                'class' => 'compact',
                'default' => 'dracula',
                'desc' => '此为默认设置，古腾堡编辑器中可单独设置主题</br>主题预览地址： https://enlighterjs.org/Theme.Enlighter.html',
                'options' => array(
                    'dracula' => __('深色：Dracula'),
                    'atomic' => __('深色：Atomic'),
                    'monokai' => __('深色：Monokai')
                )
            ),

            array(
                'title' => ' ',
                'subtitle' => '代码高亮最大高度',
                'id' => 'highlight_maxheight',
                'class' => 'compact',
                'desc' => __('设置为0则不限制高度', 'zib_language'),
                'default' => 400,
                'max' => 2000,
                'min' => 0,
                'step' => 25,
                'unit' => 'PX',
                'type' => 'spinner'
            ),
            array(
                'dependency' => array('highlight_maxheight', '==', 0),
                'type'    => 'submessage',
                'style'   => 'success',
                'content' => '已设置代码高亮<b>不限制最大高度</b>',
            ),

            array(
                'title' => __('返回顶部按钮', 'zib_language'),
                'id' => 'float_right_ontop',
                'type' => 'switcher',
                'default' => true,
                'label' => __('电脑端', 'zib_language')
            ),
            array(
                'id' => 'float_right_mobile_show',
                'title' => ' ',
                'class' => 'compact',
                'type' => 'switcher',
                'default' => true,
                'label' => __('手机端', 'zib_language')
            ),

            array(
                'title' => __('弹窗通知', 'zib_language'),
                'id' => 'system_notice_s',
                'help' => '打开页面自动弹出一个模态框，当天不会重复显示',
                'type' => 'switcher',
                'default' => true,
            ),
            array(
                'dependency' => array('system_notice_s', '!=', ''),
                'id' => 'system_notice_size',
                'title' => ' ',
                'subtitle' => '窗口尺寸',
                'default' => 'modal-sm',
                'class' => 'compact',
                'inline' => true,
                'type' => 'radio',
                'options' => array(
                    'modal-sm' => __('小', 'zib_language'),
                    '' => __('中', 'zib_language'),
                    'modal-lg' => __('大', 'zib_language')
                )
            ),
            array(
                'dependency' => array('system_notice_s', '!=', ''),
                'id' => 'system_notice_title',
                'title' => ' ',
                'subtitle' => '弹窗标题',
                'class' => 'compact',
                'default' => '<i class="fa fa-fw fa-heart c-red"></i>',
                'attributes' => array(
                    'rows' => 1
                ),
                'sanitize' => false,
                'type' => 'textarea'
            ),

            array(
                'dependency' => array('system_notice_s', '!=', ''),
                'id' => 'system_notice_content',
                'title' => ' ',
                'subtitle' => '弹窗内容',
                'class' => 'compact',
                'attributes' => array(
                    'rows' => 3
                ),
                'sanitize' => false,
                'type' => 'textarea',
                'desc' => '支持HTML代码，请注意代码规范及标签闭合',
                'default' => '<p class="c-yellow">感谢您选择子比主题</p>
        这是一条系统弹窗通知，您可以在后台-主题设置里修改内容',
            ),

            array(
                'dependency' => array('system_notice_s', '!=', ''),
                'id'     => 'system_notice_button',
                'type'   => 'group',
                'max'   => 4,
                'button_title' => '添加按钮',
                'class' => 'compact',
                'title'  => '弹窗按钮',
                'default'   => array(
                    array(
                        'link' => array(
                            'url'    => 'https://www.zibll.com/',
                            'text'   => '子比官网',
                        ),
                        'class' => 'c-blue',
                    ),
                    array(
                        'link' => array(
                            'url'    => admin_url('admin.php?page=zibll_options#tab=常用功能'),
                            'text'   => '立即设置',
                        ),
                        'class' => 'c-green',
                    ),
                ),
                'fields' => array(
                    array(
                        'id'    => 'link',
                        'type'  => 'link',
                        'title' => '按钮链接',
                        'add_title'    => '添加链接',
                        'edit_title'   => '编辑链接',
                        'remove_title' => '删除链接',
                    ),
                    array(
                        'dependency' => array('link', '!=', ''),
                        'title' => '按钮颜色',
                        'id' => "class",
                        'class' => 'compact skin-color',
                        'default' => "c-green",
                        'type' => "palette",
                        'options' => CFS_Module::zib_palette()
                    ),
                ),
            ),
            array(
                'dependency' => array('system_notice_s', '!=', ''),
                'title' => ' ',
                'subtitle' => '弹窗按钮圆角显示',
                'id' => 'system_notice_radius',
                'class' => 'compact',
                'type' => 'switcher',
                'default' => false,
            ),
        )
    ));


    CSF::createSection($prefix, array(
        'id'          => 'theme',
        'parent'      => 'basic',
        'title'       => '显示&布局',
        'icon'        => 'fa fa-fw fa-delicious',
        'description' => '',
        'fields'      => array(
            array(
                'title' => __('侧边栏设置'),
                'type'    => 'content',
                'content' => '在此设置侧边栏的默认状态，同时每篇文章或页面均可单独设置',
            ),
            array(
                'title' => ' ',
                'class' => 'compact',
                'subtitle' => '首页显示侧边栏',
                'id' => 'sidebar_home_s',
                'type' => "switcher",
                'default' => false,
            ),

            array(
                'title' => ' ',
                'subtitle' => '分类页显示侧边栏',
                'class' => 'compact',
                'id' => 'sidebar_cat_s',
                'type' => "switcher",
                'default' => false,
            ),
            array(
                'title' => ' ',
                'subtitle' => '标签页显示侧边栏',
                'class' => 'compact',
                'id' => 'sidebar_tag_s',
                'type' => "switcher",
                'default' => false,
            ),
            array(
                'title' => ' ',
                'subtitle' => '搜索页显示侧边栏',
                'class' => 'compact',
                'id' => 'sidebar_search_s',
                'type' => "switcher",
                'default' => false,
            ),
            array(
                'title' => ' ',
                'subtitle' => '文章页显示侧边栏',
                'class' => 'compact',
                'id' => 'sidebar_single_s',
                'type' => "switcher",
                'default' => true,
            ),
            array(
                'title' => ' ',
                'subtitle' => '页面显示侧边栏',
                'class' => 'compact',
                'id' => 'sidebar_page_s',
                'type' => "switcher",
                'default' => false,
            ),
            array(
                'title' => '侧边栏布局',
                'id' => 'sidebar_layout',
                'class' => 'compact',
                'default' => "right",
                'type' => "image_select",
                'options' => array(
                    'left' => $f_imgpath . '2cl.png',
                    'right' => $f_imgpath . '2cr.png',
                )
            ),

            array(
                'title' => '布局宽度',
                'subtitle' => '页面布局的最大宽度',
                'id' => 'layout_max_width',
                'default' => 1200,
                'desc' => __('页面宽度已经经过精心的调整，非特殊需求请勿调整，宽度过大会造成显示不协调', 'zib_language'),
                'desc' => __('页面全局宽度', 'zib_language'),
                'max' => 1800,
                'min' => 1200,
                'step' => 50,
                'unit' => 'PX',
                'type' => 'spinner'
            ),
            array(
                'dependency' => array('layout_max_width', '<', 1200),
                'type'    => 'submessage',
                'style'   => 'danger',
                'content' => '<div style="text-align:center"><b><i class="fa fa-fw fa-ban fa-fw"></i> 页面宽度不能低于1200PX</b></div>',
            ),
            array(
                'title' => __('默认主题', 'zib_language'),
                'id' => 'theme_mode',
                'help' => '主题最高优先级来自用户选择，也就是浏览器缓存，只有当用户未设置主题的时候此选项才有效',
                'default' => "time-auto",
                'type' => "radio",
                'options' => array(
                    'white-theme' => __('日间亮色主题', 'zib_language'),
                    'dark-theme' => __('夜间深色主题', 'zib_language'),
                    'time-auto' => __('早晚8点自动切换', 'zib_language'),
                )
            ),
            array(
                'title' => '主题切换按钮',
                'subtitle' => '选择显示按钮显示位置',
                'class' => 'compact',
                'id' => 'theme_mode_button',
                'help' => '如果关闭此功能，则前端不会显示切换按钮',
                'type' => "checkbox",
                'default' => array('pc_nav', 'm_menu'),
                'options' => array(
                    'pc_nav' => __('PC端顶部导航', 'zib_language'),
                    'm_menu' => __('移动端弹出菜单', 'zib_language'),
                    'float_right' => __('右侧浮动按钮（返回顶部按钮上方）', 'zib_language'),
                )
            ),
            array(
                'title' => __("全局主题色", 'zib_language'),
                'subtitle' => '主题高亮颜色',
                'id' => 'theme_skin_custom',
                'default' => "",
                'desc' => __('如需选择下方预置颜色，请先清空上方颜色', 'zib_language'),
                'type' => "color"
            ),
            array(
                'title' => ' ',
                'desc' => '',
                'id' => "theme_skin",
                'dependency' => array('theme_skin_custom', '==', '', '', 'visible'),
                'class' => 'compact skin-color',
                'default' => "f04494",
                'type' => "palette",
                'options' => array(
                    'ff1856' => array('#fd2760'),
                    'f04494' => array('#f04494'),
                    'ae53f3' => array('#ae53f3'),
                    '627bf5' => array('#627bf5'),
                    '00a2e3' => array('#00a2e3'),
                    '16b597' => array('#16b597'),
                    '36af18' => array('#36af18'),
                    '8fb107' => array('#8fb107'),
                    'b18c07' => array('#b18c07'),
                    'e06711' => array('#e06711'),
                    'f74735' => array('#f74735'),
                )
            ),
            array(
                'title' => __('卡片圆角', 'zib_language'),
                'subtitle' => __('页面卡片的圆角尺寸', 'zib_language'),
                'id' => 'theme_main_radius',
                'default' => 8,
                'type'        => 'spinner',
                'min'         => 0,
                'max'         => 15,
                'step'        => 1,
                'unit'        => 'PX',
            ),
            array(
                'title' => __('全局动画'),
                'subtitle' => __('', 'zib_language'),
                'id' => 'qj_loading',
                'type' => "switcher",
                'default' => false,
            ),
            array(
                'id' => 'qj_dh_xs',
                'title' => ' ',
                'subtitle' => '页面全局加载loading动画',
                'default' => 'no1',
                'class' => 'compact',
                'desc' => '网络不好，或显示不正常请关闭！',
                'type' => 'select',
                'options' => array(
                    'no1' => __('淡出淡入', 'zib_language'),
                    'no2' => __('动画2', 'zib_language'),
                    'no3' => __('动画3', 'zib_language'),
                    'no4' => __('动画4', 'zib_language'),
                    'no5' => __('动画5', 'zib_language'),
                    'no6' => __('动画6', 'zib_language'),
                    'no7' => __('动画7', 'zib_language'),
                    'no8' => __('动画8', 'zib_language'),
                    'no9' => __('动画9', 'zib_language'),
                    'no10' => __('动画10', 'zib_language')
                )
            ),
        )
    ));


    CSF::createSection($prefix, array(
        'parent'      => 'basic',
        'title'       => 'Email邮件',
        'icon'        => 'fa fa-fw fa-envelope-o',
        'description' => '',
        'fields'      => array(
            array(
                'title' => __('管理员邮件推送', 'zib_language'),
                'subtitle' => '链接提交邮件',
                'label' => '前台有新的链接提交 向管理员发送邮件',
                'id' => 'email_links_submit_to_admin',
                'type' => 'switcher',
                'default' => true,
                'desc' => ''
            ),

            array(
                'id' => 'email_payment_order_to_admin',
                'class' => 'compact',
                'type' => 'switcher',
                'default' => true,
                'title' => ' ',
                'subtitle' => '新订单邮件',
                'label' => '用户支付订单后 向管理员发送邮件'
            ),

            array(
                'dependency' => array('pay_rebate_s', '!=', '', 'all'),
                'id' => 'email_apply_withdraw_to_admin',
                'class' => 'compact',
                'type' => 'switcher',
                'default' => true,
                'title' => ' ',
                'subtitle' => '佣金提现邮件',
                'label' => '用户申请佣金提现 向管理员发送邮件'
            ),
            array(
                'title' => __('用户邮件推送', 'zib_language'),
                'id' => 'email_payment_order',
                'type' => 'switcher',
                'default' => true,
                'subtitle' => '新订单邮件',
                'label' => '用户支付订单后 向用户发送邮件'
            ),
            array(
                'dependency' => array('pay_rebate_s', '!=', '', 'all'),
                'id' => 'email_withdraw_process',
                'class' => 'compact',
                'type' => 'switcher',
                'default' => true,
                'title' => ' ',
                'subtitle' => '提现通知邮件',
                'label' => '处理用户提现申请后 向用户发送邮件'
            ),

            array(
                'dependency' => array('pay_rebate_s', '!=', '', 'all'),
                'id' => 'email_payment_order_to_referrer',
                'class' => 'compact',
                'type' => 'switcher',
                'decs' => __('当订单有返佣时，向推荐人发送订单及佣金信息', 'zib_language'),
                'default' => true,
                'title' => ' ',
                'subtitle' => '佣金通知邮件',
                'label' => '用户支付订单后 向推荐人发送邮件'
            ),
            array(
                'id' => 'email_comment_approved',
                'class' => 'compact',
                'type' => 'switcher',
                'default' => true,
                'title' => ' ',
                'subtitle' => '评论通知邮件',
                'label' => '评论通过审核后 向用户发送邮件'
            ),
            array(
                'id' => 'email_newpost_to_publish',
                'class' => 'compact',
                'type' => 'switcher',
                'default' => true,
                'title' => ' ',
                'subtitle' => '投稿通知邮件',
                'label' => '投稿通过审核后 向用户发送邮件'
            ),

            array(
                'title' => __('自定义发件人', 'zib_language'),
                'id' => 'mail_showname',
                'desc' => '自定义邮件发件人昵称（仅部分邮箱服务器有效）',
                'default' => get_bloginfo('title'),
                'type' => 'text'
            ),
            array(
                'title' => __('添加邮件内容', 'zib_language'),
                'subtitle' => __('额外内容一', 'zib_language'),
                'desc' => '建议为本站简介，请注意控制字数，此处内容最多显示三行',
                'id' => 'mail_description',
                'default' => '此信为系统邮件，请不要直接回复。',
                'sanitize' => false,
                'type' => 'textarea',
                'attributes' => array(
                    'rows' => 3
                ),
            ),
            array(
                'title' => ' ',
                'subtitle' => __('额外内容二', 'zib_language'),
                'desc' => '建议为其它链接，请注意控制字数，此处内容最多显示一行</br>支持HTML代码，请注意代码规范及标签闭合</br>由于不同邮件服务商的代码支持不同，请使用较为基础的html代码',
                'class' => 'compact',
                'id' => 'mail_more_content',
                'default' => '<a href="' . get_bloginfo('url') . '">访问网站</a> |
<a href="#">联系站长</a>',
                'sanitize' => false,
                'type' => 'textarea',
                'attributes' => array(
                    'rows' => 2
                ),
            ),
            array(
                'title' => '邮件SMTP',
                'id' => 'mail_smtps',
                'type' => 'switcher',
                'default' => false,
            ),
            array(
                'dependency' => array('mail_smtps', '!=', ''),
                'type'    => 'submessage',
                'style'   => 'warning',
                'content' => 'WordPress配置SMTP邮箱，解决邮件发送问题。功能和SMTP插件一致，所以！不能和其他SMTP插件一起开启！同时请注意开启服务器对应的端口！ <a target="_blank" href="https://www.zibll.com/720.html" class="loginbtn">查看官网教程</a>',
            ),

            array(
                'dependency' => array('mail_smtps', '!=', ''),
                'title' => 'SMTP配置',
                'subtitle' => '发信人邮箱账号',
                'class' => 'compact',
                'id' => 'mail_name',
                'class' => 'compact-heading',
                'default' => '88888888@qq.com',
                'validate' => 'csf_validate_email',
                'type' => 'text'
            ),

            array(
                'dependency' => array('mail_smtps', '!=', ''),
                'id' => 'mail_passwd',
                'class' => 'compact',
                'title' => 'SMTP服务邮箱密码',
                'desc' => '此密码非邮箱密码，一般需要单独开启',
                'default' => '',
                'type' => 'text'
            ),

            array(
                'dependency' => array('mail_smtps', '!=', ''),
                'id' => 'mail_host',
                'class' => 'compact',
                'title' => '邮件服务器地址',
                'default' => 'smtp.qq.com',
                'type' => 'text'
            ),

            array(
                'dependency' => array('mail_smtps', '!=', ''),
                'id' => 'mail_port',
                'class' => 'compact',
                'title' => 'SMTP服务器端口',
                'default' => '465',
                'type' => 'number'
            ),

            array(
                'dependency' => array('mail_smtps', '!=', ''),
                'title' => 'SMTPAuth服务',
                'id' => 'mail_smtpauth',
                'type' => 'switcher',
                'class' => 'compact',
                'default' => true,
            ),

            array(
                'dependency' => array('mail_smtps', '!=', ''),
                'title' => '加密方式（SMTPSecure）',
                'id' => 'mail_smtpsecure',
                'class' => 'compact',
                'default' => 'ssl',
                'type' => 'text'
            ),
            CFS_Module::email_test(),
        ),
    ));

    CSF::createSection($prefix, array(
        'parent'      => 'page',
        'title'       => '顶部导航',
        'icon'        => 'fa fa-fw fa-navicon',
        'description' => '',
        'fields'      => array(
            array(
                'title' => __("自定义导航栏颜色", 'zib_language'),
                'id' => 'header_theme_custom',
                'type' => 'switcher',
                'default' => false,
            ),

            array(
                'dependency' => array('header_theme_custom', '!=', ''),
                'class' => 'compact',
                'title' => ' ',
                'subtitle' => '导航栏：背景色',
                'id' => 'header_theme_bg_custom',
                'default' => '',
                'desc' => __('如需选择预置颜色，请先清空上方颜色', 'zib_language'),
                'type' => "color"
            ),
            array(
                'dependency' => array('header_theme_bg_custom|header_theme_custom', '==|!=', '|'),
                'id' => "header_theme_bg",
                'title' => ' ',
                'default' => '',
                'type' => "palette",
                'class' => 'compact skin-color',
                'options' => array(
                    'ff648f'  => array('#ff648f'),
                    'c246f5' => array('#c246f5'),
                    '469cf5' => array('#469cf5'),
                    '27bf41' => array('#27bf41'),
                    'fd6b4e' => array('#fd6b4e'),
                    '2d2422' => array('#2d2422'),
                )
            ),

            array(
                'dependency' => array('header_theme_custom', '!=', ''),
                'id' => 'header_theme_color_custom',
                'title' => ' ',
                'subtitle' => '导航栏：前景色',
                'default' => '',
                'class' => 'compact',
                'desc' => __('请注意背景色和前景色的搭配，以免文字看不清', 'zib_language'),
                'type' => "color"
            ),

            array(
                'title' => __('电脑端导航布局', 'zib_language'),
                'id' => 'header_layout',
                'default' => "1",
                'type' => "image_select",
                'options' => array(
                    '1' => $f_imgpath . 'header_layout_1.png',
                    '2' => $f_imgpath . 'header_layout_2.png',
                    '3' => $f_imgpath . 'header_layout_3.png',
                )
            ),
            array(
                'title' => __('移动端导航布局', 'zib_language'),
                'id' => 'mobile_header_layout',
                'default' => "center",
                'type' => "image_select",
                'options' => array(
                    'center' => $f_imgpath . 'mobile_header_layout_center.png',
                    'left' => $f_imgpath . 'mobile_header_layout_left.png',
                )
            ),


            array(
                'id' => 'mobile_navbar_align',
                'default' => 'left',
                'type' => 'radio',
                'title' => '移动端菜单弹出方向',
                'inline' => true,
                'options' => array(
                    'top' => __('顶部', 'zib_language'),
                    'left' => __('左边', 'zib_language'),
                    'right' => __('右边', 'zib_language')
                )
            ),

            array(
                'title' => '顶部搜索',
                'id' => 'header_search_popular_key',
                'subtitle' => '显示热门搜索关键词',
                'default' => true,
                'type' => 'switcher'
            ),

            array(
                'id' => 'header_search_history_key',
                'title' => ' ',
                'class' => 'compact',
                'subtitle' => '显示用户搜索历史关键词',
                'default' => true,
                'type' => 'switcher'
            ),

            array(
                'id' => 'header_search_posts',
                'title' => ' ',
                'class' => 'compact',
                'subtitle' => '显示热门文章',
                'default' => false,
                'type' => 'switcher'
            ),
            array(
                'id' => 'header_search_cat',
                'title'  => ' ',
                'subtitle' => '分类搜索',
                'subtitle' => '显示分类选择',
                'class' => 'compact',
                'default' => true,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('header_search_cat', '!=', ''),
                'id' => 'header_search_cat_in',
                'title' => ' ',
                'class' => 'compact',
                'default' => '',
                'options' => 'categories',
                'placeholder' => '选择分类',
                'subtitle' => '默认已选择的分类',
                'chosen' => true,
                'type' => 'select'
            ),
            array(
                'dependency' => array('header_search_cat', '!=', ''),
                'id' => 'header_search_more_cat',
                'title' => ' ',
                'subtitle' => '显示更多分类下拉列表',
                'class' => 'compact',
                'default' => true,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('header_search_cat', '!=', ''),
                'id' => 'header_search_more_cat_obj',
                'title' => ' ',
                'default' => '',
                'class' => 'compact',
                'desc' => '搜索时在下拉菜单中允许选择的更多分类列表',
                'placeholder' => '选择允许的更多分类',
                'options' => 'categories',
                'type' => 'select',
                'chosen' => true,
                'multiple' => true,
                'sortable' => true,
            ),

            //待处理-》允许添加的额外按钮
            array(
                'title' => '投稿按钮',
                'subtitle' => '顶部导航显示写文章按钮',
                'id' => 'nav_newposts',
                'type' => 'switcher',
                'help' => '请先在个人中心中开启投稿功能，并设置好投稿页面',
                'default' => true,
            ),

            array(
                'dependency' => array('nav_newposts', '!=', ''),
                'title' => ' ',
                'subtitle' => '投稿按钮文案',
                'class' => 'compact',
                'id' => 'nav_newposts_text',
                'sanitize' => false,
                'type' => 'textarea',
                'attributes' => array(
                    'rows' => 1
                ),
                'help' => '请注意控制字数，不建议超过4个字符',
                'default' => '<i class="fa fa-fw fa-pencil"></i>写文章',
            ),

            array(
                'title' => __('导航浮动', 'zib_language'),
                'subtitle' => __('导航一直固定在顶部'),
                'id' => 'nav_fixed',
                'type' => 'switcher',
                'default' => true,
            ),

        )
    ));
    CSF::createSection($prefix, array(
        'parent'      => 'page',
        'title'       => '导航幻灯片',
        'icon'        => 'fa fa-fw fa-image',
        'description' => '',
        'fields'      => array(
            array(
                'id' => 'header_slider_show',
                'title' => '开启导航幻灯片',
                'default' => array(),
                'desc' => '选择开启导航幻灯片的页面类型，一个都不选则为关闭此功能<br>显示在顶部导航栏的全宽幻灯片，功能和幻灯片小工具模块一致',
                'placeholder' => '选择开启导航幻灯片的页面类型',
                'options' => CFS_Module::page_type(),
                'type' => 'select',
                'chosen' => true,
                'multiple' => true,
            ),
            array(
                'dependency' => array('header_slider_show', '!=', ''),
                'title' => __("显示规则", 'zib_language'),
                'subtitle' => '导航栏幻灯片的显示规则',
                'id' => 'header_slider_show_type',
                'type' => 'radio',
                'default' => '',
                'options' => array(
                    '' => '全部显示',
                    'only_pc' => '仅在PC端显示',
                    'only_sm' => '仅在移动端显示',
                ),
            ),
            array(
                'dependency' => array('header_slider_show', '!=', '', '', 'visible'),
                'id'     => 'header_slider',
                'type'   => 'group',
                'min'   => '1',
                'button_title' => '添加幻灯片',
                'title'  => '幻灯片内容',
                'subtitle' => '添加导航栏幻灯片',
                'default'   => array(
                    array(
                        'background' => $imagepath . 'slider-bg.jpg',
                        'image_layer'  => array(
                            array(
                                'image' => $imagepath . 'slider-layer-1.png',
                                'align'  => 'center',
                                'free_size'  => true,
                                'parallax'  => -100,
                                'parallax_scale'  => 180,
                                'parallax_opacity'  => 30,
                            ),
                            array(
                                'image' => $imagepath . 'slider-layer-2.png',
                                'align'  => 'center',
                                'free_size'  => true,
                                'parallax'  => -50,
                                'parallax_scale'  => 80,
                                'parallax_opacity'  => 100,
                            )
                        ),
                        'link'  => array(
                            'url' => 'https://www.zibll.com/',
                            'target' => '_blank',
                        ),
                        'text'  => array(
                            'desc'  => '',
                            'title'  => '',
                        ),
                        'text_align'  => 'left-bottom',
                        'text_parallax'  => 30,
                        'text_size_m'  => 20,
                        'text_size_pc'  => 30,
                    ),
                ),
                'fields' => CFS_Module::add_slider(),
            ),
            array(
                'dependency' => array('header_slider_show', '!=', ''),
                'id'            => 'header_slider_option',
                'type'          => 'fieldset',
                'title'         => '幻灯片设置',
                'subtitle' => '导航栏幻灯片设置',
                'default'   => array(
                    'direction'  => 'horizontal',
                    'loop'  => true,
                    'button'  => true,
                    'pagination'  => true,
                    'effect'  => 'slide',
                    'auto_height'  => false,
                    'pc_height'  => 500,
                    'm_height'  => 240,
                    'spacebetween'  => 15,
                    'speed'  => 1000,
                    'autoplay' => true,
                    'interval'  => 4,
                ),
                'fields'    => CFS_Module::slide(),
            ),
        )
    ));

    //底部页脚
    CSF::createSection($prefix, array(
        'parent'      => 'page',
        'title'       => '底部页脚',
        'icon'        => 'fa fa-fw fa-minus-square-o',
        'description' => '',
        'fields'      => array(

            array(
                'title' => __("自定义底部页脚颜色", 'zib_language'),
                'id' => 'footer_theme_custom',
                'type' => 'switcher',
                'default' => false,
            ),

            array(
                'dependency' => array('footer_theme_custom', '!=', ''),
                'title' => ' ',
                'class' => 'compact',
                'subtitle' => '底部页脚：背景色',
                'default' => '',
                'id' => 'footer_theme_bg_custom',
                'desc' => __('如需选择预置颜色，请先清空上方颜色', 'zib_language'),
                'type' => "color"
            ),
            array(
                'dependency' => array('footer_theme_bg_custom|footer_theme_custom', '==|!=', '|'),
                'id' => "footer_theme_bg",
                'title' => ' ',
                'type' => "palette",
                'default' => '',
                'class' => 'compact skin-color',
                'options' => array(
                    'ff648f'  => array('#ff648f'),
                    'c246f5' => array('#c246f5'),
                    '469cf5' => array('#469cf5'),
                    '27bf41' => array('#27bf41'),
                    'fd6b4e' => array('#fd6b4e'),
                    '2d2422' => array('#2d2422'),
                )
            ),

            array(
                'dependency' => array('footer_theme_custom', '!=', ''),
                'id' => 'footer_theme_color_custom',
                'title' => ' ',
                'default' => '',
                'subtitle' => '底部页脚：前景色',
                'desc' => __('请注意背景色和前景色的搭配，以免文字看不清', 'zib_language'),
                'class' => 'compact',
                'type' => "color"
            ),

            array(
                'title' => __('页脚布局模板选择', 'zib_language'),
                'id' => 'fcode_template',
                'default' => "template_1",
                'help' => '由于页脚布局及样式种类繁多，更多模板正在开发中。后续也会发布可视化编辑功能',
                'type' => "image_select",
                'options' => array(
                    'template_1' => $f_imgpath . 'fcode_template_1.png',
                )
            ),

            array(
                'title' => __('板块一设置', 'zib_language'),
                'id' => 'footer_t1_m_s',
                'help' => '如果不勾选则仅仅在电脑端显示此板块',
                'type' => 'switcher',
                'default' => false,
                'label' => __('移动端显示', 'zib_language')
            ),
            array(
                'title' => ' ',
                'subtitle' => '日间模式图片',
                'id' => 'footer_t1_img',
                'class' => 'compact',
                'default' => $imagepath . 'logo.png',
                'library' => 'image', 'type' => 'upload'
            ),

            array(
                'title' => ' ',
                'subtitle' => '夜间模式图片',
                'id' => 'footer_t1_img_dark',
                'class' => 'compact',
                'default' => $imagepath . 'logo_dark.png',
                'library' => 'image', 'type' => 'upload'
            ),

            array(
                'title' => ' ',
                'subtitle' => '首行文字',
                'id' => 'footer_t1_t',
                'class' => 'compact',
                'default' => '',
                'type' => 'text'
            ),

            array(
                'title' => ' ',
                'subtitle' => '更多内容',
                'id' => 'fcode_t1_code',
                'class' => 'compact',
                'default' => 'Zibll 子比主题专为博客、自媒体、资讯类的网站设计开发，简约优雅的设计风格，全面的前端用户功能，简单的模块化配置，欢迎您的体验',
                'sanitize' => false,
                'type' => 'textarea',
                'attributes' => array(
                    'rows' => 3
                ),
            ),

            array(
                'title' => __('板块二', 'zib_language'),
                'subtitle' => __('第一行(建议为友情链接，或者站内链接)', 'zib_language'),
                'id' => 'fcode_t2_code_1',
                'default' => '<a href="https://zibll.com">友链申请</a>
<a href="https://zibll.com">免责声明</a>
<a href="https://zibll.com">广告合作</a>
<a href="https://zibll.com">关于我们</a>',
                'sanitize' => false,
                'type' => 'textarea',
                'attributes' => array(
                    'rows' => 4
                ),
            ),

            array(
                'title' => ' ',
                'subtitle' => __('第二行(建议为版权提醒，备案号等)', 'zib_language'),
                'id' => 'fcode_t2_code_2',
                'class' => 'compact',
                'default' => 'Copyright &copy;&nbsp;' . date('Y') . '&nbsp;·&nbsp;<a href="' . home_url() . '">' . get_bloginfo('title') . '</a>&nbsp;·&nbsp;由<a target="_blank" href="https://zibll.com">Zibll主题</a>强力驱动.',
                'sanitize' => false,
                'type' => 'textarea',
                'attributes' => array(
                    'rows' => 3
                ),
            ),

            array(
                'title' => __('联系方式', 'zib_language'),
                'id' => 'footer_contact_m_s',
                'class' => '',
                'type' => 'switcher',
                'default' => true,
                'label' => __('移动端显示')
            ),

            array(
                'id' => 'footer_contact_wechat_img',
                'class' => 'compact',
                'title' => ' ',
                'subtitle' => __('微信二维码', 'zib_language'),
                'default' => $imagepath . 'qrcode.png',
                'library' => 'image', 'type' => 'upload'
            ),

            array(
                'title' => ' ',
                'subtitle' => __('QQ号', 'zib_language'),
                'id' => 'footer_contact_qq',
                'class' => 'compact',
                'default' => '1234567788',
                'type' => 'text'
            ),

            array(
                'title' => ' ',
                'subtitle' => __('微博链接', 'zib_language'),
                'id' => 'footer_contact_weibo',
                'class' => 'compact',
                'default' => 'https://weibo.com/',
                'type' => 'text'
            ),

            array(
                'title' => ' ',
                'subtitle' => __('邮箱', 'zib_language'),
                'id' => 'footer_contact_email',
                'class' => 'compact',
                'default' => '1234567788@QQ.COM',
                'type' => 'text'
            ),

            array(
                'title' => __('板块三', 'zib_language'),
                'id' => 'footer_mini_img_m_s',
                'class' => '',
                'type' => 'switcher',
                'default' => true,
                'label' => __('移动端显示')
            ),

            array(
                'id'     => 'footer_mini_img',
                'type'   => 'group',
                'max'   => 4,
                'button_title' => '添加图片',
                'class' => 'compact',
                'title'  => '页脚图片',
                'placeholder' => '显示在板块3的图片内容',
                'default'   => array(
                    array(
                        'image' => $imagepath . 'qrcode.png',
                        'text' => '扫码加QQ群',
                    ),
                    array(
                        'image' => $imagepath . 'qrcode.png',
                        'text' => '扫码加微信',
                    ),
                ),
                'fields' => array(
                    array(
                        'id' => 'text',
                        'title' => __('显示文字', 'zib_language'),
                        'type' => 'text'
                    ),
                    array(
                        'id' => 'image',
                        'title' => __('显示图片', 'zib_language'),
                        'library' => 'image', 'type' => 'upload'
                    ),
                ),
            ),

            array(
                'title' => __('页脚自定义HTML', 'zib_language'),
                'desc' => __('最底部额外的自定义代码（支持HTML）', 'zib_language'),
                'id' => 'fcode_customize_code',
                'default' => '',
                'sanitize' => false,
                'type' => 'textarea',
                'attributes' => array(
                    'rows' => 3
                ),
            ),

        ),
    ));

    //主题显示
    CSF::createSection($prefix, array(
        'parent'      => 'page',
        'title'       => '首页栏目',
        'icon'        => 'fa fa-fw fa-home',
        'description' => '',
        'fields'      => array(
            array(
                'type'    => 'submessage',
                'style'   => 'warning',
                'content' => '首页主文章模块。关闭则首页不显示主文章模块，但仍可通过模块添加',
            ),
            array(
                'title' => __('首页文章', 'zib_language'),
                'id' => 'home_posts_list_s',
                'default' => true,
                'help' => '',
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('home_posts_list_s', '!=', '', '', 'visible'),
                'id' => 'home_list1_orderby_s',
                'title' => __('栏目1设置', 'zib_language'),
                'subtitle' => '显示最新文章的主文章栏目',
                'class' => '',
                'default' => true,
                'label' => __('显示排序方式按钮', 'zib_language'),
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('home_posts_list_s|home_list1_orderby_s', '!=|!=', '|'),
                'id'        => 'home_list1_orderby_option',
                'type'      => 'fieldset',
                'class' => 'compact',
                'default' => '',
                'title'     => ' ',
                'subtitle' => '排序方式设置',
                'fields'    => CFS_Module::orderby(),
            ),
            array(
                'dependency' => array('home_posts_list_s', '!=', '', '', 'visible'),
                'title'  => ' ',
                'subtitle' => '显示标题',
                'help' => '当没有更多栏目时，允许为空',
                'class' => 'compact',
                'id' => 'index_list_title',
                'default' => __('最新发布', 'zib_language'),
                'attributes' => array(
                    'rows' => 1
                ),
                'sanitize' => false,
                'type' => 'textarea'
            ),
            array(
                'dependency' => array('home_posts_list_s', '!=', '', '', 'visible'),
                'id' => 'home_exclude_posts',
                'class' => 'compact',
                'title'  => ' ',
                'subtitle' => __('排除文章', 'zib_language'),
                'desc' => __('输入文章标题关键词以搜索文章', 'zib_language'),
                'default' => '',
                'options' => 'posts',
                'placeholder' => '输入文章标题关键词以搜索文章',
                'chosen'      => true,
                'ajax'        => true,
                'multiple'    => true,
                'settings'  => array(
                    'min_length'  => 2
                ),
                'type' => 'select'
            ),
            array(
                'dependency' => array('home_posts_list_s', '!=', '', '', 'visible'),
                'id' => 'home_exclude_cats',
                'class' => 'compact',
                'title'  => ' ',
                'subtitle' => __('排除分类', 'zib_language'),
                'desc' => '输入关键词以搜索分类',
                'default' => '',
                'options' => 'categories',
                'placeholder' => '输入关键词以搜索分类',
                'chosen'      => true,
                'ajax'        => true,
                'multiple'    => true,
                'settings'  => array(
                    'min_length'  => 2
                ),
                'type' => 'select'
            ),
            array(
                'dependency' => array('home_posts_list_s', '!=', '', '', 'visible'),
                'id'     => 'home_lists',
                'type'   => 'group',
                'accordion_title_number'        => true,
                'button_title' => '添加栏目',
                'title'  => '更多栏目',
                'default' => array(),
                'fields' => array(
                    array(
                        'id' => 'title',
                        'title' => '自定义标题',
                        'desc' => '自定义标题为空，则显示所选择分类、专题的名称（支持HTML代码）| <a href="' . admin_url('edit-tags.php?taxonomy=category') . '">管理分类</a> | <a href="' . admin_url('edit-tags.php?taxonomy=topics') . '">管理专题</a>',
                        'attributes' => array(
                            'rows' => 1
                        ),
                        'sanitize' => false,
                        'type' => 'textarea'
                    ),
                    array(
                        'id' => 'term_id',
                        'title' => '显示分类文章',
                        'class' => 'compact',
                        'options' => 'terms_options',
                        'type' => 'select'
                    ),
                ),
            ),
        ),
    ));

    //分类页面
    CSF::createSection($prefix, array(
        'parent'      => 'page',
        'title'       => '分类页面',
        'icon'        => 'fa fa-fw fa-folder-open-o',
        'description' => '',
        'fields'      => array(
            array(
                'title' => __('显示封面图', 'zib_language'),
                'id' => 'page_cover_cat_s',
                'type' => 'switcher',
                'default' => true,
            ),
            array(
                'dependency' => array('page_cover_cat_s', '!=', ''),
                'title' => __('封面图', 'zib_language'),
                'subtitle' => __('默认封面图，建议尺寸1600X1100'),
                'id' => 'cat_default_cover',
                'default' => $imagepath . 'user_t.jpg',
                'help' => '显示页面顶部的封面图像，你可以在分类设置中单独设置每一个分类的封面图，如未设置则显示此图像',
                'library' => 'image', 'type' => 'upload'
            ),
            array(
                'title' => __('排序方式按钮', 'zib_language'),
                'subtitle' => __('在分类页显示排序方式按钮', 'zib_language'),
                'id' => 'cat_orderby_s',
                'type' => 'switcher',
                'default' => true,
            ),
            array(
                'dependency' => array('cat_orderby_s', '!=', ''),
                'id'        => 'cat_orderby_option',
                'type'      => 'fieldset',
                'default' => array(),
                'class' => 'compact',
                'title'     => ' ',
                'fields'    => CFS_Module::orderby(),
            ),
            array(
                'content' => '<b>AJAX菜单列表:</b> 显示分类、标签、专题的菜单，通过ajax获取内容',
                'type' => 'submessage',
                'style'   => 'warning',
            ),
            array(
                'title' => __('AJAX分类菜单列表', 'zib_language'),
                'subtitle' => __('在分类页显示的分类菜单列表', 'zib_language'),
                'id' => 'ajax_list_cat_cat',
                'type' => 'switcher',
                'default' => true,
            ),
            array(
                'dependency' => array('ajax_list_cat_cat', '!=', ''),
                'id'        => 'ajax_list_option_cat_cat',
                'type'      => 'fieldset',
                'default' => array(),
                'class' => 'compact',
                'title'     => ' ',
                'fields'    => CFS_Module::ajax_but('categories'),
            ),
            array(
                'title' => __('AJAX标签菜单列表', 'zib_language'),
                'subtitle' => __('在分类页显示的标签菜单列表', 'zib_language'),
                'id' => 'ajax_list_cat_tag',
                'type' => 'switcher',
                'default' => false,
            ),
            array(
                'dependency' => array('ajax_list_cat_tag', '!=', ''),
                'id'        => 'ajax_list_option_cat_tag',
                'type'      => 'fieldset',
                'default' => array(),
                'class' => 'compact',
                'title'     => ' ',
                'fields'    => CFS_Module::ajax_but('tags'),
            ),
            array(
                'title' => __('AJAX专题菜单列表', 'zib_language'),
                'subtitle' => __('在分类页显示的专题菜单列表', 'zib_language'),
                'id' => 'ajax_list_cat_topics',
                'type' => 'switcher',
                'default' => false,
            ),
            array(
                'dependency' => array('ajax_list_cat_topics', '!=', ''),
                'id'        => 'ajax_list_option_cat_topics',
                'type'      => 'fieldset',
                'default' => array(),
                'class' => 'compact',
                'title'     => ' ',
                'fields'    => CFS_Module::ajax_but('topics'),
            ),


        ),
    ));


    //标签页面
    CSF::createSection($prefix, array(
        'parent'      => 'page',
        'title'       => '标签页面',
        'icon'        => 'fa fa-fw fa-tags',
        'description' => '',
        'fields'      => array(
            array(
                'title' => __('显示封面图', 'zib_language'),
                'id' => 'page_cover_tag_s',
                'type' => 'switcher',
                'default' => true,
            ),
            array(
                'dependency' => array('page_cover_tag_s', '!=', ''),
                'title' => __('封面图', 'zib_language'),
                'subtitle' => __('默认封面图，建议尺寸1600X1100'),
                'id' => 'tag_default_cover',
                'default' => $imagepath . 'user_t.jpg',
                'help' => '显示页面顶部的封面图像，你可以在标签设置中单独设置每一个标签的封面图，如未设置则显示此图像',
                'library' => 'image', 'type' => 'upload'
            ),
            array(
                'title' => __('排序方式按钮', 'zib_language'),
                'subtitle' => __('在标签页显示排序方式按钮', 'zib_language'),
                'id' => 'tag_orderby_s',
                'type' => 'switcher',
                'default' => true,
            ),
            array(
                'dependency' => array('tag_orderby_s', '!=', ''),
                'id'        => 'tag_orderby_option',
                'type'      => 'fieldset',
                'default' => array(),
                'class' => 'compact',
                'title'     => ' ',
                'fields'    => CFS_Module::orderby(),
            ),
            array(
                'content' => '<b>AJAX菜单列表:</b> 显示分类、标签、专题的菜单，通过ajax获取内容',
                'type' => 'submessage',
                'style'   => 'warning',
            ),

            array(
                'title' => __('AJAX分类菜单列表', 'zib_language'),
                'subtitle' => __('在标签页显示的分类菜单列表', 'zib_language'),
                'id' => 'ajax_list_tag_cat',
                'type' => 'switcher',
                'default' => false,
            ),
            array(
                'dependency' => array('ajax_list_tag_cat', '!=', ''),
                'id'        => 'ajax_list_option_tag_cat',
                'type'      => 'fieldset',
                'default' => array(),
                'class' => 'compact',
                'title'     => ' ',
                'fields'    => CFS_Module::ajax_but('categories'),
            ),
            array(
                'title' => __('AJAX标签菜单列表', 'zib_language'),
                'subtitle' => __('在标签页显示的标签菜单列表', 'zib_language'),
                'id' => 'ajax_list_tag_tag',
                'type' => 'switcher',
                'default' => true,
            ),
            array(
                'dependency' => array('ajax_list_tag_tag', '!=', ''),
                'id'        => 'ajax_list_option_tag_tag',
                'type'      => 'fieldset',
                'default' => array(),
                'class' => 'compact',
                'title'     => ' ',
                'fields'    => CFS_Module::ajax_but('tags'),
            ),
            array(
                'title' => __('AJAX专题菜单列表', 'zib_language'),
                'subtitle' => __('在标签页显示的专题菜单列表', 'zib_language'),
                'id' => 'ajax_list_tag_topics',
                'type' => 'switcher',
                'default' => false,
            ),
            array(
                'dependency' => array('ajax_list_tag_topics', '!=', ''),
                'id'        => 'ajax_list_option_tag_topics',
                'type'      => 'fieldset',
                'default' => array(),
                'class' => 'compact',
                'title'     => ' ',
                'fields'    => CFS_Module::ajax_but('topics'),
            ),

        ),
    ));
    CSF::createSection($prefix, array(
        'parent'      => 'page',
        'title'       => '专题页面',
        'icon'        => 'fa fa-fw fa-cube',
        'description' => '',
        'fields'      => array(
            array(
                'title' => __('封面图', 'zib_language'),
                'subtitle' => __('默认封面图，建议尺寸1600X1100'),
                'id' => 'topics_default_cover',
                'default' => $imagepath . 'user_t.jpg',
                'help' => '显示页面顶部的封面图像，你可以在专题设置中单独设置每一个专题的封面图，如未设置则显示此图像',
                'library' => 'image', 'type' => 'upload'
            ),
            array(
                'title' => __('排序方式按钮', 'zib_language'),
                'subtitle' => __('在专题页显示排序方式按钮', 'zib_language'),
                'id' => 'topics_orderby_s',
                'type' => 'switcher',
                'default' => true,
            ),
            array(
                'dependency' => array('topics_orderby_s', '!=', ''),
                'id'        => 'topics_orderby_option',
                'type'      => 'fieldset',
                'default' => array(),
                'class' => 'compact',
                'title'     => ' ',
                'fields'    => CFS_Module::orderby(),
            ),
            array(
                'content' => '<b>AJAX菜单列表:</b> 显示分类、标签、专题的菜单，通过ajax获取内容',
                'type' => 'submessage',
                'style'   => 'warning',
            ),

            array(
                'title' => __('AJAX分类菜单列表', 'zib_language'),
                'subtitle' => __('在专题页显示的分类菜单列表', 'zib_language'),
                'id' => 'ajax_list_topics_cat',
                'type' => 'switcher',
                'default' => false,
            ),
            array(
                'dependency' => array('ajax_list_topics_cat', '!=', ''),
                'id'        => 'ajax_list_option_topics_cat',
                'default' => array(),
                'type'      => 'fieldset',
                'class' => 'compact',
                'title'     => ' ',
                'fields'    => CFS_Module::ajax_but('categories'),
            ),
            array(
                'title' => __('AJAX标签菜单列表', 'zib_language'),
                'subtitle' => __('在专题页显示的标签菜单列表', 'zib_language'),
                'id' => 'ajax_list_topics_tag',
                'type' => 'switcher',
                'default' => false,
            ),
            array(
                'dependency' => array('ajax_list_topics_tag', '!=', ''),
                'id'        => 'ajax_list_option_topics_tag',
                'type'      => 'fieldset',
                'default' => array(),
                'class' => 'compact',
                'title'     => ' ',
                'fields'    => CFS_Module::ajax_but('tags'),
            ),
            array(
                'title' => __('AJAX专题菜单列表', 'zib_language'),
                'subtitle' => __('在专题页显示的专题菜单列表', 'zib_language'),
                'id' => 'ajax_list_topics_topics',
                'type' => 'switcher',
                'default' => true,
            ),
            array(
                'dependency' => array('ajax_list_topics_topics', '!=', ''),
                'id'        => 'ajax_list_option_topics_topics',
                'default' => array(),
                'type'      => 'fieldset',
                'class' => 'compact',
                'title'     => ' ',
                'fields'    => CFS_Module::ajax_but('topics'),
            ),
        ),
    ));

    CSF::createSection($prefix, array(
        'parent'      => 'page',
        'title'       => '其它页面',
        'icon'        => 'fa fa-fw fa-clone',
        'description' => '',
        'fields'      => array(
            array(
                'title' => '页面标题',
                'subtitle' => '页面标题的默认显示样式',
                'id' => 'page_header_style',
                'default' => '',
                'type' => 'radio',
                'options' => array(
                    '' => __('不显示', 'zib_language'),
                    1 => __('简单样式', 'zib_language'),
                    2 => __('卡片样式', 'zib_language'),
                    3 => __('封面图样式', 'zib_language'),
                )
            ),

            array(
                'title' => '页面封面图',
                'id' => 'page_header_cover_img',
                'desc' => __('页面默认封面图，建议尺寸1000x400（仅页面标题显示为封面图样式时有效）'),
                'help' => '页面也单独设置封面图，如未单独设置则显示此图像',
                'default' => $imagepath . 'user_t.jpg',
                'library' => 'image', 'type' => 'upload'
            ),

        )
    ));
    CSF::createSection($prefix, array(
        'parent'      => 'post',
        'title'       => '文章列表',
        'icon'        => 'fa fa-fw fa-file-text-o',
        'description' => '',
        'fields'      => array(
            array(
                'title' => __('默认排序方式', 'zib_language'),
                'subtitle' => '文章列表全局默认排序方式',
                'id' => 'list_orderby',
                'default' => "modified",
                'inline' => true,
                'type' => "radio",
                'options' => array(
                    'date' => __('发布时间'),
                    'modified' => __('更新时间'),
                )
            ),
            array(
                'title' => __('新窗口打开文章', 'zib_language'),
                'id' => 'target_blank',
                'type' => 'switcher',
                'default' => false,
            ),
            array(
                'title' => __('AJAX翻页', 'zib_language'),
                'class' => 'compact',
                'id' => 'paging_ajax_s',
                'type' => 'switcher',
                'help' => '关闭则显示传统翻页按钮',
                'default' => true,
            ),
            array(
                'dependency' => array('paging_ajax_s', '!=', ''),
                'title' => ' ',
                'subtitle' => 'AJAX自动加载',
                'class' => 'compact',
                'id' => 'paging_ajax_ias_s',
                'type' => 'switcher',
                'label' => '页面滚动到列表尽头时，自动加载下一页',
                'default' => true,
            ),
            array(
                'title' => __('列表标题粗体显示', 'zib_language'),
                'class' => 'compact',
                'id' => 'item_heading_bold',
                'type' => 'switcher',
                'default' => false,
            ),
            array(
                'title' => __('显示文章作者', 'zib_language'),
                'class' => 'compact',
                'id' => 'post_list_author',
                'type' => 'switcher',
                'default' => true,
            ),
            array(
                'title' => '列表小部件',
                'subtitle' => '移动端优先显示',
                'id' => 'list_meta_show',
                'default' => "like",
                'type' => "radio",
                'inline' => true,
                'help' => '在移动设备由于显示空间不足，则会隐藏部分部件，此处选择的部件将会一直显示',
                'options' => array(
                    'view' => __('阅读量', 'zib_language'),
                    'like' => __('点赞数', 'zib_language'),
                    'comm' => __('评论', 'zib_language'),
                )
            ),
            array(
                'title' => __('列表样式', 'zib_language'),
                'id' => 'list_show_type',
                'help' => '当文章显示为列表模式时有效',
                'default' => "separate",
                'type' => "image_select",
                'options' => array(
                    'separate' => $f_imgpath . 'list_separate.png',
                    'no_margin' => $f_imgpath . 'list_no_margin.png',
                )
            ),
            array(
                'title' => __('默认列表模式', 'zib_language'),
                'id' => 'list_type',
                'default' => "thumb",
                'type' => "radio",
                'desc' => '<i class="fa fa-fw fa-info-circle fa-fw"></i> 文字模式、自动图文模式、多图模式仅在开启侧边栏的页面有效',
                'options' => array(
                    'text' => __('列表文字模式', 'zib_language'),
                    'thumb' => __('列表图文模式（无缩略图时使用备用缩略图）', 'zib_language'),
                    'thumb_if_has' => __('列表自动图文模式（无缩略图时自动转换为文字模式） ', 'zib_language'),
                    'card' => __('卡片模式 ', 'zib_language')
                )
            ),
            array(
                'dependency' => array('list_type', '!=', 'card'),
                'title' => __('列表卡片模式'),
                'type'    => 'content',
                'content' => '当默认模式不为卡片模式时，可以在下方单独为不同页面设置为卡片模式',
            ),
            array(
                'dependency' => array('list_type', '!=', 'card'),
                'title' => ' ',
                'class' => 'compact',
                'subtitle' => '首页列表 卡片模式',
                'id' => 'list_card_home',
                'type' => 'switcher',
                'default' => false,
            ),
            array(
                'dependency' => array('list_type', '!=', 'card'),
                'title' => ' ',
                'subtitle' => '标签页列表 卡片模式',
                'class' => 'compact',
                'id' => 'list_card_tag',
                'type' => 'switcher',
                'default' => false,
            ),
            array(
                'dependency' => array('list_type', '!=', 'card'),
                'title' => ' ',
                'subtitle' => '专题页列表 卡片模式',
                'class' => 'compact',
                'id' => 'list_card_topics',
                'type' => 'switcher',
                'default' => false,
            ),
            array(
                'dependency' => array('list_type', '!=', 'card'),
                'title' => ' ',
                'subtitle' => '用户页列表 卡片模式',
                'class' => 'compact',
                'id' => 'list_card_author',
                'type' => 'switcher',
                'default' => false,
            ),

            array(
                'dependency' => array('list_type', '!=', 'card'),
                'id' => 'list_card_cat',
                'title' => ' ',
                'subtitle' => '自定义卡片模式',
                'default' => '',
                'class' => 'compact',
                'desc' => '选择的分类、专题将会在对应页面显示为卡片模式 | <a href="' . admin_url('edit-tags.php?taxonomy=category') . '">管理分类</a> | <a href="' . admin_url('edit-tags.php?taxonomy=topics') . '">管理专题</a>',
                'placeholder' => '选择需要显示为卡片模式的分类',
                'options' => 'terms_options',
                'type' => 'select',
                'chosen' => true,
                'multiple' => true,
            ),

            array(
                'id' => 'mult_thumb_cat',
                'title' => '列表多图显示',
                'subtitle' => '文章列表显示4张缩略图',
                'desc' => '文章格式为“图片、画廊”的文章默认显示为此模式</br>在此选择的分类，该分类的全部文章都会显示为此模式</br><i class="fa fa-fw fa-info-circle fa-fw"></i> 当列表模式为卡片模式或未开启侧边栏时，此显示方式无效',
                'placeholder' => '选择需要显示为多图模式的分类',
                'default' => array(),
                'options' => 'categories',
                'type' => 'select',
                'chosen' => true,
                'multiple' => true,
            ),

            //待处理
            //需要添加文章meta 幻灯片缩略图
            array(
                'id' => 'list_thumb_slides_s',
                'title' => '幻灯片缩略图',
                'label' => '文章格式为“画廊”的文章将显示幻灯片缩略图',
                'type' => 'switcher',
                'default' => true,
            ),

            array(
                'id' => 'thumb_postfirstimg_s',
                'title' => '自动获取缩略图',
                'subtitle' => '使用文章首图为缩略图',
                'type' => 'switcher',
                'default' => true,
            ),

            array(
                'id' => 'thumb_catimg_s',
                'title' => ' ',
                'subtitle' => '使用分类封面为缩略图',
                'class' => 'compact',
                'type' => 'switcher',
                'default' => true,
            ),

            array(
                'id' => 'thumb_postfirstimg_size',
                'title' => '缩略图大小',
                'default' => 'medium',
                'desc' => '此处的三个尺寸均可在<a href="' . admin_url('options-media.php') . '">WP后台-媒体设置</a>中修改，建议此处选择中尺寸，并将中尺寸的尺寸设置为430x300效果最佳',
                'type' => "radio",
                'inline' => true,
                'options' => array(
                    'thumbnail' => __('小尺寸', 'zib_language'),
                    'medium' => __('中尺寸', 'zib_language'),
                    'large' => __('大尺寸', 'zib_language'),
                )
            ),

            array(
                'title' => '默认缩略图',
                'subtitle' => '缩略图预载图',
                'id' => 'thumbnail',
                'class' => 'compact',
                'desc' => '当文章没有任何图像时、以及缩略图加载前显示的图像，建议尺寸450x300</br><i class="fa fa-fw fa-info-circle fa-fw"></i> 缩略图获取优先级：文章特色图像>文章首图>分类封面图>缩略图预载图',
                'default' => $imagepath . 'thumbnail.svg',
                'library' => 'image', 'type' => 'upload'
            ),

            array(
                'title' => 'AJAX翻页',
                'subtitle' => '翻页按钮文字',
                'id' => 'ajax_trigger',
                'default' => '<i class="fa fa-angle-right"></i>加载更多',
                'attributes' => array(
                    'rows' => 1
                ),
                'sanitize' => false,
                'type' => 'textarea'
            ),

            array(
                'title' => ' ',
                'id' => 'ajax_nomore',
                'class' => 'compact',
                'subtitle' => '列表全部加载完毕 文案',
                'default' => '没有更多内容了',
                'desc' => '支持HTML代码，请注意代码规范及标签闭合</br>您可以在<a href="' . esc_url(admin_url('options-reading.php')) . '">WP设置-阅读-博客页面至多显示</a>，以调整单页加载数量',
                'attributes' => array(
                    'rows' => 1
                ),
                'sanitize' => false,
                'type' => 'textarea'
            ),
        ),
    ));

    //主题显示
    CSF::createSection($prefix, array(
        'title'       => '文章页',
        'parent'      => 'post',
        'icon'        => 'fa fa-fw fa-bookmark-o',
        'description' => '',
        'fields'      => array(

            array(
                'title' => '封面幻灯片',
                'desc' => '为格式为"画廊"的文章开启幻灯片封面，会自动将文章内的图片作为幻灯片背景',
                'id' => 'article_slide_cover',
                'type' => 'switcher',
                'default' => true,
            ),
            array(
                'dependency' => array('article_slide_cover', '!=', ''),
                'id'        => 'article_slide_cover_option',
                'type'          => 'accordion',
                'class' => 'compact',
                'default'   => array(
                    'direction'  => 'horizontal',
                    'loop'  => true,
                    'button'  => false,
                    'pagination'  => true,
                    'effect'  => 'slide',
                    'auto_height'  => false,
                    'pc_height'  => 380,
                    'm_height'  => 180,
                    'spacebetween'  => 15,
                    'speed'  => 800,
                    'autoplay' => true,
                    'interval'  => 4,
                ),
                'title' => ' ',
                'accordions'    => array(
                    array(
                        'title' => '封面幻灯片设置',
                        'fields'    => CFS_Module::slide()
                    ),
                ),
            ),
            array(
                'title' => __('内容段落缩进', 'zib_language'),
                'id' => 'post_p_indent_s',
                'type' => 'switcher',
                'help' => '开启后文章内容每一个段落首行将向右偏移2个文字距离',
                'default' => false,
            ),

            array(
                'title' => __('面包屑导航', 'zib_language'),
                'id' => 'breadcrumbs_single_s',
                'class' => 'compact',
                'type' => 'switcher',
                'default' => true,
            ),
            array(
                'dependency' => array('breadcrumbs_single_s', '!=', ''),
                'title' => ' ',
                'subtitle' => __('面包屑导航用“首页”替代网站名称', 'zib_language'),
                'id' => 'breadcrumbs_home_text',
                'class' => 'compact',
                'type' => 'switcher',
                'default' => true,
            ),
            array(
                'title' => __('上一页、下一页板块', 'zib_language'),
                'id' => 'post_prevnext_s',
                'class' => 'compact',
                'type' => 'switcher',
                'default' => true,
            ),
            array(
                'title' => __('作者信息板块', 'zib_language'),
                'id' => 'post_authordesc_s',
                'class' => 'compact',
                'type' => 'switcher',
                'default' => true,
            ),

            array(
                'title' => '内容高度限制',
                'subtitle' => '此处为全局设置，每篇文章可单独设置',
                'id' => 'article_maxheight_kg',
                'label' => '全局开关',
                'default' => false,
                'type' => 'switcher'
            ),

            array(
                'title' => ' ',
                'subtitle' => __('限制的最大高度', 'zib_language'),
                'desc' => '开启后如果文章高度超过设定值则会显示展开阅读全文的按钮。每篇文章可单独开启此功能',
                'id' => 'article_maxheight',
                'class' => 'compact',
                'default' => 1000,
                'max' => 3000,
                'min' => 600,
                'step' => 100,
                'prefix' => '',
                'unit' => 'px',
                'type' => 'slider'
            ),
            array(
                'title' => __('精彩一言功能'),
                'type'    => 'content',
                'content' => '将一言内容插入到文章页位置，如需修改内容，文件地址在：' . get_theme_file_path() . '/yiyan/qv-yiyan.txt',
            ),
            array(
                'title' => ' ',
                'subtitle' => '文章内容头部显示一言',
                'class' => 'compact',
                'id' => 'yiyan_single_content_header',
                'type' => 'switcher',
                'default' => false,
            ),

            array(
                'title' => ' ',
                'subtitle' => '文章内容尾部显示一言',
                'id' => 'yiyan_single_content_footer',
                'class' => 'compact',
                'type' => 'switcher',
                'default' => false,
            ),

            array(
                'title' => ' ',
                'subtitle' => '文章页面下方独立一言板块',
                'id' => 'yiyan_single_box',
                'type' => 'switcher',
                'default' => true,
                'class' => 'compact',
            ),

            array(
                'title' => __('版权声明', 'zib_language'),
                'id' => 'post_copyright_s',
                'type' => 'switcher',
                'default' => true,
            ),

            array(
                'dependency' => array('post_copyright_s', '!=', ''),
                'title' => ' ',
                'subtitle' => __('版权提示内容', 'zib_language'),
                'desc' => '支持HTML代码，请注意代码规范及标签闭合',
                'class' => 'compact',
                'id' => 'post_copyright',
                'default' => '文章版权归作者所有，未经允许请勿转载。',
                'attributes' => array(
                    'rows' => 2
                ),
                'sanitize' => false,
                'type' => 'textarea'
            ),

            array(
                'title' => __('文章页脚文案', 'zib_language'),
                'id' => 'post_button_toptext',
                'type' => "text",
                'default' => '喜欢就支持一下吧',
                'desc' => __('文章底部打赏、分享按钮上面的文字', 'zib_language')
            ),

            array(
                'title' => __('文章插入内容', 'zib_language'),
                'subtitle' => '在文章内容前-插入内容',
                'id' => 'post_front_content',
                'default' => '',
                'attributes' => array(
                    'rows' => 3
                ),
                'sanitize' => false,
                'type' => 'textarea'
            ),

            array(
                'title' => ' ',
                'subtitle' => '在文章内容后-插入内容',
                'id' => 'post_after_content',
                'class' => 'compact',
                'default' => '',
                'desc' => '在每篇文章顶部和尾部插入内容，可以插入广告或者文章说明等内容</br>支持HTML代码，请注意代码规范及标签闭合',
                'attributes' => array(
                    'rows' => 3
                ),
                'sanitize' => false,
                'type' => 'textarea'
            ),

            array(
                'title' => __('相关文章板块', 'zib_language'),
                'id' => 'post_related_s',
                'type' => 'switcher',
                'default' => true,
            ),

            array(
                'dependency' => array('post_related_s', '!=', ''),
                'title' => ' ',
                'class' => 'compact',
                'subtitle' => __('显示样式', 'zib_language'),
                'dependency' => array('post_related_s', '!=', ''),
                'id' => 'post_related_type',
                'default' => "img",
                'type' => "image_select",
                'options' => array(
                    'img' => $f_imgpath . 'related_img.png',
                    'list' => $f_imgpath . 'related_list.png',
                    'text' => $f_imgpath . 'related_text.png',
                )
            ),

            array(
                'dependency' => array('post_related_s', '!=', ''),
                'title' => ' ',
                'subtitle' => __('板块标题', 'zib_language'),
                'id' => 'related_title',
                'class' => 'compact',
                'default' => '相关推荐',
                'type' => 'text'
            ),

            array(
                'dependency' => array('post_related_s', '!=', ''),
                'class' => 'compact',
                'title' => ' ',
                'subtitle' => __('显示数量', 'zib_language'),
                'id' => 'post_related_n',
                'default' => 6,
                'max' => 12,
                'min' => 4,
                'step' => 2,
                'unit' => '篇',
                'type' => 'spinner'
            ),

        ),
    ));
    //文章功能
    CSF::createSection($prefix, array(
        'parent'      => 'post',
        'title'       => '文章功能',
        'icon'        => 'fa fa-fw fa-fw fa-magic',
        'description' => '',
        'fields'      => array(
            array(
                'id' => 'article_nav',
                'title' => '文章目录树',
                'desc' => '默认开关，每篇文章可单独设置。开启后请自行添加文章目录树模块到侧边栏',
                'default' => true,
                'type' => 'switcher'
            ),

            array(
                'id' => 'imagelightbox',
                'desc' => '点击图片查看原图功能，共两种模式|<a target="_blank" href="https://www.zibll.com/683.html">查看官网教程</a>',
                'title' => '图片灯箱',
                'default' => true,
                'type' => 'switcher'
            ),

            array(
                'title' => ' ',
                'id' => 'post_like_s',
                'title' => '文章点赞',
                'default' => true,
                'type' => 'switcher'
            ),

            array(
                'title' => __('内容分享', 'zib_language'),
                'id' => 'share_s',
                'type' => 'switcher',
                'default' => true,
            ),

            array(
                'dependency' => array('share_s', '!=', ''),
                'title' => __('自定义社交分享代码', 'zib_language'),
                'class' => 'compact',
                'id' => 'share_code',
                'default' => '',
                'help' => '',
                'desc' => '留空则使用主题的社交分享功能</br>如需自定义分享功能，可以改成其他分享代码',
                'attributes' => array(
                    'rows' => 1
                ),
                'sanitize' => false,
                'type' => 'textarea'
            ),

            array(
                'title' => __('生成海报分享', 'zib_language'),
                'help' => '网站图片如果使用了OSS等云储存，请先设置跨域规则',
                'id' => 'share_img',
                'type' => 'switcher',
                'default' => true,
            ),
            array(
                'dependency' => array('share_img', '!=', '', '', 'visible'),
                'type'    => 'submessage',
                'style'   => 'warning',
                'content' => '<i class="fa fa-fw fa-info-circle fa-fw"></i> 此功能如果加载出错，请检查图片的跨域设置！<a target="_blank" href="https://www.zibll.com/886.html">查看详细教程</a>',
            ),
            array(
                'dependency' => array('share_img', '!=', ''),
                'id' => 'share_img_byimg',
                'title' => ' ',
                'subtitle' => __('海报分享默认图片'),
                'desc' => '当文章没有任何图片时显示此图片，建议尺寸800*500',
                'default' => $imagepath . 'slider-bg.jpg',
                'library' => 'image', 'type' => 'upload'
            ),

            array(
                'dependency' => array('share_img', '!=', ''),
                'id' => 'share_logo',
                'title' => ' ',
                'subtitle' => __('海报分享LOGO'),
                'desc' => '显示在海报底部的LOGO，建议尺寸300x100',
                'class' => 'compact',
                'default' => $imagepath . 'logo.png',
                'library' => 'image', 'type' => 'upload'
            ),
            array(
                'dependency' => array('share_img', '!=', ''),
                'title' => ' ',
                'subtitle' => __('海报分享底部文案', 'zib_language'),
                'class' => 'compact',
                'id' => 'share_desc',
                'default' => __('扫描二维码阅读全文', 'zib_language'),
                'type' => 'text'
            ),

        ),
    ));
    CSF::createSection($prefix, array(
        'parent'      => 'user',
        'title'       => '消息通知',
        'icon'        => 'fa fa-fw fa-bell-o',
        'description' => '',
        'fields'      => array(
            array(
                'title' => '站内通知',
                'label' => '通知消息功能',
                'id' => 'message_s',
                'type' => 'switcher',
                'default' => true,
            ),
            array(
                'dependency' => array('message_s', '!=', ''),
                'title' => ' ',
                'type'    => 'content',
                'class' => 'compact',
                'style'   => 'warning',
                'content' => '<div style="text-align:cent er;"><a target="_blank" href="https://www.zibll.com/1244.html">查看教程</a> | <a href="' . admin_url('users.php?page=user_messags') . '">管理系统消息</a> | <a href="' . admin_url('users.php?page=user_messags&tab=new') . '">推送系统消息</a> | <a href="' . admin_url('users.php') . '">管理用户消息</a></div>',
            ),
            array(
                'dependency' => array('message_s', '!=', ''),
                'title' => '显示通知图标',
                'id' => 'message_icon_show',
                'default' => array('nav_menu', 'm_nav_user'),
                'options' => array(
                    'nav_menu' => 'PC端顶部导航',
                    'pc_nav_user' => 'PC端导航用户卡片',
                    'm_nav_user' => '移动菜单用户卡片',
                ),
                'desc' => '选择需要显示消息图标的位置',
                'type' => 'checkbox',
            ),
            array(
                'dependency' => array('message_s', '!=', '', '', 'visible'),
                'title' => '关闭推送',
                'subtitle' => '关闭部分消息推送',
                'id' => 'message_close_msg_type',
                'default' => array(),
                'options' => array(
                    'posts' => '关闭文章类消息推送',
                    'comment' => '关闭评论类消息推送',
                    'favorite' => '关闭文章收藏消息推送',
                    'like' => '关闭点赞消息推送',
                    'followed' => '关闭用户关注消息推送',
                    'system' => '关闭系统类消息推送',
                    'pay' => '关闭订单消息推送',
                    'withdraw_reply' => '关闭提现消息推送',
                ),
                'help' => '注意，只有关闭期间不会推送！已经推送的消息仍会显示。',
                'type' => 'checkbox',
            ),
            array(
                'dependency' => array('message_s', '!=', '', '', 'visible'),
                'title' => '允许用户设置',
                'label' => '用户前台消息推送设置',
                'id' => 'message_user_set',
                'type' => 'switcher',
                'default' => true,
            ),
            array(
                'dependency' => array('message_s', '!=', '', '', 'visible'),
                'title' => '私信功能',
                'id' => 'private_s',
                'type' => 'switcher',
                'default' => true,
            ),
            array(
                'dependency' => array('private_s|message_s', '!=|!=', '|'),
                'id'            => 'private_option',
                'type'          => 'fieldset',
                'title'         => '私信功能设置',
                'default'   => array(
                    'upload_img'  => false,
                    'smilie_s'  => true,
                    'code_s'  => true,
                    'image_s'  => true,
                    'submit_text'  => '<i class="fa fa-send-o"></i>发送',
                    'placeholder'  => '',
                ),
                'fields'    => array(
                    array(
                        'id' => 'smilie_s',
                        'type' => 'switcher',
                        'default' => true,
                        'title' => __('允许插入表情', 'zib_language')
                    ),
                    array(
                        'id' => 'code_s',
                        'class' => 'compact',
                        'type' => 'switcher',
                        'default' => true,
                        'title' => __('允许插入代码', 'zib_language')
                    ),
                    array(
                        'id' => 'image_s',
                        'class' => 'compact',
                        'type' => 'switcher',
                        'default' => true,
                        'title' => __('允许插入图片', 'zib_language')
                    ),
                    array(
                        'dependency' => array('image_s', '!=', ''),
                        'id' => 'upload_img',
                        'class' => 'compact',
                        'type' => 'switcher',
                        'default' => false,
                        'title' => __('允许上传图片', 'zib_language')
                    ),
                    array(
                        'id' => 'submit_text',
                        'title' => __('自定义文案', 'zib_language'),
                        'subtitle' => __('自定义提交按钮文案', 'zib_language'),
                        'default' => '<i class="fa fa-send-o"></i>发送',
                        'attributes' => array(
                            'rows' => 1
                        ),
                        'sanitize' => false,
                        'type' => 'textarea'
                    ),

                    array(
                        'id' => 'placeholder',
                        'class' => 'compact',
                        'title' => ' ',
                        'subtitle' => __('自定义占位符文案', 'zib_language'),
                        'default' => '',
                        'type' => 'text'
                    ),




                ),
            ),

        )
    ));

    CSF::createSection($prefix, array(
        'parent'      => 'user',
        'title'       => '评论设置',
        'icon'        => 'fa fa-fw fa-comments',
        'description' => '',
        'fields'      => array(
            array(
                'title' => '全站关闭评论',
                'id' => 'close_comments',
                'type' => 'switcher',
                'default' => false,
            ),

            array(
                'dependency' => array('close_comments', '==', '', '', 'visible'),
                'id' => 'comment_smilie',
                'help' => '为了防止恶意评论，建议在后台-设置-讨论：开启"用户必须登录后才能发表评论"',
                'type' => 'switcher',
                'default' => true,
                'title' => __('允许插入表情', 'zib_language')
            ),

            array(
                'dependency' => array('close_comments', '==', '', '', 'visible'),
                'id' => 'comment_code',
                'class' => 'compact',
                'type' => 'switcher',
                'default' => true,
                'title' => __('允许插入代码', 'zib_language')
            ),

            array(
                'dependency' => array('close_comments', '==', '', '', 'visible'),
                'id' => 'comment_img',
                'class' => 'compact',
                'type' => 'switcher',
                'default' => true,
                'title' => __('允许插入图片', 'zib_language')
            ),

            array(
                'dependency' => array('close_comments|comment_img', '==|!=', '|', '', 'visible'),
                'id' => 'comment_upload_img',
                'class' => 'compact',
                'type' => 'switcher',
                'default' => false,
                'title' => __('允许上传图片', 'zib_language')
            ),
            array(
                'dependency' => array('close_comments', '==', '', '', 'visible'),
                'id' => 'comment_author_tag',
                'class' => 'compact',
                'type' => 'switcher',
                'default' => true,
                'title' => __('显示“作者”标签', 'zib_language')
            ),
            array(
                'dependency' => array('close_comments', '==', '', '', 'visible'),
                'id' => 'user_edit_comment',
                'class' => 'compact',
                'type' => 'switcher',
                'default' => true,
                'title' => '允许用户编辑评论'
            ),
            array(
                'dependency' => array('close_comments', '==', '', '', 'visible'),
                'class' => 'compact',
                'id' => 'comment_like_s',
                'title' => '评论点赞功能',
                'default' => true,
                'type' => 'switcher'
            ),

            array(
                'dependency' => array('close_comments', '==', '', '', 'visible'),
                'title' => __('自定义文案', 'zib_language'),
                'subtitle' => __('自定义评论标题', 'zib_language'),
                'id' => 'comment_title',
                'class' => '',
                'default' => __('评论', 'zib_language'),
                'attributes' => array(
                    'rows' => 1
                ),
                'sanitize' => false,
                'type' => 'textarea'
            ),

            array(
                'dependency' => array('close_comments', '==', '', '', 'visible'),
                'id' => 'comment_submit_text',
                'class' => 'compact',
                'title' => ' ',
                'subtitle' => __('自定义评论提交按钮文案', 'zib_language'),
                'default' => __('提交评论', 'zib_language'),
                'attributes' => array(
                    'rows' => 1
                ),
                'sanitize' => false,
                'type' => 'textarea'
            ),

            array(
                'dependency' => array('close_comments', '==', '', '', 'visible'),
                'id' => 'comment_text',
                'class' => 'compact',
                'title' => ' ',
                'subtitle' => __('自定义评论框占位符文案', 'zib_language'),
                'default' => __('欢迎您留下宝贵的见解！', 'zib_language'),
                'type' => 'text'
            ),

        ),
    ));
    //个人中心
    CSF::createSection($prefix, array(
        'parent'      => 'user',
        'title'       => '注册登录',
        'icon'        => 'fa fa-fw fa-user-plus',
        'description' => '',
        'fields'      => array(
            array(
                'content' => '<p>无论您选择哪种登录模式，均可使用以下地址(请注意，以下地址仅在未登录时有效)</p>
                <li>登录地址：<code>' . add_query_arg('tab', 'signin', zib_get_template_page_url('pages/user-sign.php')) . '</code></li>
                <li>注册地址：<code>' . add_query_arg('tab', 'signup', zib_get_template_page_url('pages/user-sign.php')) . '</code></li>
                <li>找回密码地址：<code>' . add_query_arg('tab', 'resetpassword', zib_get_template_page_url('pages/user-sign.php')) . '</code></li>
                <div style="color:#ff2153;"><i class="fa fa-fw fa-info-circle fa-fw"></i>下方涉及到邮箱或者短信验证的功能，请确保邮件和短信能正常发送！</div>
                <a href="' . zib_get_admin_csf_url('全局功能/email邮件') . '">邮件设置</a> | <a href="' . zib_get_admin_csf_url('用户互动/短信接口') . '">短信设置</a>',
                'style' => 'warning',
                'type' => 'submessage',
            ),
            array(
                'title' => '关闭注册登录功能',
                'label' => '前台禁用注册/登录功能',
                'desc' => '部分网站无需用户交互，可在此禁用用户登录/注册功能，不影响后台管理员登录',
                'id' => 'close_sign',
                'default' => false,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('close_sign', '==', ''),
                'title' => '登录注册模式',
                'id' => 'user_sign_type',
                'default' => 'modal',
                'inline' => true,
                'type' => "radio",
                'options' => array(
                    'modal' => '弹窗登录/注册',
                    'page' => '独立页面登录/注册',
                )
            ),
            array(
                'title' => '代替WP自带登录页面',
                'label' => '使用主题的登录/注册页面代替WP自带的登录注册页面',
                'id' => 'replace_wp_login',
                'default' => true,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('close_sign', '==', ''),
                'title' => '弹窗配置',
                'subtitle' =>  '登录/注册弹窗配置',
                'id'        => 'user_modal_option',
                'type'          => 'accordion',
                'accordions'    => array(
                    array(
                        'title'     => '登录/注册弹窗的个性化设置',
                        'fields'    => array(
                            array(
                                'title' => '左侧图片',
                                'id'    => 'background',
                                'type'  => 'gallery',
                                'add_title'   => '新增图片',
                                'edit_title'  => '编辑图片',
                                'clear_title' => '清空图片',
                                'default' => false,
                                'desc' => '登录框左侧图片，如选择多张图片则随机显示<br>由于登录框的高度会根据开启的功能不同而变化，所以此处的尺寸建议根据实际情况调整',
                            ),
                            array(
                                'title' => '显示LOGO',
                                'class' => 'compact',
                                'id' => 'show_logo',
                                'default' => false,
                                'type' => 'switcher'
                            )
                        )
                    )
                )
            ),
            array(
                'title' => '页面配置',
                'subtitle' =>  '登录/注册页面配置',
                'id'        => 'user_sign_page_option',
                'type'          => 'accordion',
                'accordions'    => array(
                    array(
                        'title'     => '登录/注册/找回密码的页面配置',
                        'fields'    => array(
                            array(
                                'id'    => 'background',
                                'type'  => 'gallery',
                                'add_title'   => '新增背景图',
                                'edit_title'  => '编辑背景图',
                                'clear_title' => '清空背景图',
                                'title' => '页面背景图',
                                'default' => false,
                                'desc' => '页面的背景图，如果选择多张图则随机获取一张。',
                            ),
                            array(
                                'title' => '显示顶部导航',
                                'class' => 'compact',
                                'id' => 'show_header',
                                'default' => false,
                                'type' => 'switcher'
                            ),
                            array(
                                'title' => '卡片位置',
                                'id' => 'card_position',
                                'default' => 'right',
                                'class' => 'compact',
                                'type' => "radio",
                                'inline' => true,
                                'options' => array(
                                    'left' => '靠左',
                                    'center' => '居中',
                                    'right' => '靠右',
                                )
                            ),
                            array(
                                'title' => '显示卡片LOGO',
                                'id' => 'show_logo',
                                'class' => 'compact',
                                'default' => false,
                                'type' => 'switcher'
                            ),
                            array(
                                'title' => __('页脚内容', 'zib_language'),
                                'desc' => '在页面底部添加内容，支持HTML代码(不建议内容过多)',
                                'id' => 'footer',
                                'class' => 'compact',
                                'default' => 'Copyright &copy;&nbsp;' . date('Y') . '&nbsp;·&nbsp;<a href="' . home_url() . '">' . get_bloginfo('title') . '</a>&nbsp;·&nbsp;由<a target="_blank" href="https://zibll.com">Zibll主题</a>强力驱动.',
                                'attributes' => array(
                                    'rows' => 2
                                ),
                                'sanitize' => false,
                                'type' => 'textarea'
                            ),
                        ),
                    )
                )
            ),
            array(
                'title' => '登录框LOGO',
                'subtitle' =>  '登录/注册卡片LOGO',
                'id'        => 'user_card_option',
                'type'          => 'accordion',
                'accordions'    => array(
                    array(
                        'title'     => '登录/注册卡片LOGO设置',
                        'fields'    => array(
                            array(
                                'title' => __('登录框logo', 'zib_language'),
                                'subtitle' => __('日间主题', 'zib_language'),
                                'id' => 'user_logo',
                                'subtitle' => __('日间主题', 'zib_language'),
                                'desc' => __('登录框顶部图像，建议尺寸450px*280px'),
                                'help' => '如果单张图能同时适应日间和夜间主题，则仅设置日间主题的图片即可（推荐这样设置）',
                                'default' => $imagepath . 'logo.png',
                                'preview' => true,
                                'library' => 'image', 'type' => 'upload'
                            ),
                            array(
                                'title' => __('登录框logo', 'zib_language'),
                                'subtitle' => __('夜间主题', 'zib_language'),
                                'id' => 'user_logo_dark',
                                'class' => 'compact',
                                'default' => $imagepath . 'logo_dark.png',
                                'preview' => true,
                                'library' => 'image', 'type' => 'upload'
                            )
                        )
                    )
                )
            ),
            array(
                'dependency' => array('close_sign', '==', ''),
                'title' => '绑定设置',
                'subtitle' =>  '用户绑定手机/邮箱设置',
                'id'        => 'user_bind_option',
                'type'          => 'accordion',
                'accordions'    => array(
                    array(
                        'title'     => '绑定手机/邮箱相关配置',
                        'fields'    => array(
                            array(
                                'title' => '手机绑定',
                                'id' => 'bind_phone',
                                'label' => '用户中心显示绑定、修改手机号功能',
                                'default' => false,
                                'type' => 'switcher'
                            ),
                            array(
                                'title' => '绑定提醒',
                                'id' => 'bind_reminder',
                                'default' => "close",
                                'type' => "radio",
                                'desc' => '用户登录后如未绑定对应信息，会弹窗提示用户绑定（每天只会提醒一次）',
                                'options' => array(
                                    'email' => '提醒绑定邮箱',
                                    'phone' => '提醒绑定手机',
                                    'email_phone' => '提醒绑定邮箱和手机',
                                    'close' => '关闭'
                                )
                            ),
                            array(
                                'dependency' => array('bind_reminder', '!=', 'close'),
                                'title' => ' ',
                                'subtitle' => __('绑定提醒文案', 'zib_language'),
                                'desc' => __('提醒绑定弹窗的文案介绍，支持HTML代码', 'zib_language'),
                                'class' => 'compact',
                                'id' => 'bind_reminder_text',
                                'default' => "为了您的账户安全，请务必完成账户绑定",
                                'sanitize' => false,
                                'type' => 'textarea'
                            ),
                            array(
                                'title' => '强制绑定',
                                'id' => 'mandatory_bind',
                                'default' => "close",
                                'type' => "radio",
                                'desc' => '登录必须先绑定信息，才能完成登录',
                                'options' => array(
                                    'email' => '必须绑定邮箱',
                                    'phone' => '必须绑定手机',
                                    'email_phone' => '必须绑定邮箱和手机',
                                    'close' => '关闭'
                                )
                            ),
                            array(
                                'dependency' => array('mandatory_bind', '!=', 'close'),
                                'title' => ' ',
                                'subtitle' => __('强制绑定文案', 'zib_language'),
                                'desc' => __('强制绑定的文案介绍，支持HTML代码', 'zib_language'),
                                'class' => 'compact',
                                'id' => 'mandatory_bind_text',
                                'default' => "为了您的账户安全，请先完成账户绑定",
                                'sanitize' => false,
                                'type' => 'textarea'
                            ),
                            array(
                                'title' => '绑定邮箱需验证',
                                'id' => 'email_set_captch',
                                'label' => '用户修改、绑定邮箱需先验证',
                                'default' => true,
                                'type' => 'switcher'
                            ),
                        )
                    )
                )
            ),
            array(
                'title' => '手机号登录',
                'label' => '允许使用手机号作为用户名登录',
                'id' => 'user_signin_phone_s',
                'default' => false,
                'type' => 'switcher'
            ),
            array(
                'title' => '免密登录',
                'label' => '使用验证码免密登录',
                'id' => 'user_signin_nopas_s',
                'default' => false,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('user_signin_nopas_s', '!=', ''),
                'title' => ' ',
                'subtitle' => '免密登录方式',
                'id' => 'user_signin_nopas_type',
                'class' => 'compact',
                'default' => "email",
                'type' => "radio",
                'options' => array(
                    'email' => '邮箱验证',
                    'phone' => '手机验证',
                    'email_phone' => '邮箱或手机验证'
                )
            ),
            array(
                'dependency' => array('user_signin_nopas_s', '!=', ''),
                'title' => ' ',
                'subtitle' => '优先显示',
                'id' => 'user_signin_nopas_active',
                'class' => 'compact',
                'default' => "nopas",
                'inline' => true,
                'type' => "radio",
                'options' => array(
                    'nopas' => '免密登录',
                    'pas' => '帐号密码登录',
                )
            ),
            array(
                'title' => '注册验证',
                'label' => __('注册需要验证邮箱或手机号', 'zib_language'),
                'id' => 'user_signup_captch',
                'default' => false,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('user_signup_captch', '!=', ''),
                'title' => ' ',
                'subtitle' => '注册验证方式',
                'id' => 'captch_type',
                'class' => 'compact',
                'default' => "email",
                'type' => "radio",
                'options' => array(
                    'email' => '邮箱验证',
                    'phone' => '手机验证',
                    'email_phone' => '邮箱或手机验证'
                )
            ),
            array(
                'dependency' => array('user_signup_captch', '!=', ''),
                'title' => ' ',
                'subtitle' => '注册无需重复密码',
                'class' => 'compact',
                'id' => 'user_signup_no_repas',
                'default' => true,
                'type' => 'switcher'
            ),
            array(
                'title' => '找回密码验证',
                'subtitle' => '找回密码验证方式',
                'id' => 'user_repas_captch_type',
                'default' => "email",
                'type' => "radio",
                'options' => array(
                    'email' => '邮箱验证',
                    'phone' => '手机验证',
                    'email_phone' => '邮箱或手机验证'
                )
            ),
            array(
                'title' => '人机验证',
                'subtitle' => __('登陆、注册人机验证', 'zib_language'),
                'id' => 'user_verification_type',
                'default' => 'slider',
                'type' => "radio",
                'options' => array(
                    'image' => __('图片验证码'),
                    'slider' => __('滑动拼图验证'),
                    'null' => __('关闭')
                )
            ),
            array(
                'title' => __('显示用户协议', 'zib_language'),
                'id' => 'user_agreement_s',
                'default' => false,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('user_agreement_s', '!=', ''),
                'title' => ' ',
                'subtitle' => '用户协议页面',
                'class' => 'compact',
                'id' => 'user_agreement_page',
                'default' => '',
                'desc' => '请新建页面写入用户协议后，在此选择用户协议页面',
                'options' => 'page',
                'type' => 'select'
            ),
            array(
                'title' => __('显示隐私协议', 'zib_language'),
                'id' => 'user_privacy_s',
                'default' => false,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('user_privacy_s', '!=', ''),
                'title' => ' ',
                'subtitle' => '隐私协议页面',
                'class' => 'compact',
                'id' => 'user_privacy_page',
                'default' => '',
                'desc' => '请新建页面写入隐私协议后，在此选择隐私协议页面',
                'options' => 'page',
                'type' => 'select'
            ),
            array(
                'title' => __('用户昵称限制', 'zib_language'),
                'subtitle' => __('禁止的昵称关键词', 'zib_language'),
                'desc' => __('前台注册或修改昵称时，不能使用包含这些关键字的昵称(请用逗号或换行分割)', 'zib_language'),
                'id' => 'user_nickname_out',
                'default' => "赌博,博彩,彩票,性爱,色情,做爱,爱爱,淫秽,傻b,妈的,妈b,admin,test",
                'sanitize' => false,
                'type' => 'textarea'
            ),
        ),
    ));

    //社交登录
    CSF::createSection($prefix, array(
        'parent'      => 'user',
        'title'       => '社交登录',
        'icon'        => 'fa fa-fw fa-sign-in',
        'description' => '',
        'fields'      => array(
            array(
                'title' => __('接入插件', 'zib_language'),
                'subtitle' => '使用Wechat Social社交登录插件',
                'id' => 'social',
                'type' => 'switcher',
                'default' => false,
                'desc' => 'Wechat Social社交登录（需安装迅虎网络的<a target="_blank" href="https://www.wpweixin.net/product/1067.html">Wechat Social</a>社会化登录插件）</br>此功能以及下方的社会化登录二选一'
            ),
            array(
                'title' => __('按钮样式', 'zib_language'),
                'subtitle' => __('显示为大按钮', 'zib_language'),
                'id' => 'oauth_button_lg',
                'default' => false,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('social', '==', '', '', 'visible'),
                'title' => __('QQ登录', 'zib_language'),
                'id' => 'oauth_qq_s',
                'default' => false,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('oauth_qq_s|social', '!=|==', '|'),
                'title' => ' ',
                'subtitle' =>  'QQ登录配置',
                'id'        => 'oauth_qq_option',
                'type'      => 'fieldset',
                'class' => 'compact',
                'fields'    => array(
                    array(
                        'content' => '<h4><b>回调地址：</b>' . esc_url(home_url('/oauth/qq/callback')) . '</h4>QQ登录申请地址：<a target="_blank" href="https://connect.qq.com/">https://connect.qq.com</a> | <a target="_blank" href="https://www.zibll.com/979.html">查看官方教程</a>',
                        'style' => 'info',
                        'type' => 'submessage',
                    ),
                    array(
                        'title' => 'AppID',
                        'id' => 'appid',
                        'type' => 'text'
                    ),
                    array(
                        'title' => 'AppKey',
                        'class' => 'compact',
                        'id' => 'appkey',
                        'type' => 'text'
                    ),
                ),
            ),
            array(
                'dependency' => array('social', '==', '', '', 'visible'),
                'title' => __('微信登录', 'zib_language'),
                'id' => 'oauth_weixin_s',
                'default' => false,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('oauth_weixin_s|social', '!=|==', '|'),
                'title' => ' ',
                'subtitle' =>  '微信登录配置',
                'id'        => 'oauth_weixin_option',
                'type'      => 'fieldset',
                'class' => 'compact',
                'fields'    => array(
                    array(
                        'content' => '<h4><b>回调地址：</b>' . esc_url(home_url('/oauth/weixin/callback')) . '</h4>微信登录申请地址：<a target="_blank" href="https://open.weixin.qq.com/">https://open.weixin.qq.com</a>',
                        'style' => 'info',
                        'type' => 'submessage',
                    ),
                    array(
                        'title' => 'AppID',
                        'id' => 'appid',
                        'type' => 'text'
                    ),
                    array(
                        'title' => 'AppKey',
                        'class' => 'compact',
                        'id' => 'appkey',
                        'type' => 'text'
                    ),
                ),
            ),
            array(
                'dependency' => array('social', '==', '', '', 'visible'),
                'title' => __('微博登录', 'zib_language'),
                'id' => 'oauth_weibo_s',
                'default' => false,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('oauth_weibo_s|social', '!=|==', '|'),
                'title' => ' ',
                'subtitle' =>  '微博登录配置',
                'id'        => 'oauth_weibo_option',
                'type'      => 'fieldset',
                'class' => 'compact',
                'fields'    => array(
                    array(
                        'content' => '<h4><b>回调地址：</b>' . esc_url(home_url('/oauth/weibo/callback')) . '</h4>微博登录申请地址：<a target="_blank" href="https://open.weibo.com/authentication/">https://open.weibo.com/authentication</a>',
                        'style' => 'info',
                        'type' => 'submessage',
                    ),
                    array(
                        'title' => 'AppID',
                        'id' => 'appid',
                        'type' => 'text'
                    ),
                    array(
                        'title' => 'AppKey',
                        'class' => 'compact',
                        'id' => 'appkey',
                        'type' => 'text'
                    ),
                ),
            ),
            array(
                'dependency' => array('social', '==', '', '', 'visible'),
                'title' => __('码云(gitee)登录', 'zib_language'),
                'id' => 'oauth_gitee_s',
                'default' => false,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('oauth_gitee_s|social', '!=|==', '|'),
                'title' => ' ',
                'subtitle' =>  '码云(gitee)登录配置',
                'id'        => 'oauth_gitee_option',
                'type'      => 'fieldset',
                'class' => 'compact',
                'fields'    => array(
                    array(
                        'content' => '<h4><b>回调地址：</b>' . esc_url(home_url('/oauth/gitee/callback')) . '</h4>码云(gitee)登录申请地址：<a target="_blank" href="https://gitee.com/oauth/applications/">https://gitee.com/oauth/applications</a>',
                        'style' => 'info',
                        'type' => 'submessage',
                    ),
                    array(
                        'title' => 'AppID',
                        'id' => 'appid',
                        'type' => 'text'
                    ),
                    array(
                        'title' => 'AppKey',
                        'class' => 'compact',
                        'id' => 'appkey',
                        'type' => 'text'
                    ),
                ),
            ),
            array(
                'dependency' => array('social', '==', '', '', 'visible'),
                'title' => __('GitHub登录', 'zib_language'),
                'id' => 'oauth_github_s',
                'default' => false,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('oauth_github_s|social', '!=|==', '|'),
                'title' => ' ',
                'subtitle' =>  'GitHub登录配置',
                'id'        => 'oauth_github_option',
                'type'      => 'fieldset',
                'class' => 'compact',
                'fields'    => array(
                    array(
                        'content' => '<h4><b>回调地址：</b>' . esc_url(home_url('/oauth/github/callback')) . '</h4>GitHub登录申请地址：<a target="_blank" href="https://github.com/settings/developers">https://github.com/settings/developers</a> | <a target="_blank" href="https://www.zibll.com/1001.html">查看官方教程</a>',
                        'style' => 'info',
                        'type' => 'submessage',
                    ),
                    array(
                        'title' => 'AppID',
                        'id' => 'appid',
                        'type' => 'text'
                    ),
                    array(
                        'title' => 'AppKey',
                        'class' => 'compact',
                        'id' => 'appkey',
                        'type' => 'text'
                    ),
                ),
            ),
            array(
                'dependency' => array('social', '==', '', '', 'visible'),
                'title' => __('百度登录', 'zib_language'),
                'id' => 'oauth_baidu_s',
                'default' => false,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('oauth_baidu_s|social', '!=|==', '|'),
                'title' => ' ',
                'subtitle' =>  '百度登录配置',
                'id'        => 'oauth_baidu_option',
                'type'      => 'fieldset',
                'class' => 'compact',
                'fields'    => array(
                    array(
                        'content' => '<h4><b>回调地址：</b>' . esc_url(home_url('/oauth/baidu/callback')) . '</h4>百度登录申请地址：<a target="_blank" href="http://developer.baidu.com/">http://developer.baidu.com</a>',
                        'style' => 'info',
                        'type' => 'submessage',
                    ),
                    array(
                        'title' => 'API Key',
                        'id' => 'appid',
                        'type' => 'text'
                    ),
                    array(
                        'title' => 'Secret Key',
                        'class' => 'compact',
                        'id' => 'appkey',
                        'type' => 'text'
                    ),
                ),
            ),
            array(
                'dependency' => array('social', '==', '', '', 'visible'),
                'title' => __('支付宝登录', 'zib_language'),
                'id' => 'oauth_alipay_s',
                'default' => false,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('oauth_alipay_s|social', '!=|==', '|'),
                'title' => ' ',
                'subtitle' =>  '支付宝登录配置',
                'id'        => 'oauth_alipay_option',
                'type'      => 'fieldset',
                'class' => 'compact',
                'fields'    => array(
                    array(
                        'content' => '<h4><b>回调地址：</b>' . esc_url(home_url('/oauth/alipay/callback')) . '</h4>支付宝登录申请地址：<a target="_blank" href="https://open.alipay.com/platform/developerIndex.html">https://open.alipay.com/platform/developerIndex.html</a></br>由于移动端支付宝登陆只能在支付宝内打开才有效，所以支付宝登陆不会在移动端显示',
                        'style' => 'info',
                        'type' => 'submessage',
                    ),
                    array(
                        'title' => '支付宝应用ID',
                        'subtitle' => 'AppID',
                        'id' => 'appid',
                        'type' => 'text'
                    ),
                    array(
                        'title' => '支付宝应用私钥',
                        'class' => 'compact',
                        'subtitle' => 'appPrivateKey',
                        'id' => 'appkrivatekey',
                        'attributes' => array(
                            'rows' => 4
                        ),
                        'sanitize' => false,
                        'type' => 'textarea'
                    ),
                ),
            ),
        ),
    ));
    //用户功能
    CSF::createSection($prefix, array(
        'parent'      => 'user',
        'title'       => '用户功能',
        'icon'        => 'fa fa-fw fa-user-o',
        'description' => '',
        'fields'      => array(
            array(
                'title' => __('用户默认头像', 'zib_language'),
                'id' => 'avatar_default_img',
                'desc' => __('用户默认头像，建议尺寸100px*100px'),
                'default' => $imagepath . 'avatar-default.png',
                'library' => 'image', 'type' => 'upload'
            ),

            array(
                'title' => __('用户默认封面', 'zib_language'),
                'id' => 'user_cover_img',
                'desc' => __('默认封面图，建议尺寸1000x400,如果分类页未开启侧边栏，请选择更大的尺寸'),
                'help' => '用户可在个人中心设置自己的封面图，如用户未单独设置则显示此图像',
                'default' => $imagepath . 'user_t.jpg',
                'library' => 'image', 'type' => 'upload'
            ),

            array(
                'title' => __('用户默认签名', 'zib_language'),
                'help' => __('用户未设置签名时候，显示的签名', 'zib_language'),
                'default' => '这家伙很懒，什么都没有写...',
                'id' => 'user_desc_std',
                'type' => 'text'
            ),

            array(
                'title' => __('用一言代替用户签名', 'zib_language'),
                'class' => 'compact',
                'id' => 'yiyan_avatar_desc',
                'type' => 'switcher',
                'default' => false,
            ),

            array(
                'id' => 'post_rewards_s',
                'title' => '用户打赏功能',
                'default' => true,
                'type' => 'switcher'
            ),

            array(
                'dependency' => array('post_rewards_s', '!=', ''),
                'title' => ' ',
                'subtitle' => '自定义打赏按钮文字',
                'class' => 'compact',
                'id' => 'post_rewards_text',
                'default' => '赞赏',
                'type' => 'text'
            ),

            array(
                'title' => __('前端发布文章', 'zib_language'),
                'id' => 'post_article_s',
                'default' => true,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('post_article_s', '!=', ''),
                'title' => ' ',
                'subtitle' => __('发布文章允许选择的分类'),
                'class' => 'compact',
                'id' => 'post_article_cat',
                'placeholder' => '允许允许选择的分类',
                'default' => array(),
                'options' => 'categories',
                'type' => 'select',
                'chosen' => true,
                'multiple' => true,
                'sortable' => true,
            ),
            array(
                'dependency' => array('post_article_s', '!=', ''),
                'title' => ' ',
                'subtitle' => __('发布文章允许上传图片', 'zib_language'),
                'class' => 'compact',
                'id' => 'post_article_img_s',
                'default' => false,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('post_article_s', '!=', ''),
                'title' => ' ',
                'subtitle' => __('发布文章权限设置', 'zib_language'),
                'id' => 'post_article_limit',
                'class' => 'compact',
                'default' => "logged_in",
                'type' => "radio",
                'options' => array(
                    'logged_in' => __('仅登录后可发布文章'),
                    'all' => __('无需登录直接可发布文章'),
                )
            ),

            array(
                'dependency' => array('post_article_s', '!=', ''),
                'id' => 'post_article_review_s',
                'class' => 'compact',
                'default' => false,
                'title' => ' ',
                'subtitle' => __('发布文章无需审核直接发布', 'zib_language'),
                'type' => 'switcher'
            ),

            array(
                'dependency' => array('post_article_s', '!=', ''),
                'id' => 'post_article_user',
                'class' => 'compact',
                'options' => 'user',
                'default' => 1,
                'desc' => '当选择无需登录就能投稿时，投稿文章的用户',
                'title' => ' ',
                'subtitle' => __('投稿发布用户'),
                'type' => 'select'
            ),
        ),
    ));

    CSF::createSection($prefix, array(
        'parent'      => 'user',
        'title'       => '短信接口',
        'icon'        => 'fa fa-fw fa-comments-o',
        'description' => '',
        'fields'      => array(
            array(
                'content' => '<p><b>如需网站使用手机账户等相关功能，请在下方设置短信接口</b></p>
                <li>阿里云短信和腾讯云短信都是国内品质较高的短信平台，可靠信高，申请简单，但需要网站备案！其它接口无需备案</li>
                <li>短信能正常发送后，请记得开启手机绑定、手机号登录、手机验证等功能</li>
                <li>如需定制其它短信接口，欢迎<a href="http://wpa.qq.com/msgrd?v=3&amp;uin=1234567788&amp;site=qq&amp;menu=yes" title="QQ联系">与我QQ联系</a></li>
                <li><a target="_blank" href="https://www.zibll.com/?s=短信" class="loginbtn">官方教程</a> | <a href="' . zib_get_admin_csf_url('用户互动/注册登录') . '">登录/注册功能设置</a></li>',
                'style' => 'warning',
                'type' => 'submessage',
            ),
            array(
                'id' => 'sms_sdk',
                'default' => 'null',
                'title' => '设置短信接口',
                'type' => "select",
                'options' => array(
                    'ali' => __('阿里云短信', 'zib_language'),
                    'tencent' => __('腾讯云短信', 'zib_language'),
                    'smsbao' => __('短信宝', 'zib_language'),
                    'fcykj' => __('风吹雨短信', 'zib_language'),
                )
            ),
            array(
                'id'            => 'sms_ali_option',
                'type'          => 'accordion',
                'title'         => '阿里云',
                'accordions'    => array(
                    array(
                        'title'     => '阿里云短信配置',
                        'fields'    => array(
                            array(
                                'title' => 'AccessKey Id',
                                'id' => 'keyid',
                                'default' => '',
                                'type' => 'text'
                            ),
                            array(
                                'title' => 'AccessKey Secret',
                                'class' => 'compact',
                                'id' => 'keysecret',
                                'default' => '',
                                'type' => 'text'
                            ),
                            array(
                                'title' => '签名',
                                'class' => 'compact',
                                'id' => 'sign_name',
                                'desc' => '阿里云短信已审核的的短信签名，示例：子比主题',
                                'default' => '',
                                'type' => 'text'
                            ),
                            array(
                                'class' => 'compact',
                                'title' => '模板CODE',
                                'id' => 'template_code',
                                'desc' => '阿里云短信已审核的的短信模板代码，示例：SMS_207952000<br>
                                <a target="_blank" href="https://www.zibll.com/1483.html">阿里云短信接入教程</a>
                                <a target="_blank" href="https://www.aliyun.com/product/sms?userCode=qyth9w2q">申请地址</a>',
                                'default' => '',
                                'type' => 'text'
                            ),
                        )
                    ),
                )
            ),
            array(
                'id'            => 'sms_tencent_option',
                'type'          => 'accordion',
                'title'         => '腾讯云',
                'accordions'    => array(
                    array(
                        'title'     => '腾讯云短信配置',
                        'fields'    => array(
                            array(
                                'title' => 'SDK AppID',
                                'id' => 'app_id',
                                'default' => '',
                                'type' => 'text'
                            ),
                            array(
                                'title' => 'App Key',
                                'class' => 'compact',
                                'id' => 'app_key',
                                'desc' => '腾讯云短信应用的SDK AppID和AppKey',
                                'default' => '',
                                'type' => 'text'
                            ),
                            /**
                            array(
                                'title' => 'Access Id',
                                'class' => 'compact',
                                'id' => 'secret_id',
                                'default' => '',
                                'type' => 'text'
                            ),
                            array(
                                'title' => 'Access Key',
                                'class' => 'compact',
                                'id' => 'secret_key',
                                'default' => '',
                                'type' => 'text'
                            ), */
                            array(
                                'title' => '签名',
                                'class' => 'compact',
                                'id' => 'sign_name',
                                'desc' => '已审核的的短信签名，示例：子比主题',
                                'default' => '',
                                'type' => 'text'
                            ),
                            array(
                                'class' => 'compact',
                                'title' => '模板ID',
                                'id' => 'template_id',
                                'desc' => '已审核的的短信模板ID，示例：825011<br>
                                <a target="_blank" href="https://www.zibll.com/?s=腾讯云短信">腾讯云短信接入教程</a>
                                <a target="_blank" href="https://cloud.tencent.com/product/sms">申请地址</a>',
                                'default' => '',
                                'type' => 'text'
                            ),
                        )
                    ),
                )
            ),
            array(
                'id'            => 'sms_smsbao_option',
                'type'          => 'accordion',
                'title'         => '短信宝',
                'accordions'    => array(
                    array(
                        'title'     => '短信宝配置',
                        'fields'    => array(
                            array(
                                'title' => '用户名',
                                'id' => 'userame',
                                'default' => '',
                                'type' => 'text'
                            ),
                            array(
                                'title' => '密码',
                                'class' => 'compact',
                                'id' => 'password',
                                'desc' => '短信宝平台注册的用户名和密码',
                                'default' => '',
                                'type' => 'text'
                            ),
                            array(
                                'class' => 'compact',
                                'title' => '模板内容',
                                'id' => 'template',
                                'desc' => '已通过审核的验证码模板内容，必须要有<code style="color: #ee3f17;padding:0px 3px">{code}</code>变量<br>示例：<code>【子比主题】您的验证码为{code}，在{time}分钟内有效。</code><br>
                                <a target="_blank" href="https://www.zibll.com/?s=短信">接入教程</a> | <a target="_blank" href="http://www.smsbao.com/">短信宝官网</a>',
                                'default' => '',
                                'type' => 'text'
                            ),
                        )
                    ),
                )
            ),
            array(
                'id'            => 'sms_fcykj_option',
                'type'          => 'accordion',
                'title'         => '风吹雨短信',
                'accordions'    => array(
                    array(
                        'title'     => '风吹雨短信配置',
                        'fields'    => array(
                            array(
                                'title' => 'Appid',
                                'id' => 'appid',
                                'default' => '',
                                'type' => 'text'
                            ),
                            array(
                                'title' => 'Auth Token',
                                'class' => 'compact',
                                'id' => 'auth_token',
                                'default' => '',
                                'type' => 'text'
                            ),
                            array(
                                'class' => 'compact',
                                'title' => '模板ID',
                                'id' => 'template_id',
                                'desc' => '已通过审核的验证码模板ID，示例：<code>101</code><br>
                                <a target="_blank" href="https://www.zibll.com/?s=短信">接入教程</a> | <a target="_blank" href="https://sms.fcykj.net/">风吹雨官网</a>',
                                'default' => '',
                                'type' => 'text'
                            ),
                        )
                    ),
                )
            ),
            array(
                'content' => '<p><b>短信发送测试：</b>
                <br/>输入接收短信的手机号码，在此发送验证码为888888的测试短信</p>
                <ajaxform class="ajax-form" ajax-url="' . admin_url('admin-ajax.php') . '">
                <p><input type="text" style="max-width:300px;" ajax-name="phone_number" placeholder="13800008888"></p>
                <div class="ajax-notice"></div>
                <p><a href="javascript:;" class="but jb-yellow ajax-submit"><i class="fa fa-paper-plane-o"></i> 发送测试短信</a></p>
                <input type="hidden" ajax-name="action" value="test_send_sms">
                </ajaxform>',
                'style' => 'warning',
                'type' => 'submessage',
            ),
        )
    ));
    CSF::createSection($prefix, array(
        'parent'      => 'pay',
        'title'       => '商品销售',
        'icon'        => 'fa fa-fw fa-shopping-bag',
        'description' => '',
        'fields'      => array(
            array(
                'title' => '免登陆购买',
                'id' => 'pay_no_logged_in',
                'default' => true,
                'help' => '开启后如果用户未登录则使用浏览器缓存验证是否购买',
                'type' => 'switcher'
            ),

            array(
                'dependency' => array('pay_no_logged_in', '!=', ''),
                'class' => 'compact',
                'title' => ' ',
                'subtitle' => __('Cookie时间', 'zib_language'),
                'id' => 'pay_cookie_day',
                'desc' => '免登陆购买的浏览器缓存有效时间',
                'default' => 15,
                'max' => 31,
                'min' => 1,
                'step' => 1,
                'unit' => '天',
                'type' => 'spinner'
            ),
            array(
                'dependency' => array('pay_no_logged_in', '!=', ''),
                'title' => ' ',
                'subtitle' => '未登录提醒',
                'class' => 'compact',
                'id' => 'pay_no_logged_remind',
                'default' => '您当前未登录！建议登陆后购买，可保存购买订单',
                'attributes' => array(
                    'rows' => 2
                ),
                'sanitize' => false,
                'type' => 'textarea'
            ),

            array(
                'title' => '购买按钮',
                'subtitle' => __('直接显示支付宝、微信购买按钮', 'zib_language'),
                'id' => 'pay_show_allbut',
                'default' => false,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('pay_show_allbut', '==', ''),
                'title' => '快捷支付方式',
                'class' => 'compact',
                'id' => 'default_payment',
                'default' => 'wechat',
                'type' => "radio",
                'help' => '点击购买之后优先弹出的付款方式，用户可点击切换付款方式',
                'inline' => true,
                'options' => array(
                    'wechat' => __('微信', 'zib_language'),
                    'alipay' => __('支付宝', 'zib_language'),
                )
            ),

            array(
                'title' => '资源下载',
                'subtitle' => '独立下载页面',
                'id' => 'pay_down_alone_page',
                'class' => '',
                'default' => false,
                'help' => '开启后，付费资源的下载按钮就会独立页面显示',
                'type' => 'switcher'
            ),
            array(
                'title' => __('货币符号', 'zib_language'),
                'desc' => '（例如 R币）',
                'id' => 'pay_mark',
                'default' => '￥',
                'type' => 'text'
            ),
            array(
                'title' => __('免费资源', 'zib_language'),
                'subtitle' => __('免费资源必须登录后才能查看', 'zib_language'),
                'id' => 'pay_free_logged_show',
                'class' => 'compact',
                'default' => true,
                'type' => 'switcher'
            ),
            array(
                'title' => '订单数据',
                'subtitle' => __('在用户中心显示订单数据', 'zib_language'),
                'class' => 'compact',
                'id' => 'pay_show_user',
                'default' => true,
                'type' => 'switcher'
            ),

            array(
                'title' => '销量显示',
                'subtitle' => __('商品详情显示销售数量', 'zib_language'),
                'id' => 'pay_show_paycount',
                'class' => 'compact',
                'default' => true,
                'type' => 'switcher'
            ),

            array(
                'title' => __('商品默认参数', 'zib_language'),
                'content' => '在下方配置的参数为文章付费功能的默认值，方便新建文章的设置。最终以文章配置为准',
                'type' => "content"
            ),
            array(
                'id' => 'pay_price_default',
                'title' => '执行价',
                'default' => '0.01',
                'type' => 'number',
                'unit' => '元',
                'class' => 'compact'
            ),

            array(
                'id' => 'pay_original_price_default',
                'title' => '原价',
                'subtitle' => '显示在执行价格前面，并划掉',
                'default' => '',
                'type' => 'number',
                'unit' => '元',
                'class' => 'compact'
            ),
            array(
                'title' => _pz('pay_user_vip_1_name') . '价格',
                'id' => 'vip_1_price_default',
                'subtitle' => '填0则为' . _pz('pay_user_vip_1_name') . '免费',
                'default' => '0',
                'type' => 'number',
                'unit' => '元',
                'class' => 'compact'
            ),
            array(
                'title' => _pz('pay_user_vip_2_name') . '价格',
                'id' => 'vip_2_price_default',
                'subtitle' => '填0则为' . _pz('pay_user_vip_1_name') . '免费',
                'default' => '0',
                'type' => 'number',
                'unit' => '元',
                'class' => 'compact'
            ),
            array(
                'dependency' => array('pay_rebate_s', '!=', '', 'all'),
                'title' => '推广折扣',
                'id' => 'pay_rebate_discount',
                'class' => 'compact',
                'subtitle' => __('通过推广链接购买，额外优惠的金额', 'zib_language'),
                'desc' => __('1.需开启推广返佣功能  2.注意此金不能超过实际购买价，避免出现负数', 'zib_language'),
                'default' => '0',
                'type' => 'number',
                'unit' => '元',
            ),
            array(
                'title' => '销量浮动',
                'id' => 'pay_cuont_default',
                'class' => 'compact',
                'subtitle' => __('为真实销量增加或减少的数量', 'zib_language'),
                'default' => '0',
                'type' => 'number',
            ),
            array(
                'title' => '更多详情',
                'class' => 'compact',
                'id' => 'pay_details_default',
                'desc' => __(' （可插入任意的HTML代码）', 'zib_language'),
                'default' => '',
                'sanitize' => false,
                'type' => 'textarea',
                'attributes' => array(
                    'rows' => 3
                ),
            ),
            array(
                'title' => '额外隐藏内容',
                'class' => 'compact',
                'id' => 'pay_extra_hide_default',
                'desc' => __(' （可插入任意的HTML代码）', 'zib_language'),
                'default' => '',
                'sanitize' => false,
                'type' => 'textarea',
                'attributes' => array(
                    'rows' => 3
                ),
            ),

        ),

    ));

    CSF::createSection($prefix, array(
        'parent'      => 'pay',
        'title'       => 'VIP 会员',
        'icon'        => 'fa fa-fw fa-diamond',
        'description' => '',
        'fields'      => array(
            array(
                'content' => '<li>开启付费会员功能之前，请先配置好收款接口，确保网站收款正常</li><li>会员功能的设置项目较多，请仔细核对，避免出现价格、时间的问题</li><li>配合设置名称、有效期等可搭配出不同类型的会员</li><li>管理员可在后台为用户单独开启会员</li><li><a href="' . admin_url('users.php') . '">会员管理</a> | <a target="_blank" href="https://www.zibll.com/767.html">官方教程</a></li>',
                'style' => 'warning',
                'type' => 'submessage',
            ),
            array(
                'title' => __('导航栏购买按钮', 'zib_language'),
                'subtitle' => '在顶部导航栏显示开通会员按钮',
                'id' => 'nav_pay_vip',
                'default' => true,
                'help' => '请注意顶部导航的整体宽度和内容，请勿超宽',
                'type' => 'switcher'
            ),
            array(
                'title' => __('用户框购买按钮', 'zib_language'),
                'subtitle' => '在导航栏用户框内显示开通会员按钮',
                'id' => 'nav_user_pay_vip',
                'class' => 'compact',
                'default' => true,
                'type' => 'switcher'
            ),

            array(
                'id' => 'pay_user_vip_1_s',
                'title' => '一级会员',
                'default' => true,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('pay_user_vip_1_s', '!=', ''),
                'title' => ' ',
                'subtitle' => '显示名称',
                'id' => 'pay_user_vip_1_name',
                'class' => 'compact',
                'default' => '黄金会员',
                'desc' => __('会员名称（例如“黄金会员”、“超级会员”）', 'zib_language'),
                'type' => 'text'
            ),
            array(
                'dependency' => array('pay_user_vip_1_s|pay_user_vip_2_s', '==|!=', '|'),
                'type'    => 'submessage',
                'style'   => 'danger',
                'content' => '<div style="text-align:center"><b><i class="fa fa-fw fa-ban fa-fw"></i> 必须先开启一级会员后才能开启二级会员！</b></div>',
            ),
            array(
                'id' => 'pay_user_vip_2_s',
                'title' => '二级会员',
                'default' => true,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('pay_user_vip_1_s|pay_user_vip_2_s', '!=|!=', '|'),
                'title' => ' ',
                'subtitle' => '显示名称',
                'id' => 'pay_user_vip_2_name',
                'class' => 'compact',
                'default' => '钻石会员',
                'desc' => __('会员名称（例如“黄金会员”、“超级会员”）', 'zib_language'),
                'type' => 'text'
            ),

            array(
                'type'          => 'tabbed',
                'id'            => 'vip_opt',
                'title'         => '会员参数',
                'tabs'          => array(
                    array(
                        'title'     => _pz('pay_user_vip_1_name', '一级会员'),
                        'icon'      => 'fa fa-diamond',
                        'fields'    => CFS_Module::vip_tab(1),
                    ),
                    array(
                        'title'     => _pz('pay_user_vip_2_name', '二级会员'),
                        'icon'      => 'fa fa-diamond',
                        'fields'    => CFS_Module::vip_tab(2),
                    ),
                    array(
                        'title'     => '会员续费',
                        'icon'      => 'fa fa-fw fa-chain-broken',
                        'fields'    => array(
                            array(
                                'dependency' => array('pay_user_vip_1_s', '!=', '', 'all', 'visible'),
                                'id' => 'vip_renew',
                                'title' => '会员续费',
                                'help' => '关闭后则不能续费，只能会员到期之后再购买。永久会员不需要续费',
                                'default' => true,
                                'type' => 'switcher'
                            ),
                            array(
                                'dependency' => array('pay_user_vip_1_s|vip_renew', '!=|!=', '|', 'all', 'visible'),
                                'title' => '续费介绍',
                                'subtitle' => '一句话简介',
                                'id' => 'vip_renew_desc',
                                'default' => '立即续费会员，畅享VIP权益',
                                'desc' => __('续费会员的一句话简介', 'zib_language'),
                                'type' => 'text'
                            ),
                            array(
                                'dependency' => array('pay_user_vip_1_s|vip_renew', '!=|!=', '|', 'all', 'visible'),
                                'title' => '续费商品',
                                'subtitle' => '一句话简介',
                                'id' => 'vip_renew_price_type',
                                'default' => 'discount',
                                'desc' => __('续费的商品价格可以设置为在购买商品的基础上打折或者立减金额，也可以自定义续费商品', 'zib_language'),
                                'type' => "select",
                                'options' => array(
                                    'null ' => __('保持原价(和购买会员相同)', 'zib_language'),
                                    'discount' => __('打折', 'zib_language'),
                                    'reduce' => __('优惠立减', 'zib_language'),
                                    'customize' => __('自定义商品', 'zib_language'),
                                )
                            ),
                            array(
                                'dependency' => array('pay_user_vip_1_s|vip_renew|vip_renew_price_type', '!=|!=|==', '||discount', 'all'),
                                'id' => 'vip_renew_discount',
                                'title' => '打折比例',
                                'desc' => '在会员商品基础上打几折？',
                                'class' => 'compact',
                                'default' => 8,
                                'max' => 9.9,
                                'min' => 0.1,
                                'step' => 0.1,
                                'unit' => '折',
                                'type' => 'slider'
                            ),
                            array(
                                'dependency' => array('pay_user_vip_1_s|vip_renew|vip_renew_price_type', '!=|!=|==', '||reduce', 'all'),
                                'id' => 'vip_renew_reduce',
                                'title' => '优惠金额',
                                'desc' => '在会员商品基础上优惠的金额，优惠后总金额不能为0元',
                                'class' => 'compact',
                                'default' => 20,
                                'max' => 500,
                                'min' => 0,
                                'step' => 5,
                                'unit' => '元',
                                'type' => 'spinner'
                            ),
                            array(
                                'dependency' => array('pay_user_vip_1_s|vip_renew|vip_renew_price_type', '!=|!=|==', '||customize', 'all'),
                                'id'     => 'vip_1_renew_product',
                                'title'  => '一级会员续费',
                                'subtitle' => _pz('pay_user_vip_1_name') . '续费的商品选项',
                                'type'   => 'group',
                                'accordion_title_prefix' => '续费价格：￥',
                                'max'   => 8,
                                'button_title' => '添加续费商品',
                                'default'   => array(
                                    array(
                                        'price' => '69',
                                        'show_price' => '199',
                                        'tag' => '<i class="fa fa-fw fa-bolt"></i> 限时特惠',
                                        'time' => 3,
                                    ),
                                    array(
                                        'price' => '169',
                                        'show_price' => '299',
                                        'tag' => '<i class="fa fa-fw fa-bolt"></i> 站长推荐',
                                        'time' => 6,
                                    ),
                                ),
                                'fields' => CFS_Module::vip_product()
                            ),

                            array(
                                'dependency' => array('pay_user_vip_1_s|vip_renew|vip_renew_price_type', '!=|!=|==', '||customize', 'all'),
                                'id'     => 'vip_2_renew_product',
                                'title'  => '二级会员续费',
                                'subtitle' => _pz('pay_user_vip_2_name') . '续费的商品选项',
                                'type'   => 'group',
                                'accordion_title_prefix' => '续费价格：￥',
                                'max'   => 8,
                                'button_title' => '添加续费商品',
                                'default'   => array(
                                    array(
                                        'price' => '269',
                                        'show_price' => '599',
                                        'tag' => '<i class="fa fa-fw fa-bolt"></i> 限时特惠',
                                        'time' => 3,
                                    ),
                                    array(
                                        'price' => '369',
                                        'show_price' => '899',
                                        'tag' => '<i class="fa fa-fw fa-bolt"></i> 站长推荐',
                                        'time' => 6,
                                    ),
                                ),
                                'fields' => CFS_Module::vip_product()
                            ),

                        ),
                    ),

                    array(
                        'title'     => '会员升级',
                        'icon'      => 'fa fa-fw fa-line-chart',
                        'fields'    => array(
                            array(
                                'dependency' => array('pay_user_vip_1_s|pay_user_vip_2_s', '!=|!=', '|', 'all', 'visible'),
                                'id' => 'vip_upgrade',
                                'title' => '会员升级',
                                'default' => true,
                                'type' => 'switcher'
                            ),
                            array(
                                'dependency' => array('pay_user_vip_1_s|pay_user_vip_2_s|vip_upgrade', '!=|!=|!=', '||', 'all', 'visible'),
                                'title' => '升级介绍',
                                'subtitle' => '一句话简介',
                                'id' => 'vip_upgrade_desc',
                                'default' => '升级VIP会员，享更多会员权益',
                                'desc' => __('升级会员的一句话简介', 'zib_language'),
                                'type' => 'text'
                            ),
                            array(
                                'dependency' => array('pay_user_vip_1_s|pay_user_vip_2_s|vip_upgrade', '!=|!=|!=', '||', 'all', 'visible'),
                                'id'            => 'vip_upgrade_product',
                                'type'          => 'accordion',
                                'title'         => '升级价格',
                                'subtitle'      => '会员升级的商品选项',
                                'accordions'    => array(
                                    array(
                                        'title'     => '月费会员升级月费会员',
                                        'fields'    => array(
                                            array(
                                                'content' => '月费会员升级为月费会员，用户会员有效期不会改变<br/>价格按照会员剩余天数计算升级价格，请在下方设置每天的单价',
                                                'style' => 'warning',
                                                'type' => 'submessage',
                                            ),
                                            array(
                                                'id' => 'unit_price',
                                                'title' => '天单价',
                                                'default' => '10',
                                                'type' => 'number',
                                                'unit' => '元',
                                            ),
                                            array(
                                                'id' => 'unit_tag',
                                                'title' => '促销标签',
                                                'class' => 'compact',
                                                'desc' => '支持HTML，请注意控制长度',
                                                'default' => '<i class="fa fa-fw fa-bolt"></i> 站长推荐',
                                                'attributes' => array(
                                                    'rows' => 1
                                                ),
                                                'type' => 'textarea'
                                            ),
                                            array(
                                                'dependency' => array('unit_tag', '!=', ''),
                                                'title' => '标签颜色',
                                                'id' => "unit_tag_class",
                                                'class' => 'compact skin-color',
                                                'default' => "jb-yellow",
                                                'type' => "palette",
                                                'options' => CFS_Module::zib_palette()
                                            ),
                                        )
                                    ),
                                    array(
                                        'title'     => '月费会员升级永久会员',
                                        'fields'    => array(
                                            array(
                                                'content' => '如果网站可以购买月费会员和永久会员，则可以开启此项<br/>允许用户由月费会员直接升级到更高一级的永久会员<br>用户之前还未到期的部分将自动一起转为永久会员，可能会涉及到差价问题，请自行告知用户<br>如果网站没有永久会员的购买选项请务必关闭此项',
                                                'style' => 'warning',
                                                'type' => 'submessage',
                                            ),
                                            array(
                                                'title' => __('跨越升级', 'zib_language'),
                                                'id' => 'jump_s',
                                                'class' => '',
                                                'default' => false,
                                                'type' => 'switcher'
                                            ),
                                            array(
                                                'dependency' => array('jump_s', '!=', ''),
                                                'id' => 'jump_price',
                                                'title' => '执行价',
                                                'desc' => '永久会员升级永久会员的价格',
                                                'default' => '199',
                                                'type' => 'number',
                                                'unit' => '元',
                                            ),
                                            array(
                                                'dependency' => array('jump_s', '!=', ''),
                                                'id' => 'jump_show_price',
                                                'title' => '原价',
                                                'desc' => '显示在执行价格前面，并划掉',
                                                'default' => '299',
                                                'type' => 'number',
                                                'unit' => '元',
                                                'class' => 'compact'
                                            ),
                                            array(
                                                'dependency' => array('jump_s', '!=', ''),
                                                'id' => 'jump_tag',
                                                'title' => '促销标签',
                                                'class' => 'compact',
                                                'default' => '<i class="fa fa-fw fa-bolt"></i> 升级特惠',
                                                'desc' => '支持HTML，请注意控制长度',
                                                'attributes' => array(
                                                    'rows' => 1
                                                ),
                                                'type' => 'textarea'
                                            ),
                                            array(
                                                'dependency' => array('jump_tag|jump_s', '!=|!=', '|'),
                                                'title' => '标签颜色',
                                                'id' => "jump_tag_class",
                                                'class' => 'compact skin-color',
                                                'default' => "jb-red",
                                                'type' => "palette",
                                                'options' => CFS_Module::zib_palette()
                                            ),
                                        )
                                    ),
                                    array(
                                        'title'     => '永久会员升级永久会员',
                                        'fields'    => array(
                                            array(
                                                'id' => 'permanent_price',
                                                'title' => '执行价',
                                                'desc' => '永久会员升级永久会员的价格',
                                                'default' => '199',
                                                'type' => 'number',
                                                'unit' => '元',
                                            ),
                                            array(
                                                'id' => 'permanent_show_price',
                                                'title' => '原价',
                                                'desc' => '显示在执行价格前面，并划掉',
                                                'default' => '299',
                                                'type' => 'number',
                                                'unit' => '元',
                                                'class' => 'compact'
                                            ),
                                            array(
                                                'id' => 'permanent_tag',
                                                'title' => '促销标签',
                                                'class' => 'compact',
                                                'default' => '<i class="fa fa-fw fa-bolt"></i> 升级特惠',
                                                'desc' => '支持HTML，请注意控制长度',
                                                'attributes' => array(
                                                    'rows' => 1
                                                ),
                                                'type' => 'textarea'
                                            ),
                                            array(
                                                'dependency' => array('permanent_tag', '!=', ''),
                                                'title' => '标签颜色',
                                                'id' => "permanent_tag_class",
                                                'class' => 'compact skin-color',
                                                'default' => "jb-red",
                                                'type' => "palette",
                                                'options' => CFS_Module::zib_palette()
                                            ),
                                        )
                                    ),
                                )
                            ),

                        ),
                    ),
                )
            ),
            array(
                'dependency' => array('pay_user_vip_1_s', '!=', ''),
                'title' => '会员介绍',
                'subtitle' => '一句话简介',
                'id' => 'pay_user_vip_desc',
                'default' => '开通VIP会员，享受会员专属折扣以及多项特权',
                'desc' => __('显示在开通界面顶部一句话简介，可以为会员权益简介或者活动介绍', 'zib_language'),
                'type' => 'text'
            ),
            array(
                'dependency' => array('pay_user_vip_1_s', '!=', ''),
                'id' => 'pay_user_vip_more',
                'title' => ' ',
                'subtitle' => '开通会员更多内容',
                'class' => 'compact',
                'default' => '<li>购买后不支持退款</li>
<li>VIP权益仅适用于本站</li>
<li>欢迎与站长联系</li>',
                'desc' => '显示在开通界面底部位置，可以为提醒事项、用户协议等，支持HTML代码',
                'attributes' => array(
                    'rows' => 4
                ),
                'sanitize' => false,
                'type' => 'textarea'
            ),
        ),
    ));
    CSF::createSection($prefix, array(
        'parent'      => 'pay',
        'title'       => '推广返佣',
        'icon'        => 'fa fa-fw fa-btc',
        'description' => '',
        'fields'      => array(
            array(
                'title' => __('推广返佣', 'zib_language'),
                'id' => 'pay_rebate_s',
                'class' => '',
                'default' => false,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('pay_rebate_s', '!=', '', '', 'visible'),
                'content' => '<p><b>推广返佣设置注意事项：</b></p>
                <li>识别模式：绑定注册->用户只要是通过任意推广链接注册的，今后此用户消费均会给推荐人返佣</li>
                <li>识别模式：仅推广链接->只有通过推广链接购买的才会给推荐人返佣，不会考虑是否是推荐注册的</li>
                <li>开启的订单类型如果一个都不勾选，则代表该用户类型不参与此功能</li>
                <li>您可以根据网站的VIP设置相互配合设置相应的规则</li>
                <li>下面的设置为全局设置，还可以单独为每一个用户设置规则和比例</li>
                <li><a href="' . admin_url('users.php') . '">用户管理</a> | <a target="_blank" href="https://www.zibll.com/?s=%E6%8E%A8%E5%B9%BF%E8%BF%94%E4%BD%A3">官方教程</a></li>',
                'style' => 'warning',
                'type' => 'submessage',
            ),

            array(
                'dependency' => array('pay_rebate_s', '!=', '', '', 'visible'),
                'title' => __('推广识别模式', 'zib_language'),
                'id' => 'pay_rebate_judgment',
                'default' => 'all',
                'type' => "radio",
                'options' => array(
                    'all' => __('绑定注册', 'zib_language'),
                    'link' => __('仅推广链接', 'zib_language'),
                )
            ),
            array(
                'dependency' => array('pay_rebate_s', '!=', '', '', 'visible'),
                'type'          => 'tabbed',
                'id'            => 'rebate_rule',
                'subtitle'      => '为不同用户类型设置返佣规则',
                'title'         => '返佣规则',
                'tabs'          => array(
                    array(
                        'title'     => '普通用户',
                        'icon'      => 'fa fa-fw fa-user-o',
                        'fields'    => array(
                            array(
                                'title'      => '返佣订单',
                                'desc'      => '普通用户返利的订单类型，全部关闭，则代表普通用户不参与推广返佣',
                                'default' => array(),
                                'id' => 'pay_rebate_user_s',
                                'type' => 'checkbox',
                                'options' => CFS_Module::rebate_type()
                            ),
                            array(
                                'dependency' => array('pay_rebate_user_s', '!=', '', '', 'visible'),
                                'title'      => ' ',
                                'subtitle' => '普通用户返佣比例',
                                'id' => 'pay_rebate_ratio',
                                'class' => 'compact',
                                'default' => 5,
                                'max' => 100,
                                'min' => 0,
                                'step' => 1,
                                'unit' => '%',
                                'type' => 'spinner'
                            ),
                        ),
                    ),
                    array(
                        'title'     => _pz('pay_user_vip_1_name', '一级会员'),
                        'icon'      => 'fa fa-fw fa-diamond',
                        'fields'    => array(
                            array(
                                'title'      => '返佣订单',
                                'desc'      => _pz('pay_user_vip_1_name') . '返利的订单类型，全部关闭，则代表' . _pz('pay_user_vip_1_name') . '用户不参与推广返佣',
                                'id' => 'pay_rebate_user_s_1',
                                'default' => array('all'),
                                'type' => 'checkbox',
                                'options' => CFS_Module::rebate_type()
                            ),
                            array(
                                'dependency' => array('pay_rebate_user_s_1', '!=', '', '', 'visible'),
                                'title'      => ' ',
                                'subtitle' =>  _pz('pay_user_vip_1_name') . '返佣比例',
                                'id' => 'pay_rebate_ratio_vip_1',
                                'class' => 'compact',
                                'default' => 10,
                                'max' => 100,
                                'min' => 0,
                                'step' => 1,
                                'unit' => '%',
                                'type' => 'spinner'
                            ),
                        ),
                    ),
                    array(
                        'title'     => _pz('pay_user_vip_2_name', '二级会员'),
                        'icon'      => 'fa fa-fw fa-diamond',
                        'fields'    => array(
                            array(
                                'title'      => '返佣订单',
                                'desc'      => _pz('pay_user_vip_2_name') . '返利的订单类型，全部关闭，则代表' . _pz('pay_user_vip_2_name') . '用户不参与推广返佣',
                                'id' => 'pay_rebate_user_s_2',
                                'default' => array('all'),
                                'type' => 'checkbox',
                                'options' => CFS_Module::rebate_type()
                            ),
                            array(
                                'dependency' => array('pay_rebate_user_s_2', '!=', '', '', 'visible'),
                                'title'      => ' ',
                                'subtitle' =>  _pz('pay_user_vip_2_name') . '返佣比例',
                                'id' => 'pay_rebate_ratio_vip_2',
                                'class' => 'compact',
                                'default' => 20,
                                'max' => 100,
                                'min' => 0,
                                'step' => 1,
                                'unit' => '%',
                                'type' => 'spinner'
                            ),
                        ),
                    ),
                )
            ),

            array(
                'dependency' => array('pay_rebate_s', '!=', ''),
                'title' => __('返佣文案', 'zib_language'),
                'id' => 'pay_rebate_text_desc',
                'subtitle' => __('一句话简介', 'zib_language'),
                'desc' => __('一句话简介，内容不易过多', 'zib_language'),
                'default' => '加入分享计划，获得高额奖励',
                'type' => 'text',
            ),
            array(
                'dependency' => array('pay_rebate_s', '!=', ''),
                'class' => 'compact',
                'id' => 'pay_rebate_text_details_title',
                'title' => __('返佣详情：', 'zib_language'),
                'subtitle' => __('返佣详情介绍的标题', 'zib_language'),
                'default' => '返佣详解',
                'type' => 'text',
            ),
            array(
                'dependency' => array('pay_rebate_s', '!=', ''),
                'class' => 'compact',
                'title' => ' ',
                'subtitle' => __('返佣详情详细内容', 'zib_language'),
                'id' => 'pay_rebate_text_details',
                'default' => '<p>此处的推广链接或登陆后任意文章生成的分享链接均有效</p>
        <p>通过您的推广链接打开本站后，在本站购买商品即可获得佣金</p>
        <p>通过您的推广链接注册后的用户今后购买的商品均可获得佣金</p>
        <p>通过您的推广链接购买部分商品还有额外优惠哦</p>
        <p>当佣金积累到50元之后，即可申请提现</p>
        <p>申请提现后，需后台人工处理，一般2-3小时，请耐心等待</p>
        <p>如需申请更高的返佣比例，或有其它疑问，请联系站长</p>',
                'desc' => '返佣详情介绍，建议为规则介绍或者其他说明</br>支持HTML代码，请注意代码规范及标签闭合',
                'sanitize' => false,
                'type' => 'textarea',
                'attributes' => array(
                    'rows' => 6
                ),
            ),

            array(
                'dependency' => array('pay_rebate_s', '!=', ''),
                'content' => '<p><b>推广让利标签配置详解：</b></p>
                <li>可使用的变量：折扣<code>%discount%</code> 推荐人姓名<code>%referrer_name%</code></li>
                <li>使用变量示例：<code>%referrer_name%推荐购买 下单再减%discount%元</code> 老唐推荐购买 下单再减100元</li>
                <li><a target="_blank" href="https://www.zibll.com/?s=%E6%8E%A8%E5%B9%BF%E8%BF%94%E4%BD%A3">官方教程</a></li>',
                'style' => 'warning',
                'type' => 'submessage',
            ),
            array(
                'dependency' => array('pay_rebate_s', '!=', ''),
                'title' => __('推广让利 标签', 'zib_language'),
                'desc' => __('显示在购买模块，推广让利的标签文案', 'zib_language'),
                'id' => 'pay_rebate_text_discount',
                'default' => '会员推荐 下单再减%discount%元',
                'type' => 'text',
            ),

            array(
                'dependency' => array('pay_rebate_s', '!=', '', '', 'visible'),
                'title' => __('提现限制', 'zib_language'),
                'id' => 'pay_rebate_withdraw_lowest_money',
                'desc' => __('用户佣金低于此金额时，不能在用户中心提交提现申请。', 'zib_language'),
                'default' => '50',
                'type' => 'number',
                'unit' => '元',
            ),

            array(
                'dependency' => array('pay_rebate_s', '!=', ''),
                'title' => __('提现协议', 'zib_language'),
                'id' => 'pay_rebate_withdraw_text_details',
                'default' => '<div>当佣金积累到50元之后，即可申请提现</div>
        <div>申请提现需后台人工处理，一般2-3小时，请耐心等待</div>
        <div>如有其它疑问，请联系站长</div>',
                'desc' => '用户申请提现时展示的内容，建议为提现须知等（使用HTML代码请注意代码准确性）',
                'sanitize' => false,
                'type' => 'textarea',
                'attributes' => array(
                    'rows' => 4
                ),
            ),
        ),
    ));



    CSF::createSection($prefix, array(
        'parent'      => 'pay',
        'title'       => '收款接口',
        'icon'        => 'fa fa-fw fa-credit-card',
        'description' => '',
        'fields'      => array(
            array(
                'content' => '<p><b>以下收款接口，子比主题仅提供API接入服务，收款平台的可靠性请自行斟酌！</b></p>
                <li>涉及到资金及信息安全，请勿使用盗版主题</li>
                <li>收款接口选用，有相关执照的商家推荐使用官方接口。个人用户推荐使用讯虎PAY和Payjs</li>
                <li>如需定制其它收款接口，欢迎<a href="http://wpa.qq.com/msgrd?v=3&amp;uin=1234567788&amp;site=qq&amp;menu=yes" title="QQ联系">与我QQ联系</a></li>
                <li><a target="_blank" href="https://www.zibll.com/580.html" class="loginbtn">付费功能官方教程</a></li>',
                'style' => 'warning',
                'type' => 'submessage',
            ),

            array(
                'id' => 'pay_wechat_sdk_options',
                'default' => 'null',
                'title' => '微信收款接口',
                'type' => "select",
                'options' => array(
                    'xhpay' => __('迅虎PAY-微信', 'zib_language'),
                    'payjs' => __('PAYJS-微信', 'zib_language'),
                    'xunhupay_wechat' => __('虎皮椒V3-微信', 'zib_language'),
                    'official_wechat' => __('微信官方', 'zib_language'),
                    'codepay_wechat' => __('码支付-微信', 'zib_language'),
                    'null' => __('关闭微信收款', 'zib_language'),
                )
            ),

            array(
                'id' => 'pay_alipay_sdk_options',
                'default' => 'null',
                'title' => '支付宝收款接口',
                'class' => 'compact',
                'type' => "select",
                'options' => array(
                    'xhpay' => __('迅虎PAY-支付宝', 'zib_language'),
                    'payjs' => __('PAYJS-支付宝', 'zib_language'),
                    'xunhupay_alipay' => __('虎皮椒V3-支付宝', 'zib_language'),
                    'official_alipay' => __('支付宝企业支付/当面付', 'zib_language'),
                    'codepay_alipay' => __('码支付-支付宝', 'zib_language'),
                    'null' => __('关闭支付宝收款', 'zib_language'),
                )
            ),
            array(
                'id'            => 'official_alipay',
                'type'          => 'accordion',
                'title'         => '支付宝官方',
                'accordions'    => array(
                    array(
                        'title'     => '支付宝官方',
                        'fields'    => array(
                            array(
                                'title' => '支付宝公钥',
                                'subtitle' => 'publickey(必填)',
                                'id' => 'publickey',
                                'default' => '',
                                'attributes' => array(
                                    'rows' => 4
                                ),
                                'sanitize' => false,
                                'type' => 'textarea'
                            ),
                            array(
                                'content' => '<p>支付宝当面付：个人可申请，申请难度低</p>
                                <li>支持PC端扫码支付</li>
                                <li><b>支持移动端H5支付</b></li>
                                <li>如需接入此方式请填写下方参数，反之请留空</li>
                                <li>同时开启企业支付以及当面付，则优先使用当面付</li>
                                <li>申请地址：<a target="_blank" href="https://b.alipay.com/signing/productDetailV2.htm?productId=I1011000290000001003">点击跳转</a></li>',
                                'style' => 'info',
                                'type' => 'submessage',
                            ),
                            array(
                                'title' => '当面付：APPID',
                                'id' => 'appid',
                                'default' => '',
                                'type' => 'text'
                            ),
                            array(
                                'title' => '当面付：应用私钥',
                                'subtitle' => 'privatekey',
                                'class' => 'compact',
                                'id' => 'privatekey',
                                'default' => '',
                                'attributes' => array(
                                    'rows' => 4
                                ),
                                'sanitize' => false,
                                'type' => 'textarea'
                            ),

                            array(
                                'content' => '<p>支付宝企业支付：官方接口，商家可申请，需签约<b>电脑网站支付</b>。
                                申请地址：<a target="_blank" href="https://b.alipay.com/signing/productDetailV2.htm?productId=I1011000290000001000">点击跳转</a></p> ',
                                'style' => 'info',
                                'type' => 'submessage',
                            ),
                            array(
                                'title' => '网站应用：APPID',
                                'id' => 'webappid',
                                'default' => '',
                                'type' => 'text'
                            ),
                            array(
                                'title' => '网站应用：应用私钥',
                                'subtitle' => 'appPrivateKey',
                                'class' => 'compact',
                                'id' => 'webprivatekey',
                                'default' => '',
                                'attributes' => array(
                                    'rows' => 4
                                ),
                                'sanitize' => false,
                                'type' => 'textarea'
                            ),
                            array(
                                'title' => '开启H5支付',
                                'id' => 'h5',
                                'class' => 'compact',
                                'default' => false,
                                'desc' => '移动端自动跳转到支付宝APP支付，需签约<b>手机网站支付</b>',
                                'type' => 'switcher'
                            ),
                        )
                    ),
                )
            ),
            array(
                'id'            => 'official_wechat',
                'type'          => 'accordion',
                'title'         => '微信官方',
                'accordions'    => array(
                    array(
                        'title'     => '微信企业支付',
                        'fields'    => array(
                            array(
                                'content' => '<p>微信企业支付：官方接口，商家可申请，有年费，申请较难</p>
                                <p><b>回调地址：' . ZIB_STYLESHEET_DIRECTORY_URI . '/zibpay/shop/weixin/return.php</b></p>
                                <li>支持PC端扫码支付</li>
                                <li>支持移动端H5支付</li>',
                                'style' => 'info',
                                'type' => 'submessage',
                            ),
                            array(
                                'title' => '商户号 PartnerID',
                                'id' => 'merchantid',
                                'default' => '',
                                'type' => 'text'
                            ),
                            array(
                                'title' => '公众号或小程序APPID',
                                'class' => 'compact',
                                'id' => 'appid',
                                'default' => '',
                                'type' => 'text'
                            ),
                            array(
                                'title' => '支付API密钥',
                                'class' => 'compact',
                                'default' => '',
                                'id' => 'key',
                                'type' => 'text'
                            ),
                            /**
                            array(
                                'title' => 'JSAPI支付',
                                'id' => 'jsapi',
                                'class' => 'compact',
                                'default' => false,
                                'desc' => '微信内打开直接发起支付',
                                'type' => 'switcher'
                            ), */
                            array(
                                'title' => 'H5支付',
                                'id' => 'h5',
                                'class' => 'compact',
                                'default' => false,
                                'desc' => '移动端自动跳转到微信APP支付，需开通<b>H5支付</b>',
                                'type' => 'switcher'
                            ),
                        )
                    ),
                )
            ),
            array(
                'id'            => 'xunhupay',
                'type'          => 'accordion',
                'title'         => '虎皮椒V3',
                'accordions'    => array(
                    array(
                        'title'     => '虎皮椒V3',
                        'fields'    => array(
                            array(
                                'content' => '<p>虎皮椒是迅虎网络旗下的支付产品，无需营业执照、无需企业，申请简单。适合个人站长申请，有一定的费用</p>
                                <li>支持PC端扫码支付</li>
                                <li>支付宝支持移动端跳转APP支付</li>
                                <li>微信支持微信APP内支付</li>
                                <li>开通地址：<a target="_blank" href="https://admin.xunhupay.com/sign-up/12207.html">点击跳转</a></li>',
                                'style' => 'info',
                                'type' => 'submessage',
                            ),
                            array(
                                'title' => '微信：APPID',
                                'id' => 'wechat_appid',
                                'default' => '',
                                'type' => 'text'
                            ),
                            array(
                                'title' => '微信：秘钥secret',
                                'class' => 'compact',
                                'id' => 'wechat_appsecret',
                                'default' => '',
                                'type' => 'text'
                            ),
                            array(
                                'title' => '支付宝：APPID',
                                'id' => 'alipay_appid',
                                'type' => 'text'
                            ),
                            array(
                                'title' => '支付宝：秘钥secret',
                                'class' => 'compact',
                                'id' => 'alipay_appsecret',
                                'default' => '',
                                'type' => 'text'
                            ),
                        )
                    ),
                )
            ),
            array(
                'id'            => 'xhpay',
                'type'          => 'accordion',
                'title'         => '迅虎PAY',
                'accordions'    => array(
                    array(
                        'title'     => '迅虎PAY（虎皮椒V4）',
                        'fields'    => array(
                            array(
                                'content' => '<p>迅虎PAY又叫虎皮椒V4，是迅虎网络打造的一个全新的个人收款平台，申请简单，适合个人站长</p>
                                <li>微信、支付宝支持PC端扫码支付</li>
                                <li>微信支持微信内支付、APP跳转支付（H5支付）</li>
                                <li>支付宝APP跳转支付（H5支付）</li>
                                <li>微信内支付请联系讯虎客服手动设置返回域名</li>
                                <li>开通地址：<a target="_blank" href="https://pay.xunhuweb.com">点击跳转</a></li>',
                                'style' => 'info',
                                'type' => 'submessage',
                            ),
                            array(
                                'title' => '商户号 mchid',
                                'id' => 'mchid',
                                'default' => '',
                                'type' => 'text'
                            ),
                            array(
                                'title' => 'API密钥 key',
                                'class' => 'compact',
                                'default' => '',
                                'id' => 'key',
                                'type' => 'text'
                            ),
                        )
                    ),
                )
            ),
            array(
                'id'            => 'payjs',
                'type'          => 'accordion',
                'title'         => 'PAYJS',
                'accordions'    => array(
                    array(
                        'title'     => 'PAYJS',
                        'fields'    => array(
                            array(
                                'content' => '<p>PAYJS支持微信、支付宝收款，个人可申请，申请方便，有一定费用</p>
                                <li>微信、支付宝支持PC端扫码支付</li>
                                <li>微信支持微信内支付、APP跳转支付（H5支付）</li>
                                <li>支持微信内自动跳转微信收银台付款，此界面的LOGO调用的是全局桌面图标</li>
                                <li>如果选择了支付宝接口也为PAYJS，请确保您的帐号开通了支付宝收款</li>
                                <li>开通地址：<a target="_blank" href="https://payjs.cn">点击跳转</a></li>',
                                'style' => 'info',
                                'type' => 'submessage',
                            ),
                            array(
                                'title' => '商户号 mchid',
                                'default' => '',
                                'id' => 'mchid',
                                'type' => 'text'
                            ),
                            array(
                                'title' => 'API密钥 key',
                                'default' => '',
                                'class' => 'compact',
                                'id' => 'key',
                                'type' => 'text'
                            ),
                        )
                    ),
                )
            ),
            array(
                'id'            => 'codepay',
                'type'          => 'accordion',
                'title'         => '码支付',
                'accordions'    => array(
                    array(
                        'title'     => '码支付',
                        'fields'    => array(
                            array(
                                'content' => '<p>码支付支持微信、支付宝收款，个人可申请</p>
                                <li>支持PC端扫码支付</li>
                                <li>请注意码支付的通知设置，基础版需要软件挂机。</li>
                                <li>在码支付后台无需填写通知地址</li>
                                <li>开通地址：<a target="_blank" href="https://codepay.fateqq.com/i/490017">点击跳转</a></li>',
                                'style' => 'info',
                                'type' => 'submessage',
                            ),
                            array(
                                'title' => '码支付ID',
                                'id' => 'id',
                                'default' => '',
                                'type' => 'text'
                            ),
                            array(
                                'title' => '通信密钥',
                                'class' => 'compact',
                                'id' => 'key',
                                'default' => '',
                                'type' => 'text'
                            ),
                            array(
                                'title' => 'Token',
                                'class' => 'compact',
                                'id' => 'token',
                                'default' => '',
                                'type' => 'text'
                            ),
                        )
                    ),
                )
            ),

        ),
    ));
    CSF::createSection($prefix, array(
        'parent'      => 'basic',
        'title'       => '自定义代码',
        'icon'        => 'fa fa-fw fa-code',
        'description' => '',
        'fields'      => array(
            array(
                'content' => '<p><b>自定义代码提醒事项：</b></p><li>任何情况下都不建议修改主题源文件，自定义代码可放于此处</li><li>在此处添加的自定义代码会保存到数据库，不会因主题升级而丢失</li><li>使用自义定代码，需要有一定的代码基础</li><li>代码不规范、或代码错误将会引起意料不到的问题</li><li>如果网站遇到未知错误，请首先检查此处的代码是否规范、无误</li>',
                'style' => 'warning',
                'type' => 'submessage',
            ),
            array(
                'title' => __('自定义CSS样式', 'zib_language'),
                'subtitle' => '位于&lt;/head&gt;之前，直接写样式代码，不用添加&lt;style&gt;标签',
                'id' => 'csscode',
                'default' => '',
                'settings' => array(
                    'mode'   => 'css',
                    'theme'   => 'dracula',
                ),
                'sanitize' => false,
                'type' => 'code_editor'
            ),
            array(
                'title' => __('自定义javascript代码', 'zib_language'),
                'subtitle' => '位于底部，直接填写JS代码，不需要添加&lt;script&gt;标签',
                'id' => 'javascriptcode',
                'default' => '',
                'settings' => array(
                    'mode'   => 'javascript',
                    'theme'   => 'dracula',
                ),
                'sanitize' => false,
                'type' => 'code_editor'
            ),
            array(
                'title' => __('自定义头部HTML代码', 'zib_language'),
                'subtitle' => __(esc_attr('位于</head>之前，这部分代码是在主要内容显示之前加载，通常是CSS样式、自定义的<meta>标签、全站头部JS等需要提前加载的代码，需填HTML标签'), 'zib_language'),
                'id' => 'headcode',
                'default' => '',
                'settings' => array(
                    'theme'   => 'dracula',
                ),
                'sanitize' => false,
                'type' => 'code_editor'
            ),
            array(
                'title' => __('自定义底部HTML代码', 'zib_language'),
                'subtitle' => '位于&lt;/body&gt;之前，这部分代码是在主要内容加载完毕加载，通常是JS代码，需填HTML标签',
                'id' => 'footcode',
                'default' => '',
                'settings' => array(
                    'theme'   => 'dracula',
                ),
                'sanitize' => false,
                'type' => 'code_editor'
            ),
            array(
                'title' => __('网站统计HTML代码', 'zib_language'),
                'subtitle' => '位于底部，用于添加第三方流量数据统计代码，如：Google analytics、百度统计、CNZZ、51la，国内站点推荐使用百度统计，国外站点推荐使用Google analytics。需填HTML标签，如果是javascript代码，请保存在自定义javascript代码',
                'id' => 'trackcode',
                'default' => '',
                'settings' => array(
                    'theme'   => 'dracula',
                ),
                'sanitize' => false,
                'type' => 'code_editor'
            ),
        ),
    ));

    CSF::createSection($prefix, array(
        'parent'      => 'over',
        'title'       => '系统工具',
        'icon'        => 'fa fa-fw fa-gavel',
        'description' => '',
        'fields'      => array(
            array(
                'id' => 'hide_admin_bar',
                'type' => 'switcher',
                'label' => "开启后则不显示WordPress顶部黑条",
                'default' => true,
                'title' => '关闭顶部admin_bar'
            ),
            array(
                'id' => 'disabled_pingback',
                'type' => 'switcher',
                'default' => true,
                'title' => '防pingback攻击'
            ),
            array(
                'id' => 'remove_emoji',
                'type' => 'switcher',
                'default' => true,
                'title' => '删除WordPress自带Emoji表情'
            ),
            array(
                'id' => 'remove_open_sans',
                'type' => 'switcher',
                'default' => true,
                'title' => '禁用Google字体'
            ),
            array(
                'id' => 'remove_more_wp_head',
                'type' => 'switcher',
                'default' => true,
                'title' => '清理多于的头部Meta标签'
            ),
            array(
                'id' => 'newfilename',
                'type' => 'switcher',
                'label' => '上传文件自动重命名为随机英文名',
                'default' => false,
                'title' => __('上传文件重命名', 'zib_language')
            ),
            array(
                'id' => 'display_wp_update',
                'type' => 'switcher',
                'default' => false,
                'title' => '禁止WordPress检测更新'
            ),
            array(
                'id' => 'search_no_page',
                'label' => '在搜索页只能搜索文章内容，不能搜索页面内容',
                'type' => 'switcher',
                'default' => false,
                'title' => '搜索内容排除页面'
            ),
            array(
                'id' => 'no_repetition_name',
                'label' => '前端注册或修改资料，不允许修改为已存在的昵称(不影响后台修改)',
                'type' => 'switcher',
                'default' => true,
                'title' => '禁止重复昵称'
            ),
            array(
                'id' => 'admin_user_del_fields',
                'label' => '开启后在后台编辑用户资料时将不显示无用的多余选项',
                'type' => 'switcher',
                'default' => true,
                'title' => '关闭后台用户编辑多余的选项'
            ),
            array(
                'title' => '前端页面编辑',
                'id' => 'admin_frontend_set',
                'type' => 'switcher',
                'default' => true,
                'label' => '管理员可在前台快速修改页面、文章参数'
            ),
            array(
                'title' => '禁用古腾堡编辑器',
                'id' => 'close_gutenberg',
                'type' => 'switcher',
                'default' => false,
                'label' => '后台编辑器仍然使用4.9的编辑器',
                'desc' => '<b style="color:#fb5757;"><i class="fa fa-fw fa-info-circle fa-fw"></i> 禁用后主题的所有编辑器增强功能将都不能使用！请酌情禁用！</b></br><a target="_blank" href="https://www.zibll.com/zibll_word/%e5%8f%a4%e8%85%be%e5%a0%a1%e7%bc%96%e8%be%91%e5%99%a8">查看主题强大的编辑器相关教程</a>'
            ),

            array(
                'title' => '倒计时显示',
                'id' => 'time_ago_s',
                'type' => 'switcher',
                'label' => '时间格式化为：X分钟前，X小时前，X天前....',
                'default' => true,
            ),

            array(
                'dependency' => array('time_ago_s', '==', '', '', 'visible'),
                'title' => ' ',
                'subtitle' => '自定义时间格式',
                'id' => 'time_format',
                'type' => "text",
                'desc' => '时间格式接受标准时间格式，请注意控制长度！',
                'class' => 'compact',
                'default' => 'n月j日 H:i',
            ),
            array(
                'title' => __('框架文件CDN托管', 'zib_language'),
                'id' => 'js_outlink',
                'default' => "no",
                'desc' => '将核心框架JS文件和CSS文件托管到CDN，可提高加载速度。如果页面显示不正常，请关闭！',
                'type' => "radio",
                'options' => array(
                    'no' => __('不托管', 'zib_language'),
                    'baidu' => __('百度', 'zib_language'),
                    'staticfile' => __('七牛云', 'zib_language'),
                    'bootcdn' => __('BootCDN', 'zib_language'),
                    'he' => __('框架来源站点', 'zib_language')
                )
            ),

            array(
                'title' => '前端上传限制',
                'id' => 'up_max_size',
                'default' => 4,
                'desc' => __('前端允许上传的最大图像大小（单位M,为0则不限制）', 'zib_language'),
                'max' => 10,
                'min' => 0,
                'step' => 0.5,
                'unit' => 'M',
                'type' => 'spinner'
            ),

        ),
    ));
    CSF::createSection($prefix, array(
        'parent'      => 'over',
        'title'       => '百度熊掌号',
        'icon'        => 'fa fa-fw fa-paw',
        'description' => '',
        'fields'      => array(
            array(
                'content' => '<i class="fa fa-fw fa-info-circle fa-fw"></i> 由于百度官方原因，此功能已不推荐使用',
                'style' => 'warning',
                'type' => 'submessage',
            ),
            array(
                'title' => __('百度熊掌号', 'zib_language'),
                'id' => 'xzh_on',
                'default' => false,
                'desc' => ' 开启',
                'type' => 'switcher'
            ),

            array(
                'title' => '熊掌号 AppID',
                'id' => 'xzh_appid',
                'default' => '',
                'type' => 'text'
            ),
            array(
                'title' => __('显示熊掌号', 'zib_language'),
                'id' => 'xzh_render_tail',
                'class' => '',
                'default' => true,
                'subtitle' => '文章内容底部',
                'type' => 'switcher'
            ),

            array(
                'title' => __('添加JSON_LD数据', 'zib_language'),
                'id' => 'xzh_jsonld_single',
                'class' => '',
                'default' => true,
                'subtitle' => '文章页添加',
                'type' => 'switcher'
            ),

            array(
                'title' => ' ',
                'class' => 'compact',
                'id' => 'xzh_jsonld_page',
                'default' => false,
                'subtitle' => '页面添加',
                'type' => 'switcher'
            ),

            array(
                'title' => ' ',
                'id' => 'xzh_jsonld_img',
                'subtitle' => '不添加图片',
                'class' => 'compact',
                'default' => false,
                'type' => 'switcher'
            ),
        ),
    ));
    CSF::createSection($prefix, array(
        'parent'      => 'over',
        'title'       => '文档模式',
        'icon'        => 'fa fa-fw fa-file-text',
        'description' => '',
        'fields'      => array(
            array(
                'content' => '<p><b>文档模式：</b></p><li>文档模式适合帮助文档、使用文档等类型的文章使用</li><li>此模式会自动搜索二级分类及文章生成列表，请选择一级分类</li><li>为了良好的效果，文章分类请选择最后的子分类</li><li>请勿依赖此功能，今后可能会取消此功能</li>',
                'style' => 'warning',
                'type' => 'submessage',
            ),
            array(
                'title' => __('文档模式', 'zib_language'),
                'subtitle' => '开启文档模式的分类',
                'id' => 'docs_mode_cats',
                'desc' => __('', 'zib_language'),
                'default' => array(),
                'options' => 'categories',
                'type' => 'checkbox'
            ),
            array(
                'title' => __('在首页排除此类内容', 'zib_language'),
                'id' => 'docs_mode_exclude',
                'class' => 'compact',
                'type' => 'switcher',
                'default' => true,
                'desc' => '开启之后，在网站首页不显示文档模式的相关内容，不影响小工具、其他位置以及首页置顶文章的显示'
            ),
        ),
    ));

    CSF::createSection('zibll_options', array(
        'title'       => '主题&授权',
        'icon'        => 'fa fa-fw fa-shield',
        'description' => '',
        'fields'      => array(
            array(
                'type'    => 'submessage',
                'style'   => 'warning',
                'content' => '<h3 style="color:#fd4c73;"><i class="fa fa-heart fa-fw"></i> 感谢您使用Zibll子比主题</h3>
                <div><b>首次使用请在下方输入授权码进行授权验证</b></div>
                <p>子比主题是一款良心、厚道的好产品！创作不易，支持正版，从我做起！</p>
                <div style="margin:10px 14px;"><li>子比主题官网：<a target="_bank" href="https://www.zibll.com/">https://zibll.com</a></li>
                <li>作者联系方式：<a href="http://wpa.qq.com/msgrd?v=3&amp;uin=770349780&amp;site=qq&amp;menu=yes">QQ 770349780</a></li>
                <li>如在本地调试则无需授权</li></div>',
            ),
            CFS_Module::aut(),
        ),
    ));
    // $update_icon =  '';
    // if (ZibAut::is_update()) {
    //     $update_icon =  ' c-red';
    // }
    CSF::createSection('zibll_options', array(
        'title'       => '文档&更新',
        'icon'        => 'fa fa-fw fa-cloud-download' . $update_icon,
        'description' => '',
        'fields'      => CFS_Module::update()
    ));
    CSF::createSection($prefix, array(
        'title'       => '备份&导入',
        'icon'        => 'fa fa-fw fa-copy',
        'fields'      => array(
            array(
                'type'    => 'submessage',
                'style'   => 'warning',
                'content' => '<h3 style="color:#fd4c73;"><i class="csf-tab-icon fa fa-fw fa-copy"></i> Zibll主题配置备份及导入</h3>
                <div style="margin:10px 14px;"><li>子比主题从V5.0开始，启用了全新的配置框架，首次升级V5.0系统会自动转换配置数据并导入</li>
                <li>如果您的配置数据意外丢失，或者首次升级V5.0后，主题配置未自动转换导入，您可以点击下方按钮手动导入V5.0之前的数据</li>
                <li>首次升级V5.0务必查看<a target="_bank" href="https://www.zibll.com/1202.html">V5.0升级指南</a></li>
                <li>导入数据不可恢复，导入前请先备份现有数据！</li></div>
                <ajaxform class="ajax-form" ajax-url="' . esc_url(admin_url('admin-ajax.php')) . '">
                <input type="hidden" ajax-name="action" value="option_to_csf">
                <a href="javascript:;" class="but jb-yellow ajax-submit">导入V5.0之前的配置数据</a>
                <a href="' . esc_url(add_query_arg(['action' => 'export_old_options', 'nonce' => wp_create_nonce('export_nonce')], admin_url('admin-ajax.php'))) . '" class="but c-yellow">下载V5.0之前的配置数据</a>
                <div class="ajax-notice" style="margin-top: 10px;"></div>
                </ajaxform>',
            ),
            array(
                'type' => 'backup',
            ),
        )
    ));
}
zib_csf_admin_options();

function terms_options()
{
    $options = array();
    $query         = new WP_Term_Query(array(
        'taxonomy'   => array('topics', 'category'),
        'orderby' => 'taxonomy',
        'hide_empty' => false,
    ));
    $taxonomy_name = array(
        'topics' => '[专题] ',
        'post_tag' => '[标签] ',
        'category' => '[分类] '
    );
    if (!is_wp_error($query) && !empty($query->terms)) {
        foreach ($query->terms as $item) {
            $name = preg_replace('/^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,0}((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,8}).*/s', '\1', $item->name);
            if (mb_strlen($item->name, 'utf8') > mb_strlen($name, 'utf8')) {
                $name = $name . '...';
            }
            $options[$item->term_id] = $taxonomy_name[$item->taxonomy] . $name;
        }
    }
    return $options;
}
