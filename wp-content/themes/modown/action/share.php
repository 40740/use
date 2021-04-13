<?php
require( dirname(__FILE__) . '/../../../../wp-load.php' ); 
if(isset($_POST['id']) && $_POST['id'] && $post = get_post($_POST['id'])){
    setup_postdata( $post );
    $img_url = MBThemes_thumbnail_share($post);
    $share_head = $img_url ? $img_url : _MBT("post_share_cover_img");
    $share_logo = _MBT("post_share_cover_logo")?_MBT("post_share_cover_logo"):_MBT("logo");
    $excerpt = MBThemes_get_excerpt("200");

    $res = array(
        'head' => MBThemes_image_to_base64($share_head),
        'logo' => MBThemes_image_to_base64($share_logo),
        'title' => $post->post_title,
        'excerpt' => $excerpt,
        'timestamp' => get_post_time('U', true)
    );
    wp_reset_postdata();
    echo wp_json_encode($res);
    //exit;
}