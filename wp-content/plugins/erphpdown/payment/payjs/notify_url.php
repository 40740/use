<?php
/* *
 * by mobantu
*/
require_once('../../../../../wp-config.php');
require_once("class.php");
global $wpdb, $wppay_table_name;
$payjs = new Payjs();
if(isset($_POST['type'])){
    $data = ["return_code" => $_POST['return_code'], "total_fee" => $_POST['total_fee'], "out_trade_no" => $_POST['out_trade_no'], "payjs_order_id" => $_POST['payjs_order_id'], "transaction_id" => $_POST['transaction_id'], "time_end" => $_POST['time_end'], "openid" => $_POST['openid'], "attach" => $_POST['attach'], "mchid" => $_POST['mchid'], "type" => $_POST['type']];
}else{
    $data = ["return_code" => $_POST['return_code'], "total_fee" => $_POST['total_fee'], "out_trade_no" => $_POST['out_trade_no'], "payjs_order_id" => $_POST['payjs_order_id'], "transaction_id" => $_POST['transaction_id'], "time_end" => $_POST['time_end'], "openid" => $_POST['openid'], "attach" => $_POST['attach'], "mchid" => $_POST['mchid']];
}

if($payjs->sign($data) == $_POST['sign'] && $_POST['return_code'] == '1' && get_option('erphpdown_payjs_appid')){

	$total_fee = $_POST['total_fee']/100;
	$out_trade_no = $wpdb->escape($_POST['out_trade_no']);

	if(strstr($out_trade_no,'wppay')){
		$order=$wpdb->get_row("select * from $wppay_table_name where order_num='".$out_trade_no."'");
		if($order){
			if(!$order->order_status){
				$total_fee = $order->post_price;
				$wpdb->query("UPDATE $wppay_table_name SET order_status=1 WHERE order_num = '".$out_trade_no."'");

				$postUserId=get_post($order->post_id)->post_author;
				$ice_ali_money_author = get_option('ice_ali_money_author');
				if($ice_ali_money_author){
					addUserMoney($postUserId,$total_fee*get_option('ice_proportion_alipay')*$ice_ali_money_author/100);
				}elseif($ice_ali_money_author == '0'){

				}else{
					addUserMoney($postUserId,$total_fee*get_option('ice_proportion_alipay'));
				}

				if($order->user_id){
					$ppost = get_post($order->post_id);
					erphpAddDownloadByUid($ppost->post_title,$order->post_id,$order->user_id,$total_fee*get_option('ice_proportion_alipay'),1,'',$ppost->post_author);
				}
			}
		}
	}else{
		epd_set_order_success($out_trade_no,$total_fee,'payjs');
	}
	echo 'success';
}