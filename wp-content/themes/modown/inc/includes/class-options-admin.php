<?php
require( dirname(__FILE__).'/../../../../../wp-load.php' );
if(isset($_POST) && current_user_can('administrator')){
	if($_POST['action'] == 'update'){
		$status = 0;$message = '更新失败';
		$theme = $_POST['theme'];
		$username = get_option('MBT_'.$theme.'_user');
		$token = get_option('MBT_'.$theme.'_key');
		$home = $_POST['home'];
		$body = array('username'=>$username, 'token'=>$token, 'theme'=>$theme, 'domain'=>$_SERVER['SERVER_NAME'], 'action'=>'update');
		$result_body = json_decode(mbt_send_request($body));
		if( isset($result_body->status) && $result_body->status=='1' ){
			update_option('MBT_'.$theme.'_options',$result_body->ops);
			update_option( 'MBT_'.$theme.'_version', THEME_VER );
			$status = 1;
			$message = $result_body->message;
		}
		$arr=array(
			"status"=>$status,
			"message"=>$message
		); 
		$jarr=json_encode($arr); 
		echo $jarr;
	}elseif($_POST['action'] == 'check'){
		$v = get_url_contents("http://api.mobantu.com/theme/modown.php");
		if($v > THEME_VER){
			$arr=array(
				"status"=>1
			); 
		}else{
			$arr=array(
				"status"=>0
			);
		}
		$jarr=json_encode($arr); 
		echo $jarr;
	}else{
		$status = 0;$message = '激活失败';
		$username = $_POST['username'];
		$token = $_POST['token'];
		$home = $_POST['home'];
		$theme = $_POST['theme'];
		$body = array('username'=>$username, 'token'=>$token, 'theme'=>$theme, 'domain'=>$_SERVER['SERVER_NAME'], 'action'=>'active');
		$result_body = json_decode(mbt_send_request($body));
		if( isset($result_body->status) && $result_body->status=='1' ){
			update_option('MBT_'.$theme.'_user',$username);
			update_option('MBT_'.$theme.'_token',$result_body->token);
			update_option('MBT_'.$theme.'_key',$token);
			update_option('MBT_'.$theme.'_options',$result_body->ops);
			update_option('MBT_'.$theme.'_version', THEME_VER );
			$status = 1;
			$message = $result_body->message;
		}
		$arr=array(
			"status"=>$status,
			"message"=>$message
		); 
		$jarr=json_encode($arr); 
		echo $jarr;
	}
}