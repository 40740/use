<?php
$weixin = zrz_wx_pay_type();
$alipay = zrz_alipay_type();
  $data = array_map('stripslashes_deep', $_POST);

if($alipay == 'xunhu' || $weixin == 'xunhu'){

    require ZRZ_THEME_DIR.'/inc/SDK/Xunhu/api.php';

    $xunhu = zrz_get_pay_settings('xunhu');

    $appsecret = isset($xunhu['appsecret']) ? $xunhu['appsecret'] : '';
    $my_plugin_id = isset($xunhu['plugins']) ? $xunhu['plugins'] : '';

    $hupijiao = false;

    if($alipay == 'xunhu'){
        $hupijiao = zrz_get_pay_settings('hupijiao');
        if(isset($hupijiao['hupijiao_alipay_open']) && $hupijiao['hupijiao_alipay_open'] == 1 && $data['appid'] === $hupijiao['hupijiao_alipay_appid']){
            $appsecret = isset($hupijiao['hupijiao_alipay_appsecret']) ? $hupijiao['hupijiao_alipay_appsecret'] : '';
            $hupijiao = true;
        }
    }

    if($weixin == 'xunhu'){
        $hupijiao_wx = zrz_get_pay_settings('hupijiao');
        if(isset($hupijiao_wx['hupijiao_wx_open']) && $hupijiao_wx['hupijiao_wx_open'] == 1 && $data['appid'] === $hupijiao_wx['hupijiao_wx_appid']){
            $appsecret = isset($hupijiao_wx['hupijiao_wx_appsecret']) ? $hupijiao_wx['hupijiao_wx_appsecret'] : '';
            $hupijiao = true;
        }
    }

  

    if(!isset($data['hash']) || !isset($data['trade_order_id'])){
       echo 'failed0';exit;
    }

    // 搜库资源网 soku.cc   自定义插件ID,请与支付请求时一致
    if(isset($data['plugins']) && $data['plugins']!=$my_plugin_id && $hupijiao == false){
        echo 'failed1';exit;
    }

    // 搜库资源网 soku.cc   APP SECRET
    $appkey = $appsecret;
    $hash =XH_Payment_Api::generate_xh_hash($data,$appkey);

    if($data['hash'] != $hash){
        // 搜库资源网 soku.cc   签名验证失败
        echo 'failed2';exit;
    }

    if($data['status']=='OD'){
        // 搜库资源网 soku.cc   支付单号
        $pay_order_id = $data['trade_order_id'];
        // 搜库资源网 soku.cc   支付的金额
        $total_amount = $data['total_fee'];

        // 搜库资源网 soku.cc   更新订单数据
        zrz_notify_data_update($pay_order_id,$total_amount);
    }else{
        echo 'error';
        exit;
    }

    echo 'success';
    exit;

}else{
    require ZRZ_THEME_DIR . '/inc/SDK/PayAll/init.php';

    // 搜库资源网 soku.cc    加载配置参数
    $config = require(ZRZ_THEME_DIR . '/inc/SDK/PayConfig.php');

    $_POST = array_map('stripslashes_deep', $_POST);

    $pay = new \Pay\Pay($config);
    $res = $pay->driver('alipay')->gateway()->verify($_POST);
    if ($res && $res['trade_status'] === 'TRADE_SUCCESS') {
        $pay_order_id = $_POST['out_trade_no'];
        // 搜库资源网 soku.cc   支付的金额
        $total_amount = $_POST['total_amount'];

        // 搜库资源网 soku.cc   更新订单数据
        $res = zrz_notify_data_update($pay_order_id,$total_amount);
        if($res){
            echo 'success';
            exit;
        }
    } else {
        echo 'error';
        exit;
    }
}
