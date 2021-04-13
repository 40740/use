<?php
/* 
 * post meta from
 * ====================================================
*/
$postmeta_from = array(
	"浏览数" => array(
        "name" => "views",
        "std" => "0",
        "type" => "number",
        "title" => "浏览数"),
	"下载数" => array(
        "name" => "down_times",
        "std" => "0",
        "type" => "number",
        "title" => "下载数"),
    "iframe视频" => array(
        "name" => "video_type",
        "std" => "（指爱奇艺、腾讯视频等视频地址，仅需填写src的值，不是填整串代码）",
        "type" => "checkbox",
        "title" => "iframe视频"),
    "视频src地址" => array(
        "name" => "video",
        "std" => "",
        "type" => "text",
        "title" => "视频src地址"),
    "单栏" => array(
        "name" => "nosidebar",
        "std" => "",
        "type" => "checkbox",
        "title" => "单栏"),
    "推荐" => array(
        "name" => "down_recommend",
        "std" => "",
        "type" => "checkbox",
        "title" => "推荐"),
    "特殊" => array(
        "name" => "down_special",
        "std" => "（在列表页会与众不同，更突出）",
        "type" => "checkbox",
        "title" => "特殊"),
    "SEO标题" => array(
        "name" => "seo_title",
        "std" => "",
        "type" => "text",
        "title" => "SEO标题"),
    "SEO关键字" => array(
        "name" => "seo_keyword",
        "std" => "",
        "type" => "text",
        "title" => "SEO关键字"),
    "SEO描述" => array(
        "name" => "seo_description",
        "std" => "",
        "type" => "textarea",
        "title" => "SEO描述")
);

function mobantu_postmeta_from() {
    global $post, $postmeta_from;
    foreach($postmeta_from as $meta_box) {
        $meta_box_value = get_post_meta($post->ID, $meta_box['name'], true);
        if($meta_box_value == "")
            $meta_box_value = $meta_box['std'];
        echo'<p style="padding-left:100px;position:relative"><label style="font-weight:bold;width:100px;position:absolute;left:0;top:0;">'.$meta_box['title'].'</label>';
        if($meta_box['type'] == 'checkbox'){
            echo '<input type="checkbox" value="1" name="'.$meta_box['name'].'" ';
            if ( htmlentities( $meta_box_value, 1 ) == '1' ) echo ' checked="checked"';
            echo '>'.$meta_box['std'].'</p>';
        }elseif($meta_box['type'] == 'textarea'){
            echo '<textarea name="'.$meta_box['name'].'" style="width: 100%" row="3">'.$meta_box_value.'</textarea></p>';
        }elseif($meta_box['type'] == 'number'){
            echo '<input type="number" value="'.$meta_box_value.'" name="'.$meta_box['name'].'" style="width:100px"></p>';
        }else{
            echo '<input type="text" value="'.$meta_box_value.'" name="'.$meta_box['name'].'" style="width: 100%"></p>';
        }
    }
   
    echo '<input type="hidden" name="post_newmetaboxes_noncename" id="post_newmetaboxes_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
}

function mobantu_create_meta_box() {
    global $theme_name;
    if ( function_exists('add_meta_box') ) {
        add_meta_box( 'new-meta-boxes', 'Modown属性', 'mobantu_postmeta_from', 'post', 'normal', 'high' );
    }
}

function mobantu_save_postdata( $post_id ) {
    global $postmeta_from;
    if(!$_POST['post_newmetaboxes_noncename'])
        return;
   
    if ( !current_user_can( 'edit_posts', $post_id ))
        return;
                   
    foreach($postmeta_from as $meta_box) {
        $data = $_POST[$meta_box['name']];
        if(get_post_meta($post_id, $meta_box['name']) == "")
            add_post_meta($post_id, $meta_box['name'], $data, true);
        elseif($data != get_post_meta($post_id, $meta_box['name'], true))
            update_post_meta($post_id, $meta_box['name'], $data);
        elseif($data == "")
            delete_post_meta($post_id, $meta_box['name'], get_post_meta($post_id, $meta_box['name'], true));
    }
}

add_action('admin_menu', 'mobantu_create_meta_box');
add_action('save_post', 'mobantu_save_postdata');



add_filter( 'admin_post_thumbnail_html', 'MBThemes_thumbnail_url_field' );
add_action( 'save_post', 'MBThemes_thumbnail_url_field_save', 10, 2 );

function MBThemes_thumbnail_url_field( $html ) {
    global $post;
    $value = get_post_meta( $post->ID, '_thumbnail_ext_url', TRUE );
    $nonce = wp_create_nonce( plugin_basename(__FILE__) );
    $html .= '<input type="hidden" name="thumbnail_ext_url_nonce" value="' 
        . esc_attr( $nonce ) . '">';
    $html .= '<div><p>外链特色图片地址（留空即删除）：</p>';
    $html .= '<p><input type="url" name="thumbnail_ext_url" value="' . $value . '" style="width:100%"></p>';
    if ( ! empty($value) ) {
        $html .= '<p><img style="max-width:254px;height:auto;" src="' 
            . esc_url($value) . '"></p>';
    }
    $html .= '</div>';
    return $html;
}

function MBThemes_thumbnail_url_field_save( $pid, $post ) {

	if(!isset($_POST['thumbnail_ext_url_nonce']))
    	return;

    $cap = $post->post_type === 'page' ? 'edit_page' : 'edit_post';
    if (
        ! current_user_can( $cap, $pid )
        || ! post_type_supports( $post->post_type, 'thumbnail' )
        || defined( 'DOING_AUTOSAVE' )
    ) {
        return;
    }

    $url = $_POST['thumbnail_ext_url'];
    update_post_meta( $pid, '_thumbnail_ext_url', esc_url($url) );
    
}






function mbt_add_category_field(){  
    wp_enqueue_media ();
    echo '<div class="form-field">  
            <label for="banner_img">Banner图片</label>  
            <input name="banner_img" id="banner_img" type="text" value="">
            <a href="javascript:;" class="upload-img"><br>上传图片</a>（建议上传横幅类型的图片）
            <script>
                jQuery(document).ready(function() {
                var $ = jQuery;
                if ($(".upload-img").length > 0) {
                if ( typeof wp !== "undefined" && wp.media && wp.media.editor) {
                $(document).on("click", ".upload-img", function(e) {
                e.preventDefault();
                var button = $(this);
                var id = button.prev();
                wp.media.editor.send.attachment = function(props, attachment) {
                id.val(attachment.url);
                };
                wp.media.editor.open(button);
                return false;
                });
                }
                }
                });
            </script>
          </div>';
    echo '<div class="form-field">  
            <label for="seo-title">SEO标题</label>  
            <input name="seo-title" id="seo-title" type="text" value="">
          </div>';
    echo '<div class="form-field">  
            <label for="seo-keyword">SEO关键字</label>  
            <input name="seo-keyword" id="seo-keyword" type="text" value="">
          </div>';
    echo '<div class="form-field">  
            <label for="seo-description">SEO描述</label>  
            <textarea name="seo-description" id="seo-description" row="5"></textarea>
          </div>';
    
    if($_GET['taxonomy'] == 'category'){
        echo '<div class="form-field">  
            <label for="timthumb_height">缩略图图片高度</label>  
            <input name="timthumb_height" id="timthumb_height" type="text" value="">
            <p>文章列表的图片高度，单位px，输入一个数字即可，默认180，如果是正方形，请填285，此设置仅对该分类页面以及mocat短代码指定该分类模块生效。</p>  
          </div>';
        echo '<div class="form-field">
                <label for="filter_s">筛选开关</label>
                <select name="filter_s" id="filter_s" class="postform">
                    <option value="0">默认</option>
                    <option value="1">关闭</option>
                    <option value="2">开启</option>
                </select>
            </div>';
        echo '<div class="form-field">
                <label for="taxonomys_s">自定义分类法筛选开关</label>
                <select name="taxonomys_s" id="taxonomys_s" class="postform">
                    <option value="0">默认</option>
                    <option value="1">关闭</option>
                    <option value="2">开启</option>
                </select>
            </div>';
        echo '<div class="form-field">  
            <label for="taxonomys">自定义分类法筛选</label>  
            <input name="taxonomys" id="taxonomys" type="text" value="">  
            <p>名称,别名,筛选参数，多个用|隔开。例如：格式,format,fm|大小,size,sz</p>  
          </div>';
        echo '<div class="form-field">
                <label for="tags_s">标签筛选开关</label>
                <select name="tags_s" id="tags_s" class="postform">
                    <option value="0">默认</option>
                    <option value="1">关闭</option>
                    <option value="2">开启</option>
                </select>
            </div>';
        echo '<div class="form-field">  
            <label for="tags">标签筛选IDs</label>  
            <input name="tags" id="tags" type="text" value="" placeholder="1,3,6">  
            <p>需要筛选的标签ID列表，多个用半角英文逗号隔开。</p>  
          </div>';   
        echo '<div class="form-field">
                <label for="price_s">价格筛选开关</label>
                <select name="price_s" id="price_s" class="postform">
                    <option value="0">默认</option>
                    <option value="1">关闭</option>
                    <option value="2">开启</option>
                </select>
            </div>';  
        echo '<div class="form-field">
                <label for="order_s">排序筛选开关</label>
                <select name="order_s" id="order_s" class="postform">
                    <option value="0">默认</option>
                    <option value="1">关闭</option>
                    <option value="2">开启</option>
                </select>
            </div>';     
    }

    echo '<div class="form-field">
        <label for="style">显示样式</label>
        <select name="style" id="style" class="postform">
            <option value="default">默认</option>
            <option value="grid">网格Grid</option>
            <option value="list">列表List</option>
        </select>
    </div>';                  
}  
add_action('category_add_form_fields','mbt_add_category_field',10,2); 
add_action('post_tag_add_form_fields','mbt_add_category_field',10,2);   
  

function mbt_edit_category_field($tag){ 
    wp_enqueue_media ();
    echo '<tr class="form-field">  
            <th scope="row"><label for="banner_img">Banner图片</label></th>  
            <td>  
                <input name="banner_img" id="banner_img" type="text" value="';  
                echo get_term_meta($tag->term_id,'banner_img',true).'" />
                <a href="javascript:;" class="upload-img"><br>上传图片</a>（建议上传横幅类型的图片）
                <br><img src="'.get_term_meta($tag->term_id,'banner_img',true).'" style="max-width:400px;height:auto;">
                <script>
                    jQuery(document).ready(function() {
                    var $ = jQuery;
                    if ($(".upload-img").length > 0) {
                    if ( typeof wp !== "undefined" && wp.media && wp.media.editor) {
                    $(document).on("click", ".upload-img", function(e) {
                    e.preventDefault();
                    var button = $(this);
                    var id = button.prev();
                    wp.media.editor.send.attachment = function(props, attachment) {
                    id.val(attachment.url);
                    };
                    wp.media.editor.open(button);
                    return false;
                    });
                    }
                    }
                    });
                </script>
            </td>  
        </tr>'; 
    echo '<tr class="form-field">  
            <th scope="row"><label for="seo-title">SEO标题</label></th>  
            <td>  
                <input name="seo-title" id="seo-title" type="text" value="';  
                echo get_term_meta($tag->term_id,'seo-title',true).'" />
            </td>  
        </tr>';
    echo '<tr class="form-field">  
            <th scope="row"><label for="seo-keyword">SEO关键字</label></th>  
            <td>  
                <input name="seo-keyword" id="seo-keyword" type="text" value="';  
                echo get_term_meta($tag->term_id,'seo-keyword',true).'" />
            </td>  
        </tr>';
    echo '<tr class="form-field">  
            <th scope="row"><label for="seo-description">SEO描述</label></th>  
            <td>  
                <textarea name="seo-description" id="seo-description" row="5">';  
                echo get_term_meta($tag->term_id,'seo-description',true).'</textarea>
            </td>  
        </tr>';
    
    if($_GET['taxonomy'] == 'category'){
        echo '<tr class="form-field">  
            <th for="timthumb_height">缩略图图片高度</th>
            <td>  
            <input name="timthumb_height" id="timthumb_height" type="text" value="'.get_term_meta($tag->term_id,'timthumb_height',true).'">
            <p>文章列表的图片高度，单位px，输入一个数字即可，默认180，如果是正方形，请填285，此设置仅对该分类页面以及mocat短代码指定该分类模块生效。</p> 
            </td> 
          </tr>';
        $filter_s = get_term_meta($tag->term_id,'filter_s',true);
        echo '<tr class="form-field">
                <th scope="row">
                    <label for="filter_s">筛选开关</label>
                    <td>
                        <select name="filter_s" id="filter_s" class="postform">
                            <option value="0" '. ('0'==$filter_s?'selected="selected"':'') .'>默认</option>
                            <option value="1" '. ('1'==$filter_s?'selected="selected"':'') .'>关闭</option>
                            <option value="2" '. ('2'==$filter_s?'selected="selected"':'') .'>开启</option>
                        </select>
                    </td>
                </th>
            </tr>';  
        $taxonomys_s = get_term_meta($tag->term_id,'taxonomys_s',true);
        echo '<tr class="form-field">
                <th scope="row">
                    <label for="taxonomys_s">自定义分类法筛选开关</label>
                    <td>
                        <select name="taxonomys_s" id="taxonomys_s" class="postform">
                            <option value="0" '. ('0'==$taxonomys_s?'selected="selected"':'') .'>默认</option>
                            <option value="1" '. ('1'==$taxonomys_s?'selected="selected"':'') .'>关闭</option>
                            <option value="2" '. ('2'==$taxonomys_s?'selected="selected"':'') .'>开启</option>
                        </select>
                    </td>
                </th>
            </tr>';  
        echo '<tr class="form-field">  
            <th scope="row"><label for="taxonomys">自定义分类法筛选</label></th>  
            <td>  
                <input name="taxonomys" id="taxonomys" type="text" value="';  
                echo get_term_meta($tag->term_id,'taxonomys',true).'" /><br>  
                <span class="cat-color">'.$tag->name.' 的需要筛选的自定义分类法，名称,别名,筛选参数，多个用|隔开。例如：格式,format,fm|大小,size,sz。</span>  
            </td>  
        </tr>'; 
        $tags_s = get_term_meta($tag->term_id,'tags_s',true);
        echo '<tr class="form-field">
                <th scope="row">
                    <label for="tags_s">标签筛选开关</label>
                    <td>
                        <select name="tags_s" id="tags_s" class="postform">
                            <option value="0" '. ('0'==$tags_s?'selected="selected"':'') .'>默认</option>
                            <option value="1" '. ('1'==$tags_s?'selected="selected"':'') .'>关闭</option>
                            <option value="2" '. ('2'==$tags_s?'selected="selected"':'') .'>开启</option>
                        </select>
                    </td>
                </th>
            </tr>';  
        echo '<tr class="form-field">  
            <th scope="row"><label for="tags">标签筛选IDs</label></th>  
            <td>  
                <input name="tags" id="tags" type="text" value="';  
                echo get_term_meta($tag->term_id,'tags',true).'" /><br>  
                <span class="cat-color">'.$tag->name.' 的需要筛选的标签ID列表，多个用半角英文逗号隔开。</span>  
            </td>  
        </tr>'; 
        $price_s = get_term_meta($tag->term_id,'price_s',true);
        echo '<tr class="form-field">
                <th scope="row">
                    <label for="price_s">价格筛选开关</label>
                    <td>
                        <select name="price_s" id="price_s" class="postform">
                            <option value="0" '. ('0'==$price_s?'selected="selected"':'') .'>默认</option>
                            <option value="1" '. ('1'==$price_s?'selected="selected"':'') .'>关闭</option>
                            <option value="2" '. ('2'==$price_s?'selected="selected"':'') .'>开启</option>
                        </select>
                    </td>
                </th>
            </tr>'; 
        $order_s = get_term_meta($tag->term_id,'order_s',true);
        echo '<tr class="form-field">
                <th scope="row">
                    <label for="order_s">排序筛选开关</label>
                    <td>
                        <select name="order_s" id="order_s" class="postform">
                            <option value="0" '. ('0'==$order_s?'selected="selected"':'') .'>默认</option>
                            <option value="1" '. ('1'==$order_s?'selected="selected"':'') .'>关闭</option>
                            <option value="2" '. ('2'==$order_s?'selected="selected"':'') .'>开启</option>
                        </select>
                    </td>
                </th>
            </tr>';  
    }      
    $style = get_term_meta($tag->term_id,'style',true);
    echo '<tr class="form-field">
                <th scope="row">
                    <label for="style">显示样式</label>
                    <td>
                        <select name="style" id="style" class="postform">
                            <option value="default" '. ('default'==$style?'selected="selected"':'') .'>默认</option>
                            <option value="grid" '. ('gird'==$style?'selected="selected"':'') .'>网格Grid</option>
                            <option value="list" '. ('list'==$style?'selected="selected"':'') .'>列表List</option>
                        </select>
                    </td>
                </th>
            </tr>';  
}  
add_action('category_edit_form_fields','mbt_edit_category_field',10,2);  
add_action('post_tag_edit_form_fields','mbt_edit_category_field',10,2);
  
 
function mbt_taxonomy_metadate_edited($term_id){  
    if(!current_user_can('manage_categories')){  
        return $term_id;  
    }  
    update_term_meta($term_id,'banner_img',$_POST['banner_img']);
    update_term_meta($term_id,'seo-title',$_POST['seo-title']);
    update_term_meta($term_id,'seo-keyword',$_POST['seo-keyword']);
    update_term_meta($term_id,'seo-description',$_POST['seo-description']);
    if(isset($_POST['timthumb_height']))
        update_term_meta($term_id,'timthumb_height',$_POST['timthumb_height']);
    if(isset($_POST['tags_s'])){
        update_term_meta($term_id,'tags_s',$_POST['tags_s']);
        update_term_meta($term_id,'tags',$_POST['tags']);        
    }
    if(isset($_POST['taxonomys_s'])){
        update_term_meta($term_id,'taxonomys_s',$_POST['taxonomys_s']);
        update_term_meta($term_id,'taxonomys',$_POST['taxonomys']);        
    }
    if(isset($_POST['price_s'])){
        update_term_meta($term_id,'price_s',$_POST['price_s']);        
    }
    if(isset($_POST['order_s'])){
        update_term_meta($term_id,'order_s',$_POST['order_s']);        
    }
    if(isset($_POST['filter_s'])){
        update_term_meta($term_id,'filter_s',$_POST['filter_s']);        
    }
    update_term_meta($term_id,'style',$_POST['style']);

} 

function mbt_taxonomy_metadate_created($term_id){  
    if(!current_user_can('manage_categories')){  
        return $term_id;  
    }  
    add_term_meta($term_id,'banner_img',$_POST['banner_img']);
    add_term_meta($term_id,'seo-title',$_POST['seo-title']);
    add_term_meta($term_id,'seo-keyword',$_POST['seo-keyword']);
    add_term_meta($term_id,'seo-description',$_POST['seo-description']);
    if(isset($_POST['timthumb_height']))
        add_term_meta($term_id,'timthumb_height',$_POST['timthumb_height']);
    if(isset($_POST['tags_s'])){
        add_term_meta($term_id,'tags_s',$_POST['tags_s']);   
        add_term_meta($term_id,'tags',$_POST['tags']);   
    }
    if(isset($_POST['taxonomys_s'])){
        add_term_meta($term_id,'taxonomys_s',$_POST['taxonomys_s']);   
        add_term_meta($term_id,'taxonomys',$_POST['taxonomys']);   
    }
    if(isset($_POST['price_s'])){
        add_term_meta($term_id,'price_s',$_POST['price_s']);    
    }
    if(isset($_POST['order_s'])){
        add_term_meta($term_id,'order_s',$_POST['order_s']);    
    }
    if(isset($_POST['filter_s'])){
        add_term_meta($term_id,'filter_s',$_POST['filter_s']);    
    }
    add_term_meta($term_id,'style',$_POST['style']); 

}  
  
add_action('created_category','mbt_taxonomy_metadate_created',10,1);  
add_action('edited_category','mbt_taxonomy_metadate_edited',10,1); 
add_action('created_post_tag','mbt_taxonomy_metadate_created',10,1);  
add_action('edited_post_tag','mbt_taxonomy_metadate_edited',10,1); 