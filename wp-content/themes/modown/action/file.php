<?php
require( dirname(__FILE__).'/../../../../wp-load.php' );
if(is_uploaded_file($_FILES['fileFile']['tmp_name']) && is_user_logged_in() && _MBT('tougao_upload')){
    $vname = $_FILES['fileFile']['name'];
    $arrType=array('.zip','.rar','.7z');
    $vtype = strtolower(strrchr($vname,'.'));
    if ($vname != "") {
        if (!in_array($vtype,$arrType)) {
            
        }else{
            $filename = md5(date("YmdHis").mt_rand(100,999)).strrchr($vname,'.');
            //上传路径
            $upfile = '../../../../wp-content/uploads/modown/';
            if(!file_exists($upfile)){  mkdir($upfile,0777,true);} 
            $file_path = '../../../../wp-content/uploads/modown/'. $filename;
            if(move_uploaded_file($_FILES['fileFile']['tmp_name'], $file_path)){
                echo home_url().'/wp-content/uploads/modown/'. $filename;
            }
        }
    }
}