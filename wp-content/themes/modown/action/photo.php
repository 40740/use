<?php
require( dirname(__FILE__).'/../../../../wp-load.php' );
if(is_uploaded_file($_FILES['avatarphoto']['tmp_name']) && is_user_logged_in()){
	$vname = $_FILES['avatarphoto']['name'];
	$arrType=array('image/jpg','image/png','image/jpeg');
	$uploaded_ext  = substr( $vname, strrpos( $vname, '.' ) + 1);
	$uploaded_type = $_FILES[ 'avatarphoto' ][ 'type' ];
	$uploaded_size = $_FILES['avatarphoto']['size'];
	$uploaded_tmp  = $_FILES[ 'avatarphoto' ][ 'tmp_name' ];
	if ($vname != "") {
		if (in_array($uploaded_type,$arrType) && (strtolower( $uploaded_ext ) == 'jpg' || strtolower( $uploaded_ext ) == 'jpeg' || strtolower( $uploaded_ext ) == 'png' )) {

			if ($uploaded_size > 102400) {
				echo "2";
			}elseif(!(in_array($uploaded_type,$arrType) && (strtolower( $uploaded_ext ) == 'jpg' || strtolower( $uploaded_ext ) == 'jpeg' || strtolower( $uploaded_ext ) == 'png' ))){
				echo "3";
			}else{
				//上传路径
				$upfile = '../../../../wp-content/uploads/avatar/';
				if(!file_exists($upfile)){  mkdir($upfile,0777,true);} 

				$userid = wp_get_current_user()->ID;

				$filename = md5($userid).strrchr($vname,'.');

				$file_path = '../../../../wp-content/uploads/avatar/'. $filename;

				if( $uploaded_type == 'image/jpeg' ) {
		            $img = imagecreatefromjpeg( $uploaded_tmp );
		            imagejpeg( $img, $file_path, 100);
		        }else {
		            $img = imagecreatefrompng( $uploaded_tmp );
		            imagepng( $img, $file_path, 9);
		        }
		        imagedestroy( $img );
		        update_user_meta($userid, 'photo', get_bloginfo('siteurl').'/wp-content/uploads/avatar/'.$filename);
		        echo "1";
		    }

		}
	}
}