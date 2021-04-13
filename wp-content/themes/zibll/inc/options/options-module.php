<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-11-11 11:41:45
 * @LastEditTime: 2021-01-08 22:08:00
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */


class CFS_Module
{
    public static function add_slider()
    {
        $f_imgpath =  get_template_directory_uri() . '/inc/csf-framework/assets/images/';
        $args = array();
        $args[] = array(
            'title' => __('背景图片', 'zib_language'),
            'id' => 'background',
            'default' => '',
            'preview' => true, 'library' => 'image',
            'type' => 'upload'
        );
        $args[] = array(
            'dependency' => array('background', '!=', ''),
            'id'    => 'link',
            'type'  => 'link',
            'title' => '幻灯片链接',
            'default' => array(),
            'add_title'    => '添加链接',
            'edit_title'   => '编辑链接',
            'remove_title' => '删除链接',
        );
        $args[] = array(
            'dependency' => array('background', '!=', '', '', 'visible'),
            'id'     => 'image_layer',
            'type'   => 'group',
            'accordion_title_number'   => true,
            'accordion_title_auto'   => false,
            'accordion_title_prefix' => '图层',
            'button_title' => '添加图层',
            'title'  => '叠加图层',
            'subtitle' => '添加更多图层',
            'desc' => '添加额外的幻灯片图层，配合图层设置及幻灯片其它设置，可轻松制作出漂亮无比的幻灯片',
            'fields'   => array(
                array(
                    'title' => __('图层图片', 'zib_language'),
                    'id' => 'image',
                    'default' => '',
                    'preview' => true,
                    'library' => 'image',
                    'type' => 'upload'
                ),
                array(
                    'title' => '自由尺寸',
                    'type' => 'switcher',
                    'id' => 'free_size',
                    'class' => 'compact',
                    'desc' => '如果图层的尺寸和背景图的尺寸不一致，可开启此项以自定义图层对齐方向',
                    'default' => false,
                    'type' => 'switcher'
                ),
                array(
                    'dependency' => array('image|free_size', '!=|!=', '|'),
                    'title' => '图层对齐',
                    'id' => 'align',
                    'inline' => true,
                    'type' => 'radio',
                    'class' => 'compact',
                    'default' => 'center',
                    'options' => array(
                        'left' => '靠左显示',
                        'center' => '居中显示',
                        'right' => '靠右显示',
                    ),
                ), array(
                    'dependency' => array('image', '!=', ''),
                    'id' => 'parallax',
                    'class' => 'compact',
                    'desc' => '提前或延后进入视线，负值为延后，正值为提前，0为关闭[-200~200]',
                    'title' => '视差滚动',
                    'default' => 0,
                    'max' => 200,
                    'min' => -200,
                    'step' => 5,
                    'unit' => '%',
                    'type' => 'spinner'
                ), array(
                    'dependency' => array('image', '!=', ''),
                    'id' => 'parallax_scale',
                    'desc' => '放大或缩小进入视线，原图大小的百分之多少[1~200]',
                    'class' => 'compact',
                    'title' => '视差缩放',
                    'default' => 100,
                    'max' => 200,
                    'min' => 1,
                    'step' => 5,
                    'unit' => '%',
                    'type' => 'spinner'
                ), array(
                    'dependency' => array('image|parallax', '!=|!=', '|'),
                    'id' => 'parallax_opacity',
                    'desc' => '以百分之多少的透明度进入视线[1~100]<br>视差功能对浏览器性能有一定影响，如果图层较多，不建议全部开启',
                    'class' => 'compact',
                    'title' => '视差透明',
                    'default' => 100,
                    'max' => 100,
                    'min' => 1,
                    'step' => 5,
                    'unit' => '%',
                    'type' => 'spinner'
                )
            )
        );
        $args[] = array(
            'dependency' => array('background', '!=', ''),
            'id'            => 'text',
            'type'          => 'accordion',
            'title'         => '叠加文案',
            'accordions'    => array(
                array(
                    'title'     => '幻灯片叠加文案',
                    'fields'    => array(
                        array(
                            'title' => '幻灯片文案',
                            'subtitle' => '幻灯片标题',
                            'id' => 'title',
                            'default' => '',
                            'attributes' => array(
                                'rows' => 1
                            ),
                            'type' => 'textarea'
                        ),
                        array(
                            'dependency' => array('title', '!=', ''),
                            'title' => '幻灯片简介',
                            'id' => 'desc',
                            'class' => 'compact',
                            'default' => '',
                            'desc' => '标题、简介均支持HTML代码，请注意代码规范及标签闭合',
                            'attributes' => array(
                                'rows' => 1
                            ),
                            'type' => 'textarea'
                        ),
                        array(
                            'dependency' => array('title', '!=', ''),
                            'title' => '显示位置',
                            'id' => 'text_align',
                            'type' => 'image_select',
                            'class' => 'compact image-miniselect',
                            'default' => 'left-bottom',
                            'desc' => '前景图显示位置及文案位置需合理搭配',
                            'options' => array(
                                'left-bottom' =>  $f_imgpath . 'left-bottom.jpg',
                                'left-conter' => $f_imgpath . 'left-conter.jpg',
                                'conter-conter' =>  $f_imgpath . 'conter-conter.jpg',
                                'conter-bottom' =>  $f_imgpath . 'conter-bottom.jpg',
                                'right-conter' =>  $f_imgpath . 'right-conter.jpg',
                                'right-bottom' =>  $f_imgpath . 'right-bottom.jpg',
                            ),
                        ),
                        array(
                            'dependency' => array('title', '!=', ''),
                            'id' => 'text_size_pc',
                            'class' => 'compact',
                            'title' => 'PC端字体大小',
                            'default' => 30,
                            'max' => 50,
                            'min' => 12,
                            'step' => 1,
                            'unit' => 'PX',
                            'type' => 'spinner'
                        ),
                        array(
                            'dependency' => array('title', '!=', ''),
                            'id' => 'text_size_m',
                            'class' => 'compact',
                            'title' => '移动端字体大小',
                            'desc' => '在此设置标题的字体大小，简介的大小为标题大小的60%，最小12px</br>字体越大，文案周边的间距也越大！字体大小请根据内容合理调整，避免在某些设备显示不全',
                            'default' => 20,
                            'max' => 50,
                            'min' => 12,
                            'step' => 1,
                            'unit' => 'PX',
                            'type' => 'spinner'
                        ),
                        array(
                            'dependency' => array('title', '!=', ''),
                            'id' => 'parallax',
                            'class' => 'compact',
                            'desc' => '视差滚动功能为较背景滚动的时间差，为负数则滚动慢一拍，为正数则滚动快一拍，为0则关闭',
                            'title' => '文案视差滚动',
                            'default' => 40,
                            'max' => 200,
                            'min' => -200,
                            'step' => 10,
                            'unit' => '%',
                            'type' => 'spinner'
                        )
                    )
                )
            )
        );

        return $args;
    }
    public static function page_type()
    {
        return array(
            'home' => '首页',
            'topics' => '专题页',
            'category' => '分类页',
            'tag' => '标签页',
            'author' => '用户页',
            'single' => '文章页',
            'search' => '搜索页',
            'page' => '其它页面',
        );
    }
    public static function zib_palette()
    {
        return array(
            'c-red' => array('rgba(255, 84, 115, .4)'),
            'c-red2' => array('rgba(194, 41, 46, 0.4)'),
            'c-yellow' => array('rgba(255, 111, 6, 0.4)'),
            'c-yellow2' => array('rgba(179, 103, 8, 0.4)'),
            'c-blue' => array('rgb(41, 151, 247, .4)'),
            'c-blue2' => array('rgb(77, 130, 249, .4)'),
            'c-green' => array('rgba(18, 185, 40, .4)'),
            'c-green2' => array('rgba(72, 135, 24, .4)'),
            'c-purple' => array('rgba(213, 72, 245, 0.4)'),
            'c-purple2' => array('rgba(154, 72, 245, 0.4)'),
            'b-red' => array('#f74b3d'),
            'b-yellow' => array('#f3920a'),
            'b-blue' => array('#0a8cf3'),
            'b-green' => array('#1fd05a'),
            'b-purple' => array('#c133f5'),
            'jb-red' => array('linear-gradient(135deg, #ffbeb4 10%, #f61a1a 100%)'),
            'jb-yellow' => array('linear-gradient(135deg, #ffd6b2 10%, #ff651c 100%)'),
            'jb-blue' => array('linear-gradient(135deg, #b6e6ff 10%, #198aff 100%)'),
            'jb-green' => array('linear-gradient(135deg, #ccffcd 10%, #52bb51 100%)'),
            'jb-purple' => array('linear-gradient(135deg, #fec2ff 10%, #d000de 100%)'),
            'jb-vip1' => array('linear-gradient(25deg, #eab869 10%, #fbecd4 60%, #ffe0ae 100%)'),
            'jb-vip2' => array('linear-gradient(317deg, #4d4c4c 30%, #878787 70%, #5f5c5c 100%)'),
        );
    }

    public static function email_test()
    {
        $con = '<div class="options-notice">
        <div class="explain">
        <p><b>您可以在下方测试邮件发送功能是否正常，请输入您的邮箱帐号：</b></p>
        <ajaxform class="ajax-form" ajax-url="' . admin_url("admin-ajax.php") . '">
        <p><input type="text" style="max-width:300px;" ajax-name="email" value="' . get_option('admin_email') . '" placeholder="88888888@qq.com"></p>
        <div class="ajax-notice"></div>
        <p><a href="javascript:;" class="but jb-yellow ajax-submit"><i class="fa fa-paper-plane-o"></i> 发送测试邮件</a></p>
        <input type="hidden" ajax-name="action" value="test_send_mail">
        </ajaxform>
        </div></div>';
        return array(
            'type'    => 'submessage',
            'style'   => 'warning',
            'content' => $con,
        );
    }
    public static function vip_product()
    {
        return array(
            array(
                'id' => 'price',
                'title' => '执行价',
                'default' => '699',
                'type' => 'number',
                'unit' => '元',
            ),
            array(
                'id' => 'show_price',
                'title' => '原价',
                'desc' => '显示在执行价格前面，并划掉',
                'default' => '999',
                'type' => 'number',
                'unit' => '元',
                'class' => 'compact'
            ),
            array(
                'id' => 'tag',
                'title' => '促销标签',
                'class' => 'compact',
                'desc' => '支持HTML，请注意控制长度',
                'attributes' => array(
                    'rows' => 1
                ),
                'type' => 'textarea'
            ),
            array(
                'dependency' => array('tag', '!=', ''),
                'title' => '标签颜色',
                'id' => "tag_class",
                'class' => 'compact skin-color',
                'default' => "jb-yellow",
                'type' => "palette",
                'options' => CFS_Module::zib_palette()
            ),
            array(
                'dependency' => array('time', '==', 0),
                'type'    => 'submessage',
                'style'   => 'success',
                'content' => '<strong>会员有效时间已设置为：<code>永久会员</code></strong>',
            ),
            array(
                'title' => '会员有效时间',
                'id' => 'time',
                'class' => 'compact',
                'desc' => '开通会员的时长。填<code>0</code>则为永久会员',
                'default' => 3,
                'max' => 36,
                'min' => 0,
                'step' => 1,
                'unit' => '个月',
                'type' => 'spinner'
            ),

        );
    }
    public static function rebate_type()
    {
        return
            array(
                'all' => '全部订单',
                '1' => '付费阅读',
                '2' => '付费资源',
                '4' => '购买会员',
            );
    }
    public static function slide($hide = array())
    {
        $args = array();
        return array(
            array(
                'id' => 'direction',
                'default' => 'horizontal',
                'title' => '幻灯片方向',
                'inline' => true,
                'type' => 'radio',
                'options' => array(
                    'horizontal' => '左右切换',
                    'vertical' => '上下切换',
                )
            ),
            array(
                'title' => '循环切换',
                'class' => 'compact',
                'id' => 'loop',
                'default' => true,
                'type' => 'switcher'
            ),
            array(
                'title' => '显示翻页按钮',
                'class' => 'compact',
                'id' => 'button',
                'default' => false,
                'type' => 'switcher'
            ),
            array(
                'title' => '显示指示器',
                'type' => 'switcher',
                'id' => 'pagination',
                'class' => 'compact',
                'default' => false,
                'type' => 'switcher'
            ),

            array(
                'id' => 'effect',
                'default' => 'slide',
                'class' => 'compact',
                'title' => '切换动画',
                'type' => "select",
                'options' => array(
                    'slide' => __('滑动', 'zib_language'),
                    'fade' => __('淡出淡入', 'zib_language'),
                    'cube' => __('3D方块', 'zib_language'),
                    'coverflow' => __('3D滑入', 'zib_language'),
                    'flip' => __('3D翻转', 'zib_language'),
                )
            ),
            array(
                'dependency' => array('direction', '!=', 'vertical'),
                'title' => '自动高度',
                'type' => 'switcher',
                'id' => 'auto_height',
                'class' => 'compact',
                'default' => false,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('auto_height|direction', '!=|!=', '|vertical'),
                'type'    => 'submessage',
                'style'   => 'warning',
                'content' => '<i class="fa fa-info-circle fa-fw"></i> 开启自动高度后，会根据幻灯片背景图自动调节每张幻灯片高度</br>请注意幻灯片图片的长宽比例不能差距太大，否则会显示不佳！</br>开启自动高度后，PC端高度和移动端高端将失效，可在下方设置最大高度和最小高度，避免幻灯片过大过小',
            ),

            array(
                'dependency' => array('auto_height|direction', '!=|!=', '|vertical'),
                'id' => 'max_height',
                'class' => 'compact',
                'title' => '最大高度',
                'default' => 500,
                'max' => 800,
                'min' => 120,
                'step' => 20,
                'unit' => 'PX',
                'type' => 'spinner'
            ),

            array(
                'dependency' => array('auto_height|direction', '!=|!=', '|vertical'),
                'id' => 'min_height',
                'title' => '最小高度',
                'class' => 'compact',
                'default' => 180,
                'max' => 500,
                'min' => 100,
                'step' => 20,
                'unit' => 'PX',
                'type' => 'spinner'
            ),
            array(
                'id' => 'pc_height',
                'class' => 'compact',
                'title' => '电脑端高度',
                'default' => 400,
                'max' => 800,
                'min' => 120,
                'step' => 20,
                'unit' => 'PX',
                'type' => 'spinner'
            ),
            array(
                'id' => 'm_height',
                'title' => '移动端高度',
                'class' => 'compact',
                'default' => 200,
                'max' => 500,
                'min' => 100,
                'step' => 20,
                'unit' => 'PX',
                'type' => 'spinner'
            ),
            array(
                'id' => 'spacebetween',
                'title' => '幻灯片间距',
                'class' => 'compact',
                'default' => 15,
                'max' => 500,
                'min' => 0,
                'step' => 5,
                'unit' => 'PX',
                'type' => 'spinner'
            ),
            array(
                'id' => 'speed',
                'title' => '切换速度',
                'subtitle' => '切换过程的时间(越小越快)',
                'class' => 'compact',
                'default' => 800,
                'max' => 3000,
                'min' => 200,
                'step' => 100,
                'unit' => '毫秒',
                'type' => 'slider'
            ),
            array(
                'title' => '自动播放',
                'type' => 'switcher',
                'id' => 'autoplay',
                'class' => 'compact',
                'default' => true,
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('autoplay', '!=', ''),
                'id' => 'interval',
                'title' => '播放速度',
                'subtitle' => '自动切换的时间间隔(越小越快)',
                'class' => 'compact',
                'default' => 4,
                'max' => 20,
                'min' => 0,
                'step' => 1,
                'unit' => '秒',
                'type' => 'slider'
            )
        );
    }
    public static function orderby()
    {
        return array(
            array(
                'id' => 'lists',
                'title'  => '显示排序方式',
                'options' => array(
                    'modified' => '更新',
                    'date' => '发布',
                    'views' => '浏览',
                    'like' => '点赞',
                    'comment_count' => '评论',
                    'favorite' => '收藏',
                    'rand' => '随机',
                ),
                'type' => 'select',
                'placeholder' => '选择需要的排序方式按钮',
                'default'     => array('modified', 'views', 'like', 'comment_count'),
                'chosen' => true,
                'multiple' => true,
                'sortable' => true,
            ),
            array(
                'title' => __('更多排序方式', 'zib_language'),
                'id' => 'dropdown',
                'class' => 'compact',
                'default' => false,
                'label' => '用下拉框显示全部排序方式',
                'type' => 'switcher'
            )
        );
    }
    public static function ajax_but($type = '')
    {
        $query_args = array();
        if ($type == 'topics') {
            $type = 'tag';
            $query_args = array('taxonomy' => 'topics');
        }
        return array(
            array(
                'id' => 'lists',
                'title'  => '按钮列表',
                'options' => $type,
                'query_args' => $query_args,
                'type' => 'select',
                'placeholder' => '选择需要显示的按钮',
                'chosen' => true,
                'multiple' => true,
                'sortable' => true,
            ),
            array(
                'title' => __('下拉列表', 'zib_language'),
                'id' => 'dropdown',
                'class' => 'compact',
                'default' => false,
                'label' => '用下拉框显示更多内容',
                'type' => 'switcher'
            ),
            array(
                'dependency' => array('dropdown', '!=', ''),
                'id' => 'dropdown_lists',
                'class' => 'compact',
                'title'  => '下拉菜单列表',
                'options' => $type,
                'query_args' => $query_args,
                'type' => 'select',
                'placeholder' => '选择需要显示的按钮',
                'chosen' => true,
                'multiple' => true,
                'sortable' => true,
            ),
        );
    }
    public static function vip_tab($level = 1)
    {
        return array(
            array(
                'dependency' => array('pay_user_vip_' . $level . '_s', '!=', '', 'all', 'visible'),
                'id' => 'pay_user_vip_' . $level . '_equity',
                'title' => '会员权益简介',
                'subtitle' => _pz('pay_user_vip_' . $level . '_name') . '简介',
                'default' => '<li>全站资源折扣购买</li>
<li>部分内容免费阅读</li>
<li>一对一技术指导</li>
<li>VIP用户专属QQ群</li>',
                'help' => '使用自定义HTML代码，每行用li标签包围',
                'attributes' => array(
                    'rows' => 4
                ),
                'sanitize' => false,
                'type' => 'textarea'
            ),
            array(
                'dependency' => array('pay_user_vip_' . $level . '_s', '!=', '', 'all', 'visible'),
                'id'     => 'vip_' . $level . '_product',
                'title'  => '会员商品',
                'subtitle' => _pz('pay_user_vip_' . $level . '_name') . '的商品选项',
                'type'   => 'group',
                'accordion_title_prefix' => '价格：￥',
                'max'   => 8,
                'button_title' => '添加会员商品',
                'class' => 'compact',
                'default'   => array(
                    array(
                        'price' => '99',
                        'show_price' => '199',
                        'tag' => '<i class="fa fa-fw fa-bolt"></i> 限时特惠',
                        'time' => 3,
                    ),
                    array(
                        'price' => '199',
                        'show_price' => '299',
                        'tag' => '<i class="fa fa-fw fa-bolt"></i> 站长推荐',
                        'time' => 6,
                    ),
                ),
                'fields' => CFS_Module::vip_product()
            ),

        );
    }

    public static function aut()
    {
        // if (ZibAut::is_aut()) {
            $con = '<div id="authorization_form" class="ajax-form" ajax-url="' . esc_url(admin_url('admin-ajax.php')) . '">
            <div class="ok-icon"><svg t="1585712312243" class="icon" style="width: 1em; height: 1em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="3845" data-spm-anchor-id="a313x.7781069.0.i0"><path d="M115.456 0h793.6a51.2 51.2 0 0 1 51.2 51.2v294.4a102.4 102.4 0 0 1-102.4 102.4h-691.2a102.4 102.4 0 0 1-102.4-102.4V51.2a51.2 51.2 0 0 1 51.2-51.2z m0 0" fill="#FF6B5A" p-id="3846"></path><path d="M256 13.056h95.744v402.432H256zM671.488 13.056h95.744v402.432h-95.744z" fill="#FFFFFF" p-id="3847"></path><path d="M89.856 586.752L512 1022.72l421.632-435.2z m0 0" fill="#6DC1E2" p-id="3848"></path><path d="M89.856 586.752l235.52-253.952h372.736l235.52 253.952z m0 0" fill="#ADD9EA" p-id="3849"></path><path d="M301.824 586.752L443.136 332.8h137.216l141.312 253.952z m0 0" fill="#E1F9FF" p-id="3850"></path><path d="M301.824 586.752l209.92 435.2 209.92-435.2z m0 0" fill="#9AE6F7" p-id="3851"></path></svg></div>
            <p style=" color: #0087e8; font-size: 15px; "><svg class="icon" style="width: 1em;height: 1em;vertical-align: -.2em;fill: currentColor;overflow: hidden;font-size: 1.4em;" viewBox="0 0 1024 1024"><path d="M492.224 6.72c11.2-8.96 26.88-8.96 38.016 0l66.432 53.376c64 51.392 152.704 80.768 243.776 80.768 27.52 0 55.104-2.624 81.92-7.872a30.08 30.08 0 0 1 24.96 6.4 30.528 30.528 0 0 1 11.008 23.424V609.28c0 131.84-87.36 253.696-228.288 317.824L523.52 1021.248a30.08 30.08 0 0 1-24.96 0l-206.464-94.08C151.36 862.976 64 741.12 64 609.28V162.944a30.464 30.464 0 0 1 36.16-29.888 425.6 425.6 0 0 0 81.92 7.936c91.008 0 179.84-29.504 243.712-80.768z m19.008 62.528l-47.552 38.208c-75.52 60.8-175.616 94.144-281.6 94.144-19.2 0-38.464-1.024-57.472-3.328V609.28c0 107.84 73.92 208.512 192.768 262.72l193.856 88.384 193.92-88.384c118.912-54.208 192.64-154.88 192.64-262.72V198.272a507.072 507.072 0 0 1-57.344 3.328c-106.176 0-206.144-33.408-281.728-94.08l-47.488-38.272z m132.928 242.944c31.424 0 56.832 25.536 56.832 56.832H564.544v90.944h121.92a56.448 56.448 0 0 1-56.384 56.384H564.48v103.424h150.272a56.832 56.832 0 0 1-56.832 56.832H365.056a56.832 56.832 0 0 1-56.832-56.832h60.608v-144c0-33.92 27.52-61.44 61.44-61.44v205.312h71.68V369.024H324.8c0-31.424 25.472-56.832 56.832-56.832z" p-id="4799"></path></svg> 恭喜您! 已完成授权</p>    
            <input type="hidden" ajax-name="action" value="admin_delete_aut">
            <a id="authorization_submit" class="but c-red ajax-submit">撤销授权</a>
            <div class="ajax-notice"></div>
            </div>';
        // } else {
        //     $con = '<div id="authorization_form" class="ajax-form" ajax-url="' . esc_url(admin_url('admin-ajax.php')) . '">
        //     <div class="ok-icon"><svg class="icon" style="font-size: 1.2em;width: 1em; height: 1em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024"><path d="M880 502.3V317.1c0-34.9-24.4-66-60.8-77.4l-80.4-30c-37.8-14.1-73.4-32.9-105.7-55.7l-84.6-60c-19.2-15.2-47.8-15.2-67 0l-84.7 59.9c-32.3 22.8-67.8 41.6-105.7 55.7l-80.4 30c-36.4 11.4-60.8 42.5-60.8 77.4v185.2c0 123.2 63.9 239.2 172.5 313.2l158.5 108c20.2 13.7 47.9 13.7 68.1 0l158.5-108C816.1 741.6 880 625.5 880 502.3z" fill="#0DCEA7" p-id="17337"></path><path d="M150 317.1v3.8c13.4-27.6 30-53.3 49.3-76.7C169.4 258 150 286 150 317.1zM880 317.1c0-34.9-24.4-66-60.8-77.4l-43.5-16.2c57.7 60.6 95.8 140 104.2 228.1l0.1-134.5zM572.8 111.2L548.5 94c-19.2-15.2-47.8-15.2-67 0l-15.3 10.8c10-0.8 20.2-1.2 30.5-1.2 26 0.1 51.5 2.7 76.1 7.6zM496.7 873.9c-39.5 0-77.6-5.9-113.4-17l97.7 66.6c20.2 13.7 47.9 13.7 68.1 0l158.5-108c92.3-62.9 152.3-156.1 168.2-258.3C843.5 737.3 686 873.9 496.7 873.9z" fill="#0DCEA7" p-id="17338"></path><path d="M875.8 557.2c2.8-18.1 4.3-36.4 4.3-54.9v-50.8c-8.5-88.1-46.6-167.4-104.2-228.1L739 209.6c-37.8-14.1-73.4-32.9-105.7-55.7l-60.5-42.7c-24.6-4.9-50-7.5-76.1-7.5-10.3 0-20.4 0.4-30.5 1.2l-58.7 41.5c23.4-5.2 47.7-8 72.7-8 183.6 0 332.4 148.8 332.4 332.4S663.9 803 480.3 803c-170.8 0-311.5-128.9-330.2-294.7 2 121 65.6 234.5 172.4 307.2l60.8 41.4c35.9 11 74 17 113.4 17 189.3 0 346.8-136.6 379.1-316.7zM261.2 220.8l-50.4 18.8c-4 1.3-7.8 2.8-11.5 4.5-19.3 23.4-35.9 49.2-49.3 76.7v112.7c9.4-84.5 50.5-159.4 111.2-212.7z" fill="#1DD49C" p-id="17339"></path><path d="M480.3 803c183.6 0 332.4-148.8 332.4-332.4S663.9 138.3 480.3 138.3c-25 0-49.3 2.8-72.7 8l-10.7 7.6c-32.3 22.8-67.8 41.6-105.7 55.7l-30 11.2C200.5 274.1 159.4 349 150 433.6v68.8c0 2 0 4 0.1 6C168.8 674.1 309.5 803 480.3 803z m-16.4-630c154.4 0 279.6 125.2 279.6 279.6S618.3 732.2 463.9 732.2 184.3 607 184.3 452.6 309.5 173 463.9 173z" fill="#2DDB92" p-id="17340"></path><path d="M463.9 732.2c154.4 0 279.6-125.2 279.6-279.6S618.3 173 463.9 173 184.3 298.2 184.3 452.6s125.2 279.6 279.6 279.6z m-16.4-524.5c125.3 0 226.8 101.5 226.8 226.8S572.8 661.3 447.5 661.3 220.7 559.8 220.7 434.5s101.6-226.8 226.8-226.8z" fill="#3DE188" p-id="17341" data-spm-anchor-id="a313x.7781069.0.i7"></path><path d="M447.5 661.3c125.3 0 226.8-101.5 226.8-226.8S572.8 207.7 447.5 207.7 220.7 309.2 220.7 434.5s101.6 226.8 226.8 226.8z m-16.4-419c96.1 0 174 77.9 174 174s-77.9 174-174 174-174-77.9-174-174 77.9-174 174-174z" fill="#4CE77D" p-id="17342"></path><path d="M431.1 590.4c96.1 0 174-77.9 174-174s-77.9-174-174-174-174 77.9-174 174 77.9 174 174 174zM414.7 277c67 0 121.3 54.3 121.3 121.3s-54.3 121.3-121.3 121.3-121.3-54.3-121.3-121.3S347.8 277 414.7 277z" fill="#5CEE73" p-id="17343"></path><path d="M414.7 398.3m-121.3 0a121.3 121.3 0 1 0 242.6 0 121.3 121.3 0 1 0-242.6 0Z" fill="#6CF468" p-id="17344"></path><path d="M515 100.7c8.3 0 16.2 2.7 22.3 7.5l0.4 0.3 0.4 0.3 84.7 59.9c33.5 23.7 70.5 43.2 109.8 57.9l80.4 30 0.4 0.2 0.5 0.1c28.8 9.1 48.2 33.3 48.2 60.3v185.2c0 28.9-3.7 57.8-11.1 86-7.3 27.8-18.1 54.8-32.2 80.4-14.1 25.6-31.5 49.8-51.7 71.8-20.5 22.4-43.9 42.6-69.6 60.1L539 908.6c-6.8 4.6-15.3 7.2-23.9 7.2s-17.1-2.6-23.9-7.2l-158.5-108c-25.7-17.5-49.1-37.7-69.6-60.1-20.2-22-37.6-46.2-51.7-71.8-14.1-25.6-24.9-52.6-32.2-80.4-7.4-28.1-11.1-57-11.1-86V317.1c0-27 19.4-51.2 48.2-60.3l0.5-0.1 0.4-0.2 80.4-30c39.3-14.7 76.2-34.1 109.8-57.9l84.7-59.9 0.4-0.3 0.4-0.3c5.9-4.8 13.9-7.4 22.1-7.4m0-18c-11.9 0-23.9 3.8-33.5 11.4L396.8 154c-32.3 22.8-67.8 41.6-105.7 55.7l-80.4 30c-36.4 11.4-60.8 42.5-60.8 77.4v185.2c0 123.2 63.9 239.2 172.5 313.2l158.5 108c10.1 6.9 22.1 10.3 34 10.3 12 0 24-3.4 34-10.3l158.5-108c108.6-74 172.5-190 172.5-313.2V317.1c0-34.9-24.4-66-60.8-77.4l-80.4-30c-37.8-14.1-73.4-32.9-105.7-55.7l-84.5-60c-9.6-7.5-21.5-11.3-33.5-11.3z" fill="#0EC69A" p-id="17345"></path><path d="M688.8 496.7V406c0-17.1-11.6-32.3-28.9-37.9l-38.3-14.7c-18-6.9-35-16.1-50.3-27.3L531 296.8c-9.1-7.4-22.8-7.4-31.9 0l-40.3 29.3a218.45 218.45 0 0 1-50.3 27.3l-38.3 14.7c-17.3 5.6-28.9 20.8-28.9 37.9v90.7c0 60.3 30.4 117.1 82.1 153.3l75.5 52.9c9.6 6.7 22.8 6.7 32.4 0l75.5-52.9c51.6-36.2 82-93 82-153.3z" fill="#9CFFBD" p-id="17346"></path><path d="M325.6 287.5c-7.2 0-14.1-4.4-16.8-11.6-3.5-9.3 1.1-19.7 10.4-23.2 68.5-26.2 110.5-60.3 110.9-60.6 7.7-6.3 19-5.2 25.3 2.5s5.2 19-2.5 25.3c-1.9 1.5-47 38.2-120.9 66.4-2.1 0.8-4.2 1.2-6.4 1.2z" fill="#FFFFFF" p-id="17347"></path><path d="M260.2 311.7c-7.3 0-14.2-4.5-16.9-11.7-3.5-9.3 1.3-19.7 10.6-23.1l10.5-3.9c9.3-3.5 19.7 1.3 23.1 10.6 3.5 9.3-1.3 19.7-10.6 23.1l-10.5 3.9c-2.1 0.7-4.2 1.1-6.2 1.1z" fill="#FFFFFF" p-id="17348"></path></svg></div>
        //     <p style="color:#fd4c73;">激动人心的时候到了！即将开启优雅的建站之旅！</p>
        //     <div class="hide-box">
        //     <p>请输入购买主题时获取的授权码：</p>
        //     <input class="regular-text" type="text" ajax-name="cut_code" value="" placeholder="请输入授权码">
        //     <input type="hidden" ajax-name="action" value="admin_curl_aut">
        //     </div>
        //     <a id="authorization_submit" class="but c-blue ajax-submit curl-aut-submit" data-depend-id="zib_submit_aut">一键授权</a>
        //     <div class="ajax-notice"></div>
        //     </div>';
        // }
        // if (!ZibAut::is_local()) {
            return array(
                'type'    => 'content',
                'content' => $con,
            );
        // } else {
        //     return array(
        //         'type'    => 'notice',
        //         'style'   => 'info',
        //         'content' => '您当前正处于本地环境，暂时无需授权！请忽略顶部提示',
        //     );
        // }
    }
    public static function update()
    {
        $csf = array();
        // $data = ZibAut::is_update();
        $theme_data = wp_get_theme();
        $theme_version = $theme_data['Version'];

        if ($data) {
            $notice = '<div class="ajax-form" ajax-url="' . esc_url(admin_url('admin-ajax.php')) . '">
                        <p style="color:#ff2f86"><i class="csf-tab-icon fa fa-cloud-upload fa-2x"></i></p>
                        <p><b>当前主题版本：V' . $theme_version . '，可更新到最新版本：<code style="color:#ff1919;background: #fbeeee; font-size: 16px; ">V' . $data['version'] . '</code></b></p>'
                . ($data['update_description'] ? '<p>' . $data['update_description'] . '</p>' : '') . '
                        <div>
                            <input type="hidden" ajax-name="action" value="admin_skip_update">
                            <div class="progress"><div class="progress-bar"></div></div>
                            <p class="ajax-notice"></p>
                            <a href="javascript:;" class="but jb-blue mr10 online-update"><i class="fa fa-cloud-download fa-fw"></i> 在线更新</a><a href="javascript:;" class="but c-yellow ajax-submit"><i class="fa fa-ban fa-fw"></i> 忽略此次更新</a>
                        </div>
                        <div style="text-align: right;font-size: 12px;opacity: .5;"><a style="color: inherit;" target="_blank" href="https://www.zibll.com/1411.html">遇到问题？点此查看官网教程</a></div>
                    </div>';

            $log = '<div class="box-theme">';
            $log .= $data['update_content'];
            $log .= '</div>';
            $csf[] = array(
                'type'    => 'notice',
                'style'   => 'info',
                'content' => $notice
            );
            $csf[] = array(
                'title'       => '更新日志',
                'type'    => 'content',
                'content' => $log
            );
        } else {
            $notice = '<div class="ajax-form" ajax-url="' . esc_url(admin_url('admin-ajax.php')) . '">
            <h3 class="c-red"><i class="fa fa-thumbs-o-up fa-fw" aria-hidden="true"></i> 当前主题已经是最新版啦</h3>
            <p><b>当前主题版本：V' . wp_get_theme()['Version'] . ' </b></p>
            <p class="ajax-notice"></p>
            <p><a href="javascript:;" class="but jb-blue ajax-submit">检测更新</a></p>
            <input type="hidden" ajax-name="action" value="admin_detect_update">
            </div>';

            $docs = '<div style="margin-left:14px;"><li><a target="_blank" href="https://www.zibll.com/375.html">Zibll子比主题历史更新日志</a></li>';
            $docs .= '<li><a target="_blank" href="https://www.zibll.com/?s=短信">短信验证码功能相关教程</a></li>';
            $docs .= '<li><a target="_blank" href="https://www.zibll.com/1071.html">V5.0推广返佣、推荐奖励使用教程</a></li>';
            $docs .= '<li><a target="_blank" href="https://www.zibll.com/1246.html">V5.0新版幻灯片使用教程</a></li>';
            $docs .= '<li><a target="_blank" href="https://www.zibll.com/1244.html">消息系统-站内通知-用户私信功能详解</a></li>';
            $docs .= '<li><a target="_blank" href="https://www.zibll.com/1012.html">导航菜单添加自定义徽章及多种样式菜单教程</a></li>';
            $docs .= '<li><a target="_blank" href="https://www.zibll.com/1222.html">正确使用自定义代码示例及教程</a></li>';
            $docs .= '<li><a target="_blank" href="https://www.zibll.com/1001.html">主题接入Github登录图文教程</a></li>';
            $docs .= '<li><a target="_blank" href="https://www.zibll.com/979.html">主题接入QQ登录图文教程</a></li>';
            $docs .= '<li><a target="_blank" href="https://www.zibll.com/958.html">文章列表显示模式设置教程</a></li>';
            $docs .= '<li><a target="_blank" href="https://www.zibll.com/951.html">友情链接页面创建配置教程></a></li>';
            $docs .= '<li><a target="_blank" href="https://www.zibll.com/886.html">海报分享功能详细教程</a></li>';
            $docs .= '<li><a target="_blank" href="https://www.zibll.com/860.html">古腾堡编辑器-在文章中插入其他文章卡片教程</a></li>';
            $docs .= '<li><a target="_blank" href="https://www.zibll.com/853.html">古腾堡编辑器-隐藏内容模块使用教程></a></li>';
            $docs .= '<li><a target="_blank" href="https://www.zibll.com/767.html">主题VIP会员系统详细使用教程</a></li>';
            $docs .= '<li><a target="_blank" href="https://www.zibll.com/720.html">邮件SMTP发送邮件教程</a></li>';
            $docs .= '<li><a target="_blank" href="https://www.zibll.com/689.html">编辑器增强-古腾堡编辑器块入门详解</a></li>';
            $docs .= '<li><a target="_blank" href="https://www.zibll.com/683.html">强大的图片灯箱功能详解</a></li>';
            $docs .= '<li><a target="_blank" href="https://www.zibll.com/675.html">使用古腾堡块在文章中插入幻灯片教程</a></li>';
            $docs .= '<li><a target="_blank" href="https://www.zibll.com/580.html">主题付费阅读、付费资源功能详解</a></li>';
            $docs .= '<li><a target="_blank" href="https://www.zibll.com/529.html">主题导航菜单设置教程</a></li>';
            $docs .= '<li><a target="_blank" href="https://www.zibll.com/519.html">主题常用功能设置教程</a></li>';
            $docs .= '<li><a target="_blank" href="https://www.zibll.com/498.html">主题前端显示配置教程</a></li>';
            $docs .= '<li><a target="_blank" href="https://www.zibll.com/46.html">主题详细安装教程/更新教程/首次配置指南</a></li>';
            $docs .= '<li><a target="_blank" href="https://www.zibll.com/zibll_word">更多主题文档及教程</a></li></div>';

            $csf[] = array(
                'type'    => 'notice',
                'style'   => 'info',
                'content' => $notice
            );

            $db_update = get_option('zibll_new_version');
            if (!empty($db_update['update_content'])) {
                $csf[] = array(
                    'type'          => 'tabbed',
                    'id'            => 'theme_text',
                    'tabs'          => array(
                        array(
                            'title'     => '主题文档',
                            'icon'      => 'fa fa-file-text-o fa-fw',
                            'fields'    => array(
                                array(
                                    'title'       => '主题文档',
                                    'type'    => 'content',
                                    'style'   => 'success',
                                    'content' => $docs
                                )
                            ),
                        ),
                        array(
                            'title'     => '更新日志',
                            'icon'      => 'fa fa-cloud-upload fa-fw',
                            'fields'    => array(
                                array(
                                    'title'       => '更新日志',
                                    'type'    => 'content',
                                    'style'   => 'success',
                                    'content' => ($db_update['update_description'] ? '<p>' . $db_update['update_description'] . '</p>' : '') . $db_update['update_content'] . '<p><a class="but c-blue" target="_blank" href="https://www.zibll.com/375.html">查看更多更新日志</a></p>'
                                )
                            ),
                        ),
                    )
                );
            } else {
                $csf[] = array(
                    'title'       => '主题文档',
                    'type'    => 'content',
                    'style'   => 'success',
                    'content' => $docs
                );
            }
        }

        $csf[] = array(
            'title'       => '系统环境',
            'type'    => 'content',
            'content' => '<div style="margin-left:14px;"><li><strong>操作系统</strong>： ' . PHP_OS . ' </li>
            <li><strong>运行环境</strong>： ' . $_SERVER["SERVER_SOFTWARE"] . ' </li>
            <li><strong>PHP版本</strong>： ' . PHP_VERSION . ' </li>
            <li><strong>WordPress版本</strong>： ' . get_bloginfo('version') . '</li>
            <li><strong>系统信息</strong>： ' . php_uname() . ' </li>
            <li><strong>服务器时间</strong>： ' . current_time('mysql') . '</li></div>
            <a class="but c-yellow" href="' . admin_url('site-health.php?tab=debug') . '">查看更多系统信息</a>',
        );
        $csf[] = array(
            'title'       => '推荐环境',
            'type'    => 'content',
            'content' => '<div style="margin-left:14px;"><li><strong>WordPress</strong>：4.9+，推荐使用最新版</li>
            <li><strong>PHP</strong>：PHP5.6及以上，推荐使用7.0以上</li>
            <li><strong>服务器配置</strong>：无要求，最低配都行</li>
            <li><strong>操作系统</strong>：无要求，不推荐使用Windows系统</li></div>'
        );
        return $csf;
    }
}
