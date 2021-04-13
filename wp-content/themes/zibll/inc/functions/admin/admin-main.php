<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-09-29 13:18:36
 * @LastEditTime: 2020-12-22 23:34:56
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

//给文章分类添加封面图像
$dir = get_bloginfo('template_directory');
if (!defined('Z_PLUGIN_URL')) define('Z_PLUGIN_URL', untrailingslashit(plugins_url('', __FILE__)));

add_action('admin_head', 'z_init');
function z_init()
{
    $z_taxonomies = get_taxonomies();
    if (is_array($z_taxonomies)) {
        foreach ($z_taxonomies as $z_taxonomy) {
            if ($z_taxonomy == 'link_category') continue;
            add_action($z_taxonomy . '_add_form_fields', 'z_add_texonomy_field');
            add_action($z_taxonomy . '_edit_form_fields', 'z_edit_texonomy_field');
            add_filter('manage_edit-' . $z_taxonomy . '_columns', 'z_taxonomy_columns');
            add_filter('manage_' . $z_taxonomy . '_custom_column', 'z_taxonomy_column', 10, 3);
        }
    }
}

function z_add_style()
{
    echo '<style type="text/css" media="screen">
		th.column-thumb {width:60px;}
		.form-field img.taxonomy-image,.taxonomy-image{width:95%;max-width:500px;max-height:300px;}
		.inline-edit-row fieldset .thumb label span.title {display:inline-block;}
		.column-thumb span {display:inline-block;}
		.inline-edit-row fieldset .thumb img,.column-thumb img {width:55px;height:28px;}
	</style>';
}

// 添加分类时候的添加图像
function z_add_texonomy_field()
{
    if (get_bloginfo('version') >= 3.5)
        wp_enqueue_media();
    else {
        wp_enqueue_style('thickbox');
        wp_enqueue_script('thickbox');
    }
    echo '<div class="form-field">
		<label for="taxonomy_image">' . __('封面图像', 'zci') . '</label>
		<input type="text" name="taxonomy_image" id="taxonomy_image" value="" />
        <br/>
        <p>设置封面图，建议尺寸为1000x400,如果分类页未开启侧边栏，请选择更大的尺寸，需要在主题设置-分类、标签页：开启分类、标签封面显示功能</p>
		<button class="z_upload_image_button button">' . __('上传/添加图像', 'zci') . '</button>
	</div>' . z_script();
}

// 编辑分类时候的添加图像
// 待处理-换新cfs
define('Z_IMAGE_PLACEHOLDER', ZIB_STYLESHEET_DIRECTORY_URI . '/img/thumbnail-lg.svg');
function z_edit_texonomy_field($taxonomy)
{
    if (get_bloginfo('version') >= 3.5)
        wp_enqueue_media();
    else {
        wp_enqueue_style('thickbox');
        wp_enqueue_script('thickbox');
    }
    $image_text = zib_get_taxonomy_img_url($taxonomy->term_id, NULL);
    echo '<tr class="form-field">
		<th scope="row" valign="top"><label for="taxonomy_image">' . __('图像', 'zci') . '</label></th>
		<td><img class="taxonomy-image" src="' . $image_text . '"/><br/><input type="text" name="taxonomy_image" id="taxonomy_image" value="' . $image_text . '" /><br />
        <p>设置封面图，建议尺寸为1000x400,如果分类页未开启侧边栏，请选择更大的尺寸，需要在主题设置-分类、标签页：开启分类、标签封面显示功能</p>
        <button class="z_upload_image_button button">' . __('上传/添加图像', 'zci') . '</button>
		<button class="z_remove_image_button button">' . __('删除图像', 'zci') . '</button>
		</td>
	</tr>' . z_script();
}
// 上传按钮的js函数
function z_script()
{
    return '<script type="text/javascript">
	    jQuery(document).ready(function($) {
			var wordpress_ver = "' . get_bloginfo("version") . '", upload_button;
			$(".z_upload_image_button").click(function(event) {
				upload_button = $(this);
				var frame;
				if (wordpress_ver >= "3.5") {
					event.preventDefault();
					if (frame) {
						frame.open();
						return;
					}
					frame = wp.media();
					frame.on( "select", function() {
						// Grab the selected attachment.
						var attachment = frame.state().get("selection").first();
						frame.close();
						if (upload_button.parent().prev().children().hasClass("tax_list")) {
							upload_button.parent().prev().children().val(attachment.attributes.url);
							upload_button.parent().prev().prev().children().attr("src", attachment.attributes.url);
						}
						else
                            $("#taxonomy_image").val(attachment.attributes.url);
                            upload_button.parent().find(".taxonomy-image").attr("src", attachment.attributes.url);
					});
					frame.open();
				}
				else {
					tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");
					return false;
				}
			});
			
			$(".z_remove_image_button").click(function() {
				$("#taxonomy_image").val("");
                $(this).parent().siblings(".title").children("img").attr("src","' . Z_IMAGE_PLACEHOLDER . '");
                $(this).parent().find(".taxonomy-image").attr("src", "' . Z_IMAGE_PLACEHOLDER . '");

				$(".inline-edit-col :input[name=\'taxonomy_image\']").val("");
				return false;
			});
			
			if (wordpress_ver < "3.5") {
				window.send_to_editor = function(html) {
					imgurl = $("img",html).attr("src");
					if (upload_button.parent().prev().children().hasClass("tax_list")) {
						upload_button.parent().prev().children().val(imgurl);
						upload_button.parent().prev().prev().children().attr("src", imgurl);
					}
					else
						$("#taxonomy_image").val(imgurl);
					tb_remove();
				}
			}
			
			$(".editinline").live("click", function(){  
			    var tax_id = $(this).parents("tr").attr("id").substr(4);
			    var thumb = $("#tag-"+tax_id+" .thumb img").attr("src");
				if (thumb != "' . Z_IMAGE_PLACEHOLDER . '") {
					$(".inline-edit-col :input[name=\'taxonomy_image\']").val(thumb);
				} else {
					$(".inline-edit-col :input[name=\'taxonomy_image\']").val("");
				}
				$(".inline-edit-col .title img").attr("src",thumb);
			    return false;  
			});  
	    });
	</script>';
}

// 保存函数
add_action('edit_term', 'z_save_taxonomy_image');
add_action('create_term', 'z_save_taxonomy_image');
function z_save_taxonomy_image($term_id)
{
    if (isset($_POST['taxonomy_image'])) {
        update_option('_taxonomy_image_' . $term_id, $_POST['taxonomy_image']);
        wp_cache_delete($term_id, 'taxonomy_image_');
        wp_cache_delete($term_id, 'taxonomy_image_full');
        wp_cache_delete($term_id, 'taxonomy_image_thumbnail');
        wp_cache_delete($term_id, 'taxonomy_image_medium');
        wp_cache_delete($term_id, 'taxonomy_image_large');
    }
}

function z_quick_edit_custom_box($column_name, $screen, $name)
{
    if ($column_name == 'thumb')
        echo '<fieldset>
		<div class="thumb inline-edit-col">
			<label>
				<span class="title"><img src="" alt="Thumbnail"/></span>
				<span class="input-text-wrap"><input type="text" name="taxonomy_image" value="" class="tax_list" /></span>
                <span class="input-text-wrap">
                <p>设置封面图，建议尺寸为1000x400,如果分类页未开启侧边栏，请选择更大的尺寸，需要在主题设置-分类、标签页：开启分类、标签封面显示功能</p>
					<button class="z_upload_image_button button">' . __('上传/添加图像', 'zci') . '</button>
					<button class="z_remove_image_button button">' . __('删除图像', 'zci') . '</button>
				</span>
			</label>
		</div>
	</fieldset>';
}

function z_taxonomy_columns($columns)
{
    $new_columns = array();
    $new_columns['thumb'] = __('图像', 'zci');
    return array_merge($new_columns, $columns);
}

function z_taxonomy_column($columns, $column, $id)
{
    if ($column == 'thumb')
        $columns = '<span><img src="' . zib_get_taxonomy_img_url($id, NULL, Z_IMAGE_PLACEHOLDER) . '" alt="' . __('Thumbnail', 'zci') . '" class="wp-post-image" /></span>';
    return $columns;
}

// change 'insert into post' to 'use this image'
function z_change_insert_button_text($safe_text, $text)
{
    return str_replace("Insert into Post", "Use this image", $text);
}

// style the image in category list
add_action('admin_head', 'z_add_style');

if (strpos($_SERVER['SCRIPT_NAME'], 'edit-tags.php')) {
    add_action('quick_edit_custom_box', 'z_quick_edit_custom_box', 10, 3);
    add_filter("attribute_escape", "z_change_insert_button_text", 10, 2);
}


// 上传文件自动重命名
if (_pz('newfilename') && !function_exists('_new_filename')) :
    function _new_filename($filename)
    {
        $info = pathinfo($filename);
        $ext = empty($info['extension']) ? '' : '.' . $info['extension'];
        $name = basename($filename, $ext);
        return substr(md5($name), 0, 12) . $ext;
    }
    add_filter('sanitize_file_name', '_new_filename', 10);

endif;

// editor style
add_editor_style(get_locale_stylesheet_uri() . '/css/editor-style.min.css');

// 后台Ctrl+Enter提交评论回复
add_action('admin_footer', '_admin_comment_ctrlenter');
function _admin_comment_ctrlenter()
{
    echo '<script type="text/javascript">
        jQuery(document).ready(function($){
            $("textarea").keypress(function(e){
                if(e.ctrlKey&&e.which==13||e.which==10){
                    $("#replybtn").click();
                }
            });
        });
    </script>';
};


// 禁用WP Editor Google字体css
function zib_remove_gutenberg_styles($translation, $text, $context, $domain)
{
    if ($context != 'Google Font Name and Variants' || $text != 'Noto Serif:400,400i,700,700i') {
        return $translation;
    }
    return 'off';
}
add_filter('gettext_with_context', 'zib_remove_gutenberg_styles', 10, 4);
// 古腾堡编辑器扩展
function zibll_block()
{
    wp_register_script(
        'zibll_block',
        ZIB_STYLESHEET_DIRECTORY_URI . '/js/gutenberg-extend.js',
        array('wp-blocks', 'wp-element', 'wp-rich-text')
    );

    wp_register_style(
        'zibll_block',
        ZIB_STYLESHEET_DIRECTORY_URI . '/css/editor-style.min.css',
        array('wp-edit-blocks')
    );

    wp_register_style(
        'font_awesome',
        ZIB_STYLESHEET_DIRECTORY_URI . '/css/font-awesome.min.css',
        array('wp-edit-blocks')
    );

    register_block_type('zibll/block', array(
        'editor_script' => 'zibll_block',
        'editor_style'  => ['zibll_block', 'font_awesome'],
    ));
}

if (function_exists('register_block_type')) {
    add_action('init', 'zibll_block');
    add_filter('block_categories', function ($categories, $post) {
        return array_merge(
            $categories,
            array(
                array(
                    'slug' => 'zibll_block_cat',
                    'title' => __('Zibll主题模块', 'zibll-blocks'),
                ),
            )
        );
    }, 10, 2);
}


//熊掌号实时文章推送
$_on = '';
if ((_pz('xzh_post_on') || _pz('xzh_post_daily_push')) && _pz('xzh_post_token')) {
    $_on = true;
    add_action('admin_menu', 'zib_postmeta_xzh_create');
    add_action('save_post', 'zib_postmeta_xzh_save');
}
$postmeta_xzh = array(
    array(
        "title" => "链接提交到百度资源中心",
        "name" => "xzh_post_ison",
        "std" => $_on,
        "disabled" => $_on,
    )
);

function zib_postmeta_xzh()
{
    global $post, $postmeta_xzh;
    foreach ($postmeta_xzh as $meta_box) {
        $meta_box_value = get_post_meta($post->ID, $meta_box['name'], true);
        if ($meta_box_value == "")
            $meta_box_value = $meta_box['std'];
        echo '<span style="margin:15px 20px 15px 0; display:inline-block;"><label><input ' . ($meta_box_value ? 'checked' : '') . ' type="checkbox" value="1" name="' . $meta_box['name'] . '"> ' . (isset($meta_box['title']) ? $meta_box['title'] : '') . '</label></span>';
    }
    $tui = get_post_meta($post->ID, 'xzh_tui_back', true);
    $show_text = '';
    if (!empty($tui['normal_push'])) {
        $show_text .= '<strong>普通收录：成功</strong> ' . json_encode($tui['normal_result']) . '</br>';
    } elseif (isset($tui['normal_push']) && $tui['normal_push'] == false) {
        $show_text .= '<strong>普通收录：失败</strong> ' . json_encode($tui['normal_result']) . '</br>';
    }
    if (!empty($tui['daily_push'])) {
        $show_text .= '<strong>快速收录：成功</strong> ' . json_encode($tui['daily_result']) . '</br>';
    } elseif (isset($tui['daily_push']) && $tui['daily_push'] == false) {
        $show_text .= '<strong>快速收录：失败</strong> ' . json_encode($tui['daily_result']) . '</br>';
    }
    if (!empty($tui['update_time'])) {
        $show_text .= '<strong>更新时间：</strong>' . $tui['update_time'] . '</br>';
    }
    if (strstr(json_encode($tui), '成功') || strstr(json_encode($tui), '失败')) {
        $show_text .= json_encode($tui) . '</br>';
    }
    if ($show_text) {
        $show_text = '<div>提交结果:</div>' . $show_text;
    } else {
        $show_text = '</br>发布、更新文章并刷新页面后可查看提交结果';
    }
    echo $show_text;
    echo '<input type="hidden" name="post_newmetaboxes_noncename" id="post_newmetaboxes_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
}

function zib_postmeta_xzh_create()
{
    global $theme_name;
    if (function_exists('add_meta_box')) {
        add_meta_box('postmeta_xzh_boxes', __('百度熊掌号资源提交', 'zib_language'), 'zib_postmeta_xzh', 'post', 'normal', 'high');
        add_meta_box('postmeta_xzh_boxes', __('百度熊掌号资源提交', 'zib_language'), 'zib_postmeta_xzh', 'page', 'normal', 'high');
    }
}

function zib_postmeta_xzh_save($post_id)
{
    global $postmeta_xzh;

    if (!wp_verify_nonce(isset($_POST['post_newmetaboxes_noncename']) ? $_POST['post_newmetaboxes_noncename'] : '', plugin_basename(__FILE__)))
        return;

    if (!current_user_can('edit_posts', $post_id))
        return;

    foreach ($postmeta_xzh as $meta_box) {
        $data = isset($_POST[$meta_box['name']]) ? $_POST[$meta_box['name']] : '';
        if ($data) {
            tb_xzh_post_to_baidu($post_id);
        }
    }
}

// 百度资源推送提交
function tb_xzh_post_to_baidu($post_id = '')
{
    if (!$post_id) {
        global $post;
        $post_id = !empty($post->ID) ? $post->ID : 0;
    }

    if (_pz('xzh_post_token') && $post_id) {
        $post = get_post($post_id);
        $plink = get_permalink($post_id);
        $ok = get_post_meta($post_id, 'xzh_tui_back', true);
        $site = home_url();
        $urls = array();
        $urls[] = $plink;

        $result_meta = array(
            'update_time' => current_time("Y-m-d H:i:s"),
        );

        if ('publish' == $post->post_status && $plink) {
            if (empty($ok['normal_push']) && _pz('xzh_post_on')) {
                $api = 'http://data.zz.baidu.com/urls?site=' . $site . '&token=' . _pz('xzh_post_token');
                $ch = curl_init();
                $options =  array(
                    CURLOPT_URL => $api,
                    CURLOPT_POST => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POSTFIELDS => implode("\n", $urls),
                    CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
                );
                curl_setopt_array($ch, $options);
                $result = curl_exec($ch);
                $result = json_decode($result);

                if (!empty($result->success)) {
                    $result_meta['normal_push'] = true;
                } else {
                    $result_meta['normal_push'] = false;
                }
                $result_meta['normal_result'] = $result;
            }
            if (empty($ok['daily_push']) && _pz('xzh_post_daily_push')) {
                $api = 'http://data.zz.baidu.com/urls?site=' . $site . '&token=' . _pz('xzh_post_token') . '&type=daily';
                $ch = curl_init();
                $options =  array(
                    CURLOPT_URL => $api,
                    CURLOPT_POST => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POSTFIELDS => implode("\n", $urls),
                    CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
                );
                curl_setopt_array($ch, $options);
                $result = curl_exec($ch);
                $result = json_decode($result);

                if (!empty($result->success_daily)) {
                    $result_meta['daily_push'] = true;
                } else {
                    $result_meta['daily_push'] = false;
                }
                $result_meta['daily_result'] = $result;
            }
        }

        update_post_meta($post->ID, 'xzh_tui_back', $result_meta);
        return;
    }
}

//分类及专题设置SEO
class __Tax_Cat
{

    function __construct()
    {
        add_action('category_add_form_fields', array($this, 'add_tax_field'));
        add_action('category_edit_form_fields', array($this, 'edit_tax_field'));
        add_action('topics_add_form_fields', array($this, 'add_tax_field'));
        add_action('topics_edit_form_fields', array($this, 'edit_tax_field'));

        add_action('edit_term',  array($this, 'save_tax_meta'), 10, 2);
        add_action('create_term', array($this, 'save_tax_meta'), 10, 2);
    }

    public function add_tax_field()
    {
        echo '
        <div class="form-field">
            <label for="term_meta[title]">SEO 标题</label>
            <input type="text" name="term_meta[title]" id="term_meta[title]" />
        </div>
        <div class="form-field">
            <label for="term_meta[keywords]">SEO 关键字keywords）（用英文逗号分开）</label>
            <input type="text" name="term_meta[keywords]" id="term_meta[keywords]" />
        </div>
        <div class="form-field">
            <label for="term_meta[keywords]">SEO 描述（description）</label>
            <textarea name="term_meta[description]" id="term_meta[description]" rows="4" cols="40"></textarea>
            <p>主题默认会自动设置SEO内容，推荐自定义内容。相关建议请参考文章SEO设置</p>
            </div>
        ';
    }

    public function edit_tax_field($term)
    {

        $term_id = $term->term_id;
        $term_meta = get_option("_taxonomy_meta_$term_id");

        $meta_title = isset($term_meta['title']) ? $term_meta['title'] : '';
        $meta_keywords = isset($term_meta['keywords']) ? $term_meta['keywords'] : '';
        $meta_description = isset($term_meta['description']) ? $term_meta['description'] : '';

        echo '
      <tr class="form-field">
        <th scope="row">
            <label for="term_meta[title]">SEO 标题</label>
            <td>
                <input type="text" name="term_meta[title]" id="term_meta[title]" value="' . $meta_title . '" />
            </td>
        </th>
    </tr>
    <tr class="form-field">
        <th scope="row">
            <label for="term_meta[keywords]">SEO 关键字（keywords）</label>
            <td>
                <input type="text" name="term_meta[keywords]" id="term_meta[keywords]" value="' . $meta_keywords . '" />
            </td>
        </th>
    </tr>
    <tr class="form-field">
        <th scope="row">
            <label for="term_meta[description]">SEO 描述（description）</label>
            <td>
                <textarea name="term_meta[description]" id="term_meta[description]" rows="4">' . $meta_description . '</textarea>
                <p>主题默认会自动设置SEO内容，推荐自定义内容。相关建议请参考文章SEO设置</p>
            </td>
        </th>
    </tr>
    ';
    }

    public function save_tax_meta($term_id)
    {

        if (isset($_POST['term_meta'])) {

            $term_meta = array();

            $term_meta['title'] = isset($_POST['term_meta']['title']) ? esc_sql($_POST['term_meta']['title']) : '';
            $term_meta['keywords'] = isset($_POST['term_meta']['keywords']) ? esc_sql($_POST['term_meta']['keywords']) : '';
            $term_meta['description'] = isset($_POST['term_meta']['description']) ? esc_sql($_POST['term_meta']['description']) : '';

            update_option("_taxonomy_meta_$term_id", $term_meta);
        }
    }
}
if (_pz('post_keywords_description_s')) {
    $tax_cat = new __Tax_Cat();
}


add_filter('admin_footer_text', 'zib_admin_footer_thank', 99999);
function zib_admin_footer_thank()
{
    return '感谢您使用<a href="https://wordpress.org">WordPress</a>和<a href="https://zibll.com">子比主题</a>进行创作。';
}
