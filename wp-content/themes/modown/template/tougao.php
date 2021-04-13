<?php 
/*
	template name: 投稿页面
	description: template for mobantu.com modown theme 
*/
if(!is_user_logged_in()){
	header("Location:".get_permalink(MBThemes_page("template/login.php")).'?redirect_to='.MBThemes_selfURL());
}
$is_submit_page = 1;
date_default_timezone_set('Asia/Shanghai');
get_header();
global $current_user;
$security_nonce = wp_create_nonce( 'security_nonce' );
if(isset($_POST['security']) && is_user_logged_in()){
	if($security_nonce == $_POST['security']){
		$last_post = $wpdb->get_var("SELECT post_date FROM $wpdb->posts WHERE post_author='".$current_user->ID."' AND post_type = 'post' ORDER BY post_date DESC LIMIT 1");
	    if ( time() - strtotime($last_post) < 300 ){
	        echo '<script>alert("这也太快了吧，喝杯咖啡~");</script>'; 
	    }else{

			$title =   $wpdb->escape($_POST['title']) ;
			$content =  $_POST['content'] ;
			$cat =  $wpdb->escape($_POST['cat']);
			$status = 'draft';//publish  draft
			$submit = array(
				'post_title' => strip_tags($title),
				'post_author' => $current_user->ID,
				'post_content' => $content,
				'post_category' => array($cat),
				'post_status' => $status
			);
			$status = wp_insert_post( $submit );
			
			if ($status != 0) {
				if($_POST['image']){
					update_post_meta($status,'_thumbnail_ext_url',$wpdb->escape($_POST['image']));
				}
				if(wp_is_erphpdown_active() && $_POST['start_down'] == '1'){
					update_post_meta($status,'start_down',"yes");
					update_post_meta($status,'member_down','1');
					//update_post_meta($status,'down_days',$wpdb->escape($_POST['down_days']));
			        update_post_meta($status,'down_price',$wpdb->escape($_POST['down_price']));
			        update_post_meta($status,'down_url',$wpdb->escape($_POST['down_url']));
			        update_post_meta($status,'hidden_content',$wpdb->escape($_POST['hidden_content'])); 
				}
				echo '<script>alert("投稿成功，请等待管理员审核！");</script>';
			}else{
				echo '<script>alert("投稿失败，请稍后重试！");</script>';
			}
		}
	}
}
?>
<div class="main">
	<div class="container" style="max-width: 800px !important">
		<div class="content-wrap">
	    	<div class="content">
	    		<article class="single-content">
		    		<header class="article-header">
		    			<h1 class="article-title center"><?php the_title(); ?></h1>
		    		</header>
		    		<div class="tougao-content">
		    			<form method="post">
		    				<div class="tougao-item">
		    					<label>标题 *</label>
		    					<input type="text" name="title" class="tougao-input" required="" />
		    				</div>
		    				<div class="tougao-item">
		    					<label>分类 *</label>
		    					<div class="tougao-select"><?php wp_dropdown_categories('show_option_all=选择分类&orderby=name&hierarchical=1&selected=-1&depth=0&hide_empty=0');?></div>
		    				</div>
		    				<div class="tougao-item">
		    					<label>封面图地址</label>
		    					<input type="url" name="image" id="image" class="tougao-input tougao-input2" />
		    					<?php if(_MBT('tougao_upload')){?>
		    					<div class="upload-wrap"><a href="javascript:;" class="tougao-upload"><i class="icon icon-image"></i></a><span id="file-progress" class="file-progress"></span></div>
		    					<?php }?>
		    				</div>
		    				<div class="tougao-item">
		    					<label>内容 *</label>
		    					<?php wp_editor( '', 'content',post_editor_settings(array('textarea_name'=>'content')) ); ?>
		    				</div>
		    				<?php if(wp_is_erphpdown_active()){?>
		    				<div class="tougao-item">
		    					<label>收费下载</label>
		    					<div class="tougao-select">
		    						<input type="radio" name="start_down" id="start_down1" value="0" checked=""> <label for="start_down1">不启用</label>
		    						<input type="radio" name="start_down" id="start_down2" value="1"> <label for="start_down2">启用</label>
		    					</div>
		    					<p>售卖总额的<?php echo (get_option('ice_ali_money_author')?get_option('ice_ali_money_author'):'100');?>%将直接进入您的网站余额</p>
		    				</div>
		    				<div class="tougao-item tougao-item-erphpdown">
		    					<label>下载价格</label>
		    					<input type="number" name="down_price" class="tougao-input" min="0" step="0.01" style="width:150px;"/>
		    					<p>留空或0则表示免费下载</p>
		    				</div>
		    				<div class="tougao-item tougao-item-erphpdown">
		    					<label>下载地址</label>
		    					<input type="text" id="down_url" name="down_url" class="tougao-input tougao-input2" placeholder="" />
		    					<?php if(_MBT('tougao_upload')){?>
		    					<div class="upload-wrap"><a href="javascript:;" class="tougao-upload2"><i class="icon icon-upload-cloud"></i></a> <span id="file-progress2" class="file-progress"></span></div>
		    					<?php }?>
		    					<p>（可输入网盘地址，上传附件仅支持.zip .rar .7z格式）</p>
		    				</div>
		    				<div class="tougao-item tougao-item-erphpdown">
		    					<label>提取码</label>
		    					<input type="text" name="hidden_content" class="tougao-input" style="width:200px;"/>
		    					<p>提取码或者解压密码</p>
		    				</div>
		    				<!--div class="tougao-item tougao-item-erphpdown">
		    					<label>过期天数</label>
		    					<input type="number" name="down_days" class="tougao-input" min="0" step="1" style="width:200px;"/>
		    					<p>留空则表示一次购买，永久下载</p>
		    				</div-->
		    				<?php }?>
		    				<div class="tougao-item">
		    					<button class="tougao-btn" type="submit">提交</button>
		    					<input type="hidden" name="security" value="<?php echo $security_nonce;?>">
		    				</div>
		    			</form>
		    			<form style="display:none" id="imageForm" action="<?php bloginfo("template_url");?>/action/image.php" enctype="multipart/form-data" method="post"><input type="file" id="imageFile" name="imageFile" accept="image/png, image/jpeg"></form>
		    			<form style="display:none" id="fileForm" action="<?php bloginfo("template_url");?>/action/file.php" enctype="multipart/form-data" method="post"><input type="file" id="fileFile" name="fileFile" accept=".zip, .rar, .7z"></form>
		    		</div>
	            </article>
	    	</div>
	    </div>
	</div>
</div>
<?php get_footer();?>