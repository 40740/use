<?php
// 搜库资源网 soku.cc   支付方式
function zrz_wx_pay_type(){
    $wxpay = zrz_get_pay_settings('weixin');
    $wxpay = isset($wxpay['_open_weixin']) ? $wxpay['_open_weixin'] : false;

    $payjs = zrz_get_pay_settings('weixinpay');
    $payjs = isset($payjs['open_weixin']) ? $payjs['open_weixin'] : false;

    $xunhu = zrz_get_pay_settings('xunhu');
    $xunhu = isset($xunhu['open']) ? $xunhu['open'] : false;

    $youzan = zrz_get_pay_settings('youzan');
    $youzan = isset($youzan['open']) ? $youzan['open'] : false;

    if(!$xunhu){
        $xunhu = zrz_get_pay_settings('hupijiao');
        $xunhu = isset($xunhu['hupijiao_wx_open']) ? $xunhu['hupijiao_wx_open'] : false;
    }

    if($wxpay) return 'weixin';
    if($payjs) return 'payjs';
    if($xunhu) return 'xunhu';
    if($youzan) return 'youzan';

    return false;
}

function zrz_alipay_type($dangmian = false){
    $alipay = zrz_get_pay_settings('alipay','open_alipay');

    $xunhu = zrz_get_pay_settings('xunhu');
    $xunhu = isset($xunhu['open']) ? $xunhu['open'] : false;

    if($dangmian){
        return $alipay && zrz_get_pay_settings('alipay','dangmian') && !wp_is_mobile() ? 1 : 0;
    }

    if(!$xunhu){
        $xunhu = zrz_get_pay_settings('hupijiao');
        $xunhu = isset($xunhu['hupijiao_alipay_open']) ? $xunhu['hupijiao_alipay_open'] : false;
    }

    if($alipay) return 'alipay';
    if($xunhu) return 'xunhu';
    return false;
}

// 搜库资源网 soku.cc   返回二维码
function zrz_back_qcode($order_id,$toatl_price,$title,$current_url,$paytype){

    $alipay = zrz_alipay_type(true);

    if($alipay == 1 && $current_url == 'http:// 搜库资源网 soku.cc   alipayscan'){
        require ZRZ_THEME_DIR . '/inc/SDK/PayAll/init.php';
        // 搜库资源网 soku.cc    加载配置参数
        $config = require ZRZ_THEME_DIR . '/inc/SDK/PayConfig.php';

        // 搜库资源网 soku.cc    参考请求参数  https:// 搜库资源网 soku.cc   docs.open.alipay.com/203/107090/
        $options = array(
            'out_trade_no' => $order_id, // 搜库资源网 soku.cc    商户订单号
            'total_amount' => $toatl_price*0.01, // 搜库资源网 soku.cc    支付金额
            'subject'      => $title, // 搜库资源网 soku.cc    支付订单描述
            'notify_url'   => home_url('/notify-pay'), // 搜库资源网 soku.cc    定义通知URL
        );

        $pay = new \Pay\Pay($config);

        try {
            $res = $pay->driver('alipay')->gateway('scan')->apply($options);
            return ZRZ_THEME_URI.'/inc/qrcode/index.php?c='.$res['qr_code'];
        } catch (Exception $e) {
            return false;
        }
    }

    $type = zrz_wx_pay_type();

    // 搜库资源网 soku.cc   微信官方支付
    if($type == 'weixin'){
        // 搜库资源网 soku.cc   微信官方支付
        require ZRZ_THEME_DIR . '/inc/SDK/PayAll/init.php';
        // 搜库资源网 soku.cc    加载配置参数
        $config = require(ZRZ_THEME_DIR . '/inc/SDK/PayConfig.php');
        $ip = zrz_get_client_ip();
        if(wp_is_mobile()){

            // 搜库资源网 soku.cc   公众号支付
            if(zrz_is_weixin()){
                $cuser_id = get_current_user_id();
                // 搜库资源网 soku.cc    支付参数
                $options = [
                    'out_trade_no'     => $order_id, // 搜库资源网 soku.cc    订单号
                    'total_fee'        => $toatl_price, // 搜库资源网 soku.cc    订单金额，**单位：分**
                    'body'             => $title, // 搜库资源网 soku.cc    订单描述
                    'spbill_create_ip' => $ip, // 搜库资源网 soku.cc    支付人的 IP
                    'openid'           => get_user_meta($cuser_id,'zrz_weixin_open_id',true), // 搜库资源网 soku.cc    支付人的 openID
                    'notify_url'       => home_url('/weixinpay-notify'), // 搜库资源网 soku.cc    定义通知URL
                ];

                // 搜库资源网 soku.cc    实例支付对象
                $pay = new \Pay\Pay($config);
                
                try {
                    return $pay->driver('wechat')->gateway('mp')->apply($options);
                } catch (Exception $e) {
                    return false;
                }
            }else{
                // 搜库资源网 soku.cc   h5支付
                $options = array(
                    'out_trade_no'     => $order_id, // 搜库资源网 soku.cc    订单号
                    'total_fee'        => $toatl_price, // 搜库资源网 soku.cc    订单金额，**单位：分**
                    'spbill_create_ip' => $ip, // 搜库资源网 soku.cc    支付人的 IP
                    'body'             => $title, // 搜库资源网 soku.cc    订单描述
                    'notify_url'       => home_url('/weixinpay-notify'), // 搜库资源网 soku.cc    定义通知URL
                );

                $return_url = home_url('/pay_return');
                $pay = new \Pay\Pay($config);

                try {
                    return $pay->driver('wechat')->gateway('wap')->apply($options,$return_url);
                } catch (Exception $e) {
                    return false;
                }
            }
        }else{
            // 搜库资源网 soku.cc   扫码支付
            $options = array(
                'out_trade_no'     => $order_id, // 搜库资源网 soku.cc    订单号
                'total_fee'        => $toatl_price, // 搜库资源网 soku.cc    订单金额，**单位：分**
                'body'             => $title, // 搜库资源网 soku.cc    订单描述
            );

            $pay = new \Pay\Pay($config);

            try {
                $url = $pay->driver('wechat')->gateway('scan')->apply($options);
                return ZRZ_THEME_URI.'/inc/qrcode/index.php?c='.$url;
            } catch (Exception $e) {
                return false;
            }
        }

    }

    // 搜库资源网 soku.cc   payjs 支付
    if($type == 'payjs'){
        require ZRZ_THEME_DIR.'/inc/SDK/Payjs/payjs.php';
        $arr =array(
           'body' =>$title,               // 搜库资源网 soku.cc    订单标题
           'out_trade_no' =>$order_id,       // 搜库资源网 soku.cc    订单号
           'total_fee' => $toatl_price,// 搜库资源网 soku.cc    金额,单位:分
           'notify_url'=> home_url('/weixinpay-notify')
        );

      	if($current_url == home_url('/cart')){
        	$current_url =  home_url('/user/'.get_current_user_id().'/orders');
        }
      
        $payjs = new Payjs($arr,zrz_get_pay_settings('weixinpay','key'),zrz_get_pay_settings('weixinpay','mchid'),zrz_is_weixin(),$current_url,$paytype);
        $rst = $payjs->pay();
        if(zrz_is_weixin()){
             return $rst;
        }
        $resout = json_decode($rst);
        if($resout->return_code == 1){

          	return $resout->qrcode;
          
        }
        return false;
    }

    // 搜库资源网 soku.cc   有赞支付
    if($type == 'youzan'){
        require_once ZRZ_THEME_DIR.'/inc/SDK/Youzan/YZGetTokenClient.php';
        require_once ZRZ_THEME_DIR.'/inc/SDK/Youzan/YZTokenClient.php';

        $token_client = new YZGetTokenClient(zrz_get_pay_settings('youzan','client_id'), zrz_get_pay_settings('youzan','client_secret'));

        $keys = array(
            'grant_type' => 'silent',
            'kdt_id' => zrz_get_pay_settings('youzan','kdt_id')
        );

        $token = $token_client->get_token('self', $keys);

        $client = new YZTokenClient($token['access_token']);

        $params = array(
            'qr_name' => $title,
            'qr_price' => $toatl_price,
            'qr_type' => 'QR_TYPE_DYNAMIC',
        );

        $resp = $client->post('youzan.pay.qrcode.create', '3.0.0', $params);
        if (!isset($resp['response'])) {
            return false;
        } else {
            $qr = $resp['response'];
            $qr_id = $qr['qr_id'];
            $qr_code = $qr['qr_code'];

            // 搜库资源网 soku.cc   保存 qr_id 到临时订单
            $ordre = new Zrz_order_Message();
            $resout = $ordre->update_orders_data(array($order_id),array(
                'order_id'=>$qr_id,
            ),true);

            return $qr_code;
        }
    }
}

// 搜库资源网 soku.cc   付费文章微信支付
add_action('wp_ajax_zrz_weixin_post_pay','zrz_weixin_post_pay');
function zrz_weixin_post_pay(){
    $post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
  	$current_url = isset($_POST['current_url']) ? esc_url($_POST['current_url']) : '';
  	$js_type = isset($_POST['type']) ? esc_attr($_POST['type']) : '';
    $balance = 0;
    $pay_type = 'post';

    $user_id = get_current_user_id();

    if(!$post_id || !$pay_type){
        print json_encode(array('status'=>401,'msg'=>__('非法操作','ziranzhi2')));
        exit;
    }
    $id_arr = array();
    $order_id = 'pay_post-'.$post_id.'-'.str_shuffle(uniqid()).'-'.$user_id;

    // 搜库资源网 soku.cc   检查文章所需的金额
    $cap = get_post_meta($post_id,'capabilities',true);

    // 搜库资源网 soku.cc   检查文章类型
    if(isset($cap['key']) && isset($cap['val']) && $cap['key'] == 'rmb'){
        $toatl_price = $cap['val']*100;
        $title = get_the_title($post_id);
        $title = mb_strimwidth(strip_tags(strip_shortcodes($title)), 0, 10,'...').' [付费阅读]';
        // 搜库资源网 soku.cc   支付订单号
        $id = array(
            'id'=>$post_id,
            'count'=>1
        );
        array_push($id_arr,$id);
    }else{
        print json_encode(array('status'=>401,'msg'=>__('商品类型错误','ziranzhi2')));
        exit;
    }

    // 搜库资源网 soku.cc   删除旧订单信息
    delete_user_meta($user_id,'zrz_orders');

    // 搜库资源网 soku.cc   开始订单状态
    delete_user_meta($user_id,'zrz_ds_resout');

    $user_order = array(
        'order'=>$order_id,
        'ids'=>$id_arr,// 搜库资源网 soku.cc   商品ID
        'total_price'=>$toatl_price/100,// 搜库资源网 soku.cc   支付金额
        'balance'=>$balance,// 搜库资源网 soku.cc   是否使用余额
        'payed'=>0,
        'type'=>$pay_type
    );

    // 搜库资源网 soku.cc   设置一个临时数据，回调的时候检查
    update_user_meta($user_id,'zrz_orders',$user_order);

    // 搜库资源网 soku.cc   先付费阅读信息打赏信息标记
    update_user_meta($user_id,'zrz_ds_resout','begin');

    // 搜库资源网 soku.cc   生成一个临时的订单
    $c_order_id = zrz_build_order_no();
    $ordre = new Zrz_order_Message($user_id,'0',$c_order_id,'',0,1,0,'w',$order_id);
    $resout = $ordre->add_data();

    $resout = zrz_back_qcode($c_order_id,$toatl_price,$title,$current_url,$js_type);
    if($resout){
        print json_encode(array('status'=>200,'msg'=>$resout,'user_id'=>$user_id));
        exit;
    }

    print json_encode(array('status'=>401,'msg'=>$resout));
    exit;
}

// 搜库资源网 soku.cc   微信打赏
add_action('wp_ajax_zrz_weixin_ds_pay','zrz_weixin_ds_pay');
function zrz_weixin_ds_pay(){

    $data =  isset($_POST['data']) ? $_POST['data'] : 0;

    $post_id = isset($data['post_id']) && !empty($data['post_id']) ? $data['post_id'] : '';
    $total_price = isset($data['price']) && !empty($data['price']) ? $data['price'] : '';
    $text = isset($data['text']) ? $data['text'] : 0;
	$current_url = isset($_POST['current_url']) ? esc_url($_POST['current_url']) : '';
    $js_type = isset($_POST['type']) ? esc_attr($_POST['type']) : '';
    if(!$post_id || !$total_price){
        print json_encode(array('status'=>401,'msg'=>__('非法操作','ziranzhi2')));
        exit;
    }
    $user_id = get_current_user_id();

    $author_id = get_post_field('post_author',$post_id);
    $order_id = 'pay_ds-'.$post_id.'-'.str_shuffle(uniqid()).'-'.$user_id;

    // 搜库资源网 soku.cc   先清除打赏信息
    delete_user_meta($user_id,'zrz_ds_resout');

    // 搜库资源网 soku.cc   记录留言信息
    update_user_meta($user_id,'zrz_ds_resout',array('text'=>$text));

    if($total_price <= 0){
        print json_encode(array('status'=>401,'msg'=>'输入金额有误'));
        exit;
    }

    // 搜库资源网 soku.cc   生成一个临时的订单
    $c_order_id = zrz_build_order_no();
    $ordre = new Zrz_order_Message($user_id,'0',$c_order_id,'',0,1,0,'w',$order_id);
    $resout = $ordre->add_data();

    $resout = zrz_back_qcode($c_order_id,$total_price*100,'[打赏]',$current_url,$js_type);
    if($resout){
        print json_encode(array('status'=>200,'msg'=>$resout,'user_id'=>$user_id));
        exit;
    }

    print json_encode(array('status'=>401,'msg'=>__('获取二维码失败','ziranzhi2')));
    exit;
}

// 搜库资源网 soku.cc   微信商品支付
add_action('wp_ajax_zrz_weixin_sp_pay','zrz_weixin_sp_pay');
function zrz_weixin_sp_pay(){
    // 搜库资源网 soku.cc   订单数据
    $data = isset($_POST['data']) ? (array)$_POST['data'] : '';
    $current_url = isset($_POST['current_url']) ? esc_url($_POST['current_url']) : '';
    $js_type = isset($_POST['type']) ? esc_attr($_POST['type']) : '';
    $order_content = isset($_POST['orderContent']) ? esc_sql(sanitize_text_field($_POST['orderContent'])) : '';
    if(!$data){
        print json_encode(array('status'=>401,'msg'=>__('非法操作','ziranzhi2')));
        exit;
    }

    $user_id = get_current_user_id();

    $msg = false;
    $toatl_price = 0;
    $id_arr = array();
    $title = '';
    $i = 0;

    // 搜库资源网 soku.cc   支付订单ID
    $order_id = 'pay_shop-1-'.str_shuffle(uniqid()).'-'.$user_id;
    foreach ($data as $value) {
        $i++;
        $post_id = number($value['pid']);

        // 搜库资源网 soku.cc   检查商品类型
        $type = get_post_meta($post_id, 'zrz_shop_type', true);
        if($type != 'normal'){
            $msg .= '<p><a href="'.get_permalink($post_id).'">'.get_the_title($post_id).'</a> 不是要出售的商品。</p>';
            break;
        }

        // 搜库资源网 soku.cc   检查商品剩余的数量
        $remaining = (int)zrz_shop_count_remaining($post_id);
        if($remaining - (int)$value['count'] < 0){
            $msg .= '<p><a href="'.get_permalink($post_id).'">'.get_the_title($post_id).'</a> 剩余数量不足，请修改订单。</p>';
            break;
        }

        // 搜库资源网 soku.cc   应付金额
        $price = zrz_get_shop_price_dom($post_id);
        $price = $price['price']*$value['count'];
        $toatl_price += $price;

        // 搜库资源网 soku.cc   支付信息
        $id = array(
            'id'=>$post_id,
            'count'=>$value['count']
        );
        array_push($id_arr,$id);
        // 搜库资源网 soku.cc   商品名称
        $title .= ($i == 1 ? '' : '，').get_the_title($post_id);
    }

    $title = mb_strimwidth(zrz_clear_code(strip_tags(strip_shortcodes($title))), 0, 10,'...');

    if($msg){
        print json_encode(array('status'=>401,'msg'=>$msg));
        exit;
    }

    // 搜库资源网 soku.cc   删除订单信息
    delete_user_meta($user_id,'zrz_orders');

    // 搜库资源网 soku.cc   开始订单状态
    delete_user_meta($user_id,'zrz_ds_resout');

    $user_order = array(
        'order'=>$order_id,
        'ids'=>$id_arr,// 搜库资源网 soku.cc   商品ID
        'total_price'=>$toatl_price,// 搜库资源网 soku.cc   支付金额
        'balance'=>0,// 搜库资源网 soku.cc   是否使用余额
        'payed'=>0,
        'type'=>'shop'
    );

    // 搜库资源网 soku.cc   设置一个临时数据，回调的时候检查
    update_user_meta($user_id,'zrz_orders',$user_order);

    // 搜库资源网 soku.cc   设置一个状态
    update_user_meta($user_id,'zrz_ds_resout','begin');

    // 搜库资源网 soku.cc   生成一个临时的订单
    $c_order_id = zrz_build_order_no();
    $ordre = new Zrz_order_Message($user_id,'0',$c_order_id,'',0,1,0,'w',$order_id,'',$order_content);
    $resout = $ordre->add_data();

    $resout = zrz_back_qcode($c_order_id,$toatl_price*100,$title,$current_url,$js_type);
    if($resout){
        print json_encode(array('status'=>200,'msg'=>$resout,'user_id'=>$user_id));
        exit;
    }

    print json_encode(array('status'=>401,'msg'=>__('获取二维码失败','ziranzhi2')));
    exit;
}

// 搜库资源网 soku.cc   微信充值支付
add_action('wp_ajax_zrz_weixin_cz_pay','zrz_weixin_cz_pay');
function zrz_weixin_cz_pay(){
    $price = isset($_POST['price']) ? $_POST['price'] : 0;
    $current_url = isset($_POST['current_url']) ? esc_url($_POST['current_url']) : '';
    $js_type = isset($_POST['type']) ? esc_attr($_POST['type']) : '';
    if(!$price){
        print json_encode(array('status'=>200,'msg'=>__('充值金额不可为零')));
        exit;
    }

    $user_id = get_current_user_id();

    // 搜库资源网 soku.cc   先清除充值信息
    delete_user_meta($user_id,'zrz_ds_resout');

    $order_id = 'pay_cz-1-'.str_shuffle(uniqid()).'-'.$user_id;

    // 搜库资源网 soku.cc   记录充值信息
    update_user_meta($user_id,'zrz_ds_resout',array('text'=>'cz'));

    // 搜库资源网 soku.cc   生成一个临时的订单
    $c_order_id = zrz_build_order_no();
    $ordre = new Zrz_order_Message($user_id,'0',$c_order_id,'',0,1,0,'w',$order_id);
    $resout = $ordre->add_data();

    $resout = zrz_back_qcode($c_order_id,$price*100,'[充值]',$current_url,$js_type);
    if($resout){
        print json_encode(array('status'=>200,'msg'=>$resout,'user_id'=>$user_id));
        exit;
    }

    print json_encode(array('status'=>401,'msg'=>__('获取二维码失败','ziranzhi2')));
    exit;
}

// 搜库资源网 soku.cc   充值订单确认
add_action('wp_ajax_zrz_cz_pay_check','zrz_cz_pay_check');
function zrz_cz_pay_check(){
    $user_id = get_current_user_id();
    $resout = get_user_meta($user_id,'zrz_ds_resout',true);
    if($resout == 'success'){
        print json_encode(array('status'=>200));
        exit;
    }
    print json_encode(array('status'=>401));
    exit;
}

// 搜库资源网 soku.cc   打赏订单确认
add_action('wp_ajax_zrz_ds_check', 'zrz_ds_check');
function zrz_ds_check(){
    $user_id = get_current_user_id();
    $resout = get_user_meta($user_id,'zrz_ds_resout',true);
    if($resout == 'success'){
        print json_encode(array('status'=>200,'msg'=>__('打赏成功','ziranzhi2')));
        exit;
    }
    print json_encode(array('status'=>401,'msg'=>__('打赏失败','ziranzhi2')));
    exit;
}

// 搜库资源网 soku.cc   微信购买会员
add_action('wp_ajax_zrz_weixin_vip_pay', 'zrz_weixin_vip_pay');
function zrz_weixin_vip_pay(){
	$lv = isset($_POST['lv']) ? $_POST['lv'] : '';
    $current_url = isset($_POST['current_url']) ? esc_url($_POST['current_url']) : '';
    $js_type = isset($_POST['type']) ? esc_attr($_POST['type']) : '';
	$user_id = get_current_user_id();

	if($lv != 'vip' && $lv != 'vip1' && $lv != 'vip2' && $lv != 'vip3'){
		print json_encode(array('status'=>401,'msg'=>__('非法操作','ziranzhi2')));
        exit;
	}

	$lv_setting = zrz_get_lv_settings($lv);
	$price = $lv_setting['price'];

	// 搜库资源网 soku.cc   先清除支付信息
    delete_user_meta($user_id,'zrz_ds_resout');
	update_user_meta($user_id,'zrz_ds_resout',array('vip'=>$lv));

	$order_id = 'pay_vip-0-'.str_shuffle(uniqid()).'-'.$user_id;

    // 搜库资源网 soku.cc   生成一个临时的订单
    $c_order_id = zrz_build_order_no();
    $ordre = new Zrz_order_Message($user_id,'0',$c_order_id,'',0,1,0,'w',$order_id);
    $resout = $ordre->add_data();

    $resout = zrz_back_qcode($c_order_id,$price*100,'[会员购买]',$current_url,$js_type);
    if($resout){
        print json_encode(array('status'=>200,'msg'=>$resout,'user_id'=>$user_id));
        exit;
    }

    print json_encode(array('status'=>401,'msg'=>__('获取二维码失败','ziranzhi2')));
    exit;
}

// 搜库资源网 soku.cc   微信购买积分
add_action('wp_ajax_zrz_weixin_credit_pay', 'zrz_weixin_credit_pay');
function zrz_weixin_credit_pay(){
	$rmb = isset($_POST['rmb']) ? $_POST['rmb'] : '';
    $current_url = isset($_POST['current_url']) ? esc_url($_POST['current_url']) : '';
    $js_type = isset($_POST['type']) ? esc_attr($_POST['type']) : '';
	$user_id = get_current_user_id();

    if(!$rmb){
        print json_encode(array('status'=>401,'msg'=>__('请输入金额','ziranzhi2')));
        exit;
    }

    $user_id = get_current_user_id();

	// 搜库资源网 soku.cc   先清除支付信息
    delete_user_meta($user_id,'zrz_ds_resout');
	update_user_meta($user_id,'zrz_ds_resout',array('rmb'=>$rmb));

	$order_id = 'pay_credit-0-'.str_shuffle(uniqid()).'-'.$user_id;

    // 搜库资源网 soku.cc   生成一个临时的订单
    $c_order_id = zrz_build_order_no();
    $ordre = new Zrz_order_Message($user_id,'0',$c_order_id,'',0,1,0,'w',$order_id);
    $resout = $ordre->add_data();

    $resout = zrz_back_qcode($c_order_id,$rmb*100,'[积分购买]', $current_url,$js_type);
    if($resout){
        print json_encode(array('status'=>200,'msg'=>$resout,'user_id'=>$user_id));
        exit;
    }

    print json_encode(array('status'=>401,'msg'=>__('获取二维码失败','ziranzhi2')));
    exit;
}