<?php
require( dirname(__FILE__).'/../../../../wp-load.php' );
if(is_uploaded_file($_FILES['imageFile']['tmp_name']) && is_user_logged_in() && _MBT('tougao_upload')){
	$vname = $_FILES['imageFile']['name'];
	$arrType=array('image/jpg','image/png','image/jpeg');
	$uploaded_ext  = substr( $vname, strrpos( $vname, '.' ) + 1);
	$uploaded_type = $_FILES[ 'imageFile' ][ 'type' ];
	$uploaded_tmp  = $_FILES[ 'imageFile' ][ 'tmp_name' ];
	if ($vname != "") {
		if (in_array($uploaded_type,$arrType) && (strtolower( $uploaded_ext ) == 'jpg' || strtolower( $uploaded_ext ) == 'jpeg' || strtolower( $uploaded_ext ) == 'png' )) {

			//上传路径
			$upfile = '../../../../wp-content/uploads/modown/';
			if(!file_exists($upfile)){  mkdir($upfile,0777,true);} 

			$filename = md5(date("YmdHis").mt_rand(100,999)).strrchr($vname,'.');

			$file_path = '../../../../wp-content/uploads/modown/'. $filename;

			if( $uploaded_type == 'image/jpeg' ) {
	            $img = imagecreatefromjpeg( $uploaded_tmp );
	            imagejpeg( $img, $file_path, 100);
	        }else {
	            $img = imagecreatefrompng( $uploaded_tmp );
	            imagepng( $img, $file_path, 9);
	        }
	        imagedestroy( $img );

	        echo home_url().'/wp-content/uploads/modown/'. $filename;

		}
	}
}