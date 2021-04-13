<?php
add_shortcode('mofilter','MBThemes_shortcode_filter');
function MBThemes_shortcode_filter($atts){
  global $post_target;
  $atts = shortcode_atts( array(
    'id' => '',
    'num' => 8,
    'title' => '',
    'desc' => '',
    'more' => 1,
    'new' => 0,
    'text' => '查看更多',
    'link' => '',
    'cols' => 3
  ), $atts, 'mocat' );
  $title = $atts['title']?$atts['title']:get_cat_name($atts['id']);
  $css = '';
  $cat_class = 'grids';
  $style = _MBT('list_style');
  if($style == 'list') $cat_class = 'lists';
  if($atts['id']) {
    $category = get_term( $atts['id'], 'category' );
    $moid = ' id="mocat-'.$atts['id'].'"';
    $style = get_term_meta($atts['id'],'style',true);
    if($style == 'list') $cat_class = 'lists';
    elseif($style == 'grid') $cat_class = 'grids';
    $timthumb_height = get_term_meta($atts['id'],'timthumb_height',true);
    if($timthumb_height){
      $css = '<style>#mocat-'.$atts['id'].' .grids .grid .img{height: '.$timthumb_height.'px;}
        @media (max-width: 1230px){
          #mocat-'.$atts['id'].' .grids .grid .img{height: '.(($timthumb_height=="285")?"232.5":(232.5*$timthumb_height/285)).'px;}
        }
        @media (max-width: 1024px){
          #mocat-'.$atts['id'].' .grids .grid .img{height: '.$timthumb_height.'px;}
        }
        @media (max-width: 925px){
          #mocat-'.$atts['id'].' .grids .grid .img{height: '.(($timthumb_height=="285")?"232.5":(232.5*$timthumb_height/285)).'px;}
        }
        @media (max-width: 768px){
          #mocat-'.$atts['id'].' .grids .grid .img{height: '.$timthumb_height.'px;}
        }
        @media (max-width: 620px){
          #mocat-'.$atts['id'].' .grids .grid .img{height: '.(($timthumb_height=="285")?"232.5":(232.5*$timthumb_height/285)).'px;}
        }
        @media (max-width: 480px){
          #mocat-'.$atts['id'].' .grids .grid .img{height: '.(($timthumb_height=="285")?"180":(180*$timthumb_height/285)).'px;}
        }
        </style>';
    }
  }else {
    $category = '';
    $moid = '';
  }
  $html = '<div class="mocat"'.$moid.'>'.$css.'<div class="container">';
  $html .= '<h2><span>'.$title;
  if($atts['new']){
    $html .= '<i>NEW</i>';
  }
  $html .= '</span></h2>';

  if($atts['desc']){
    $html .= '<p class="desc">'.$atts['desc'].'</p>';
  }else{
    if($category){
      if($category->description) $html .= '<p class="desc">'.$category->description.'</p>';
    }
  }
  
  $html .= '<ul class="cfilter"><li><a href="javascript:;" class="active" data-o="date" data-c="'.$atts['id'].'" data-num="'.$atts['num'].'">最新</a></li>';
  $html .= '<li><a href="javascript:;" data-o="views" data-c="'.$atts['id'].'" data-num="'.$atts['num'].'">人气</a></li>';
  $html .= '<li><a href="javascript:;" data-o="downs" data-c="'.$atts['id'].'" data-num="'.$atts['num'].'">下载</a></li>';
  $html .= '<li><a href="javascript:;" data-o="free" data-c="'.$atts['id'].'" data-num="'.$atts['num'].'">免费</a></li>';
  $html .= '<li><a href="javascript:;" data-o="fee" data-c="'.$atts['id'].'" data-num="'.$atts['num'].'">付费</a></li>';
  $html .= '</ul>';

  if($atts['cols'] == 2){
    $cat_class .= ' cols-two';
  }

  $html .= '<div class="'.$cat_class.' clearfix">';
  if($atts['id']){ 
    $args = array(
      'cat'              => $atts['id'],
      'showposts'        => $atts['num'],
      'order'            => 'DESC',
      'ignore_sticky_posts' => 1
    );
  }else{
    $args = array(
      'showposts'        => $atts['num'],
      'category__not_in' => explode(',', _MBT('home_cats_exclude')),
      'order'            => 'DESC'
    );
  }
    
  query_posts($args);
  while (have_posts()) : the_post();
    $ts = get_post_meta(get_the_ID(),'down_special',true);
    $tj = get_post_meta(get_the_ID(),'down_recommend',true);
    $tsstyle = '';
    if($tj) $tj = ' grid-tj'; else $tj = '';
    if($ts && $cat_class != 'lists'){ 
      $ts = ' grid-ts'; 
      $tsstyle = ' style="background-image:url('.MBThemes_thumbnail_full().')"';
    }else $ts = '';
    $start_down=get_post_meta(get_the_ID(), 'start_down', true);
    $start_down2=get_post_meta(get_the_ID(), 'start_down2', true);
    $start_see=get_post_meta(get_the_ID(), 'start_see', true);
    $start_see2=get_post_meta(get_the_ID(), 'start_see2', true);
    $price=get_post_meta(get_the_ID(), 'down_price', true);
    $memberDown=get_post_meta(get_the_ID(), 'member_down',TRUE);
    $downtimes = get_post_meta(get_the_ID(),'down_times',true);
    $cat = MBThemes_get_child_cat();
    $html .= '<div class="post grid'.$tj.$ts.'"'.$tsstyle.'>
      <div class="img"><a href="'.get_permalink().'" title="'.get_the_title().'" target="'.$post_target.'" rel="bookmark">
        <img src="'.MBThemes_thumbnail().'" class="thumb" alt="'.get_the_title().'">
      </a></div>';
      if(_MBT('post_cat')) $html .= '<a href="'.get_category_link($cat->term_id ).'" class="cat">'.$cat->cat_name.'</a>';
      $html .= '<h3 itemprop="name headline"><a itemprop="url" rel="bookmark" href="'.get_permalink().'" title="'.get_the_title().'" target="'.$post_target.'">'.get_the_title().'</a></h3>';
      $html .= '<p class="excerpt">'.MBThemes_get_excerpt(80).'</p>';
      if(!_MBT('post_metas')){
      $html .= '<div class="grid-meta">';
        if(_MBT('post_date')) $html .= '<span class="time"><i class="icon icon-time"></i> '.MBThemes_timeago( get_the_time('Y-m-d G:i:s') ).'</span>';
        if(_MBT('post_views')) $html .= '<span class="views"><i class="icon icon-eye"></i> '.MBThemes_views(false).'</span>';
            if(_MBT('post_comments')) $html .= '<span class="comments"><i class="icon icon-comment"></i> '.get_comments_number('0', '1', '%').'</span>';
        if(($start_down || $start_down2 || $start_see || $start_see2) && wp_is_erphpdown_active()){
            if(_MBT('post_downloads')) $html .= '<span class="downs"><i class="icon icon-download"></i> '.($downtimes?$downtimes:'0').'</span>';
            if(!_MBT('post_price')){
                $html .= '<span class="price">';
                if($memberDown == '4' || $memberDown == '8' || $memberDown == '9') $html .= '<span class="vip-tag"><i>VIP</i></span>';
                elseif($price) $html .= '<span class="fee"><i class="icon icon-ticket"></i> '.$price.'</span>';
                else $html .= '<span class="vip-tag free-tag"><i>免费</i></span>';
                $html .= '</span>';
            }
        }
    $html .= '</div>';}
    $html .= '</div>';

  endwhile; wp_reset_query(); 
  $html .= '</div>';
  if($atts['more']) $html .= '<div class="more"><a href="'.($atts['link']?$atts['link']:get_category_link($atts['id'])).'" target="_blank">'.$atts['text'].'</a></div>';
    $html .= '</div></div>';
    return $html;
}

add_shortcode('mocat','MBThemes_shortcode_cat');
function MBThemes_shortcode_cat($atts){
  global $post_target;
	$atts = shortcode_atts( array(
    'id' => '',
    'num' => 8,
    'title' => '',
    'desc' => '',
    'more' => 1,
    'new' => 0,
    'recommend' => 0,
    'orderby' => 'date',
    'text' => '查看更多',
    'link' => '',
    'child' => 0,
    'child-num' => 5,
    'cols' => 3
  ), $atts, 'mocat' );
  $title = $atts['title']?$atts['title']:get_cat_name($atts['id']);
  $css = '';
  $cat_class = 'grids';
  $style = _MBT('list_style');
  if($style == 'list') $cat_class = 'lists';
  if($atts['id']) {
    $category = get_term( $atts['id'], 'category' );
    $moid = ' id="mocat-'.$atts['id'].'"';
    $style = get_term_meta($atts['id'],'style',true);
    if($style == 'list') $cat_class = 'lists';
    elseif($style == 'grid') $cat_class = 'grids';
    $timthumb_height = get_term_meta($atts['id'],'timthumb_height',true);
    if($timthumb_height){
        $css = '<style>#mocat-'.$atts['id'].' .grids .grid .img{height: '.$timthumb_height.'px;}
          @media (max-width: 1230px){
            #mocat-'.$atts['id'].' .grids .grid .img{height: '.(($timthumb_height=="285")?"232.5":(232.5*$timthumb_height/285)).'px;}
          }
          @media (max-width: 1024px){
            #mocat-'.$atts['id'].' .grids .grid .img{height: '.$timthumb_height.'px;}
          }
          @media (max-width: 925px){
            #mocat-'.$atts['id'].' .grids .grid .img{height: '.(($timthumb_height=="285")?"232.5":(232.5*$timthumb_height/285)).'px;}
          }
          @media (max-width: 768px){
            #mocat-'.$atts['id'].' .grids .grid .img{height: '.$timthumb_height.'px;}
          }
          @media (max-width: 620px){
            #mocat-'.$atts['id'].' .grids .grid .img{height: '.(($timthumb_height=="285")?"232.5":(232.5*$timthumb_height/285)).'px;}
          }
          @media (max-width: 480px){
            #mocat-'.$atts['id'].' .grids .grid .img{height: '.(($timthumb_height=="285")?"180":(180*$timthumb_height/285)).'px;}
          }
          </style>';
    }
  }else {
    $category = '';
    $moid = '';
  }
  $html = '<div class="mocat"'.$moid.'>'.$css.'<div class="container">';
  $html .= '<h2><span>'.$title;
  if($atts['new']){
  	$html .= '<i>NEW</i>';
  }
  $html .= '</span></h2>';

  if($atts['desc']){
    $html .= '<p class="desc">'.$atts['desc'].'</p>';
  }else{
    if($category){
      if($category->description) $html .= '<p class="desc">'.$category->description.'</p>';
    }
  }

  if($atts['child'] && $atts['id']){
  	$category = get_term_by('id',$atts['id'],'category');
  	$cat_childs = get_categories("parent=".$category->term_id."&hide_empty=1&depth=1");  
		if($cat_childs){
			$html .= '<ul class="child"><li><a href="javascript:;" class="active" data-c="'.$atts['id'].'" data-c2="0" data-num="'.$atts['num'].'">全部</a></li>';
			$i = 1;
			foreach ($cat_childs as $term) {
				if($i > $atts['child-num']) $html .= '';
				else $html .= '<li><a href="javascript:;" data-c="'.$atts['id'].'" data-c2="'.$term->term_id.'" data-num="'.$atts['num'].'">'.$term->name.'</a></li>';
				$i ++;
			}
			$html .= '</ul>';
		}
  }

  if($atts['cols'] == 2){
    $cat_class .= ' cols-two';
  }

  $html .= '<div class="'.$cat_class.' clearfix">';
  if($atts['id']){ 
    $args = array(
  		'cat'              => $atts['id'],
  		'showposts'        => $atts['num'],
      'order'            => 'DESC',
  		'ignore_sticky_posts' => 1
  	);
  }else{
    $args = array(
      'showposts'        => $atts['num'],
      'category__not_in' => explode(',', _MBT('home_cats_exclude')),
      'order'            => 'DESC'
    );
  }
  if($atts['orderby'] == 'views'){
    $args['orderby'] = 'meta_value_num';
    $args['meta_key'] = 'views';
  }if($atts['orderby'] == 'downs'){
    $args['orderby'] = 'meta_value_num';
    $args['meta_key'] = 'down_times';
  }elseif($atts['orderby'] == 'comment'){
    $args['orderby'] = 'comment_count';
  }elseif($atts['orderby'] == 'rand'){
    $args['orderby'] = 'rand';
  }else{
    $args['orderby'] = $atts['orderby'];
  }
  if($atts['recommend']){
    $args['meta_query'] = array(array('key'=>'down_recommend','value'=>'1'));
  }
	query_posts($args);
	while (have_posts()) : the_post();
    $ts = get_post_meta(get_the_ID(),'down_special',true);
		$tj = get_post_meta(get_the_ID(),'down_recommend',true);
    $tsstyle = '';
		if($tj) $tj = ' grid-tj'; else $tj = '';
    if($ts && $cat_class != 'lists'){ 
      $ts = ' grid-ts'; 
      $tsstyle = ' style="background-image:url('.MBThemes_thumbnail_full().')"';
    }else $ts = '';
		$start_down=get_post_meta(get_the_ID(), 'start_down', true);
    $start_down2=get_post_meta(get_the_ID(), 'start_down2', true);
    $start_see=get_post_meta(get_the_ID(), 'start_see', true);
    $start_see2=get_post_meta(get_the_ID(), 'start_see2', true);
	  $price=get_post_meta(get_the_ID(), 'down_price', true);
	  $memberDown=get_post_meta(get_the_ID(), 'member_down',TRUE);
    $downtimes = get_post_meta(get_the_ID(),'down_times',true);
    $cat = MBThemes_get_child_cat();
		$html .= '<div class="post grid'.$tj.$ts.'"'.$tsstyle.'>
		  <div class="img"><a href="'.get_permalink().'" title="'.get_the_title().'" target="'.$post_target.'" rel="bookmark">
		    <img src="'.MBThemes_thumbnail().'" class="thumb" alt="'.get_the_title().'">
		  </a></div>';
      if(_MBT('post_cat')) $html .= '<a href="'.get_category_link($cat->term_id ).'" class="cat">'.$cat->cat_name.'</a>';
		  $html .= '<h3 itemprop="name headline"><a itemprop="url" rel="bookmark" href="'.get_permalink().'" title="'.get_the_title().'" target="'.$post_target.'">'.get_the_title().'</a></h3>';
      $html .= '<p class="excerpt">'.MBThemes_get_excerpt(80).'</p>';
      if(!_MBT('post_metas')){
		  $html .= '<div class="grid-meta">';
		    if(_MBT('post_date')) $html .= '<span class="time"><i class="icon icon-time"></i> '.MBThemes_timeago( get_the_time('Y-m-d G:i:s') ).'</span>';
		    if(_MBT('post_views')) $html .= '<span class="views"><i class="icon icon-eye"></i> '.MBThemes_views(false).'</span>';
            if(_MBT('post_comments')) $html .= '<span class="comments"><i class="icon icon-comment"></i> '.get_comments_number('0', '1', '%').'</span>';
		    if(($start_down || $start_down2 || $start_see || $start_see2) && wp_is_erphpdown_active()){
		        if(_MBT('post_downloads')) $html .= '<span class="downs"><i class="icon icon-download"></i> '.($downtimes?$downtimes:'0').'</span>';
            if(!_MBT('post_price')){
                $html .= '<span class="price">';
                if($memberDown == '4' || $memberDown == '8' || $memberDown == '9') $html .= '<span class="vip-tag"><i>VIP</i></span>';
                elseif($price) $html .= '<span class="fee"><i class="icon icon-ticket"></i> '.$price.'</span>';
                else $html .= '<span class="vip-tag free-tag"><i>免费</i></span>';
                $html .= '</span>';
            }
		  	}
		$html .= '</div>';}
		$html .= '</div>';

	endwhile; wp_reset_query(); 
	$html .= '</div>';
	if($atts['more']) $html .= '<div class="more"><a href="'.($atts['link']?$atts['link']:get_category_link($atts['id'])).'" target="_blank">'.$atts['text'].'</a></div>';
    $html .= '</div></div>';
    return $html;
}

add_shortcode('mocats','MBThemes_shortcode_cats');
function MBThemes_shortcode_cats($atts, $content=null){
  global $post_target;
  $atts = shortcode_atts( array(
    'cols' => 3,
    'title' => '',
    'text' => '查看更多',
    'link' => '',
    'more' => 0
  ), $atts, 'mocats' );
  $cat_class = '';
  if($atts['cols'] == 2){
    $cat_class = ' cols-two';
  }

  $title = '';
  if($atts['title']){
    $title .= '<h2><span>'.$atts['title'];
    if($atts['new']){
      $title .= '<i>NEW</i>';
    }
    $title .= '</span></h2>';
  }

  $html = '<div class="mocat mocats'.$cat_class.'"><div class="container">'.$title.'<div class="molis clearfix">'.do_shortcode($content).'</div>';
  if($atts['more']) $html .= '<div class="more"><a href="'.$atts['link'].'" target="_blank">'.$atts['text'].'</a></div>';
  $html .='</div></div>';
  return $html;
}

add_shortcode('moli','MBThemes_shortcode_li');
function MBThemes_shortcode_li($atts){
  global $post_target;
  $atts = shortcode_atts( array(
    'id' => '',
    'recommend' => 0,
    'orderby' => 'date',
    'title' => '',
    'desc' => '',
    'link' => '',
    'num' => 8
  ), $atts, 'moli' );

  $title = $atts['title']?$atts['title']:get_cat_name($atts['id']);
  $banner_archive_img = '';$bg = '';
  if($atts['id']) {
    $category = get_term( $atts['id'], 'category' );
    $args = array(
      'cat'              => $atts['id'],
      'showposts'        => $atts['num'],
      'order'            => 'DESC',
      'ignore_sticky_posts' => 1
    );
    $desc = $atts['desc']?$atts['desc']:$category->description;
    if($desc) $desc = '<p class="des">'.$desc.'</p>';
    $banner_img = get_term_meta($atts['id'],'banner_img',true);
    if($banner_img){
        $banner_archive_img = $banner_img;
    }else{
        if(_MBT('banner_archive_img')){
            $banner_archive_img = _MBT('banner_archive_img');
        }
    }
  }else{
    if(_MBT('banner_archive_img')){
        $banner_archive_img = _MBT('banner_archive_img');
    }
    $desc = $atts['desc'];
    if($desc) $desc = '<p class="des">'.$desc.'</p>';
    $args = array(
      'showposts'        => $atts['num'],
      'order'            => 'DESC',
      'ignore_sticky_posts' => 1
    );
  }

  if($atts['orderby'] == 'views'){
    $args['orderby'] = 'meta_value_num';
    $args['meta_key'] = 'views';
  }if($atts['orderby'] == 'downs'){
    $args['orderby'] = 'meta_value_num';
    $args['meta_key'] = 'down_times';
  }elseif($atts['orderby'] == 'comment'){
    $args['orderby'] = 'comment_count';
  }elseif($atts['orderby'] == 'rand'){
    $args['orderby'] = 'rand';
  }else{
    $args['orderby'] = $atts['orderby'];
  }
  if($atts['recommend']){
    $args['meta_query'] = array(array('key'=>'down_recommend','value'=>'1'));
  }

  if($banner_archive_img) $bg = 'style="background-image: url('.$banner_archive_img.');"';

  $html = '<div class="moli"><div class="moli-header"'.$bg.'><h3>'.$title.'</h3>'.$desc.'<a href="'.($atts['link']?$atts['link']:get_category_link($atts['id'])).'" target="_blank"></a></div><ul>';
  query_posts($args);
  $i = 1;
  while (have_posts()) : the_post();
  $html .= '<li><i>'.$i.'</i><a href="'.get_permalink().'" target="'.$post_target.'">'.get_the_title().'</a><span><i class="icon icon-eye"></i> '.MBThemes_views(false).'</span></li>';
  $i ++;
  endwhile; wp_reset_query();
  $html .= '</ul></div>';
  return $html;
}

function MBThemes_shortcode_img($atts, $content=null, $code="") {
    $return = '<img class="alignnone" src="';
    $return .= htmlspecialchars($content);
    $return .= '" alt="" />';
    return $return;
}
add_shortcode('img' , 'MBThemes_shortcode_img' );

function MBThemes_shortcode_code($atts, $content=null, $code="") {
    $content = htmlspecialchars($content);
    $return = '<div class="code-highlight"><pre><code class="hljs">';
    $return .= ltrim($content, '\n');
    $return .= '</code></pre></div>';
    return $return;
}
add_shortcode('code' , 'MBThemes_shortcode_code' );

add_shortcode("ckplayer","MBThemes_ckplayer_shortcode");
function MBThemes_ckplayer_shortcode( $atts, $content=null ){
    $nonce = wp_create_nonce(rand(10,1000));
    return '<div id="ckplayer-video-'.$nonce.'" class="ckplayer-video" style="margin-bottom:30px;"></div>
    <script type="text/javascript">
        var videoObject'.$nonce.' = {
            container:"#ckplayer-video-'.$nonce.'",
            variable:"player",
            autoplay:false,
            video:"'.trim($content).'"
        };
        var player=new ckplayer(videoObject'.$nonce.');
    </script>';
}

add_shortcode("login","MBThemes_login_shortcode");
function MBThemes_login_shortcode( $atts, $content=null ){
    if(is_user_logged_in()){
      return '<p>'.do_shortcode($content).'</p>';
    }else{
      return '<div class="modown-login">此内容 <a href="javascript:;" class="signin-loader">登录</a> 后可见！</div>';
    }
}

add_shortcode("reply","MBThemes_reply_shortcode");
function MBThemes_reply_shortcode( $atts, $content=null ){
    extract(shortcode_atts(array("notice" => '<div class="modown-reply">此内容 <a href="#respond">评论</a> 本文后刷新可见！</div>'), $atts));   
    $email = null;   
    $user_ID = (int) wp_get_current_user()->ID;   
    if ($user_ID > 0) {   
        $email = get_userdata($user_ID)->user_email;   
        $admin_email = get_option('admin_email');  
        if ($email == $admin_email) {   
            return '<p>'.do_shortcode($content).'</p>';
        }   
    } else if (isset($_COOKIE['comment_author_email_' . COOKIEHASH])) {   
        $email = str_replace('%40', '@', $_COOKIE['comment_author_email_' . COOKIEHASH]);   
    } else {   
        return $notice;   
    }   
    if (empty($email)) {   
        return $notice;   
    }   
    global $wpdb;   
    $post_id = get_the_ID();   
    $query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `comment_author_email`='{$email}' LIMIT 1";   
    if ($wpdb->get_results($query)) {   
        return '<p>'.do_shortcode($content).'</p>';
    } else {
      if($user_ID){
        $query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `user_id`='{$user_ID}' LIMIT 1";   
        if ($wpdb->get_results($query)) {   
          return '<p>'.do_shortcode($content).'</p>';
        }else{
          return $notice;  
        }
      }else{
        return $notice;  
      } 
    } 
}

add_action( 'admin_init', 'modown_tinymce_button' );
function modown_tinymce_button() {
     if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
          add_filter( 'mce_buttons', 'modown_register_tinymce_button' );
          add_filter( 'mce_external_plugins', 'modown_add_tinymce_button' );
     }
}

function modown_register_tinymce_button( $buttons ) {
     array_push( $buttons, "button_ckplayer");
     array_push( $buttons, "button_erphpdown");
     array_push( $buttons, "button_reply");
     array_push( $buttons, "button_login");
     return $buttons;
}

function modown_add_tinymce_button( $plugin_array ) {
     $plugin_array['mobantu_button_script'] = get_bloginfo('template_directory') . "/static/js/editor.js";
     return $plugin_array;
}
