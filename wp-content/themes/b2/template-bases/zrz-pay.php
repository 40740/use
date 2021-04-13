<?php
// 搜库资源网 soku.cc   支付
get_header();

// 搜库资源网 soku.cc   数据
$data = isset($_GET['data']) ? $_GET['data'] : '';

// 搜库资源网 soku.cc   支付方式
$pay_type = isset($_GET['pay_type']) ? $_GET['pay_type'] : '';

// 搜库资源网 soku.cc   支付内容
$type = isset($_GET['type']) ? $_GET['type'] : '';

$user_id = get_current_user_id();

$msg = '';
$toatl_price = 0;
$id_arr = array();
$title = '';
$i = 0;
if(!is_user_logged_in()){
    $msg .='<p>老铁，请登陆。！( ͡° ͜ʖ ͡°)</p>';
}
$order_id = '';
// 搜库资源网 soku.cc   付费活动报名
if($type == 'activity'){
    $post_id = isset($data['post_id']) ? $data['post_id'] : '';
    $name = isset($data['name']) ? $data['name'] : '';
    $number = isset($data['number']) ? $data['number'] : '';
    $sex = isset($data['sex']) ? $data['sex'] : '';
    $more = isset($data['more']) ? $data['more'] : '';

    if(!$post_id || !$name || !$number || !$sex){
        $msg .='<p>您提交的信息不全</p>'; 
    }

    if(get_post_type($post_id) != 'activity'){
        $msg .='<p>参数错误</p>'; 
    }

    $toatl_price = get_post_meta($post_id,'zrz_activity_role',true);
    $toatl_price = $toatl_price['key'] == 'rmb' ? $toatl_price['rmb'] : 0;
    
    // 搜库资源网 soku.cc   先清除支付信息
    delete_user_meta($user_id,'zrz_ds_resout');
	update_user_meta($user_id,'zrz_ds_resout',$data);

    $order_id = 'pay_activity-'.$post_id.'-'.str_shuffle(uniqid()).'-'.$user_id;

    $title = '活动付费';

    // 搜库资源网 soku.cc   会员购买
}elseif($type == 'vip'){
	$order_id = 'pay_vip-0-'.str_shuffle(uniqid()).'-'.$user_id;
	$lv_setting = zrz_get_lv_settings($data);
	$toatl_price = $lv_setting['price'];

	// 搜库资源网 soku.cc   先清除支付信息
    delete_user_meta($user_id,'zrz_ds_resout');
	update_user_meta($user_id,'zrz_ds_resout',array('vip'=>$data));

	$title = '购买'.$lv_setting['name'];
	// 搜库资源网 soku.cc   打赏
}elseif($type == 'ds'){

    if(!isset($data['price']) || !isset($data['post_id'])){
        $msg .= '数据不完整';
    }

    $toatl_price = $data['price'];
    $author_id = get_post_field('post_author',$data['post_id']);

    // 搜库资源网 soku.cc   先清除打赏信息
    delete_user_meta($user_id,'zrz_ds_resout');

    $text = isset($data['text']) ? $data['text'] : 0;

    // 搜库资源网 soku.cc   记录留言信息
    update_user_meta($user_id,'zrz_ds_resout',array('text'=>$text));

    if($toatl_price <= 0){
        $msg .='<p>输入的金额错误，请重试</p>';
    }

    $order_id = 'pay_ds-'.$data['post_id'].'-'.str_shuffle(uniqid()).'-'.$user_id;
    $title = '给 ['.get_the_author_meta('display_name',$author_id).'] 打赏';

    // 搜库资源网 soku.cc   付费阅读
}elseif($type == 'post'){

    // 搜库资源网 soku.cc   先付费阅读信息打赏信息
    delete_user_meta($user_id,'zrz_ds_resout');

    // 搜库资源网 soku.cc   检查文章所需的金额
    $cap = get_post_meta($data,'capabilities',true);

    // 搜库资源网 soku.cc   检查文章类型
    if(isset($cap['key']) && isset($cap['val']) && $cap['key'] == 'rmb'){
        $toatl_price = $cap['val'];
        $title = get_the_title($data);
        $title = mb_strimwidth(zrz_clear_code(strip_tags(strip_shortcodes($title))), 0, 400,'...').' [付费阅读]';
        // 搜库资源网 soku.cc   支付订单号
        $id = array(
            'id'=>$data,
            'count'=>1
        );
        array_push($id_arr,$id);
    }else{
        $msg .= '<p>商品类型错误！</p>';
    }
    $order_id = 'pay_post-'.$data.'-'.str_shuffle(uniqid()).'-'.$user_id;
    // 搜库资源网 soku.cc   会员充值
}elseif($type == 'cz'){
    // 搜库资源网 soku.cc   先清除充值信息
    delete_user_meta($user_id,'zrz_ds_resout');

    $order_id = 'pay_cz-1-'.str_shuffle(uniqid()).'-'.$user_id;

    // 搜库资源网 soku.cc   记录充值信息
    update_user_meta($user_id,'zrz_ds_resout',array('text'=>'cz'));

    $title = '给会员['.get_the_author_meta('display_name',$user_id).']充值';
    $toatl_price =  $data;
    // 搜库资源网 soku.cc   积分购买
}elseif($type == 'gm'){

    $order_id = 'pay_credit-0-'.str_shuffle(uniqid()).'-'.$user_id;

	// 搜库资源网 soku.cc   先清除支付信息
    delete_user_meta($user_id,'zrz_ds_resout');
	update_user_meta($user_id,'zrz_ds_resout',array('rmb'=>$data));

	$title = '购买积分';
    $toatl_price =  $data;
    // 搜库资源网 soku.cc   商品购买
}elseif($type == 'shop'){
    /* 搜库资源网 soku.cc
    * ------------------------------------------------------ 商品支付 --------------------------------------------------
    */
    // 搜库资源网 soku.cc   先付费阅读信息打赏信息
    delete_user_meta($user_id,'zrz_ds_resout');
    // 搜库资源网 soku.cc   订单检查
    $order_id = 'pay_shop-1-'.str_shuffle(uniqid()).'-'.$user_id;
    foreach ($data as $value) {
        $i++;
        $post_id = number($value['pid']);

        // 搜库资源网 soku.cc   检查商品类型
        $_type = get_post_meta($post_id, 'zrz_shop_type', true);
        if($_type != 'normal'){
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
    $title = mb_strimwidth(zrz_clear_code(strip_tags(strip_shortcodes($title))), 0, 400,'...');
}


/* 搜库资源网 soku.cc
* -----------------------------------------------------------------------------------------------------------------------
*/

// 搜库资源网 soku.cc   支付金额
if(!$toatl_price || $toatl_price <= 0){
    $msg .= '<p>支付金额错误，请联系管理员。</p>';
}

?>
<div id="primary" class="content-area mar10-b" style="width:100%">
    <main id="pay" class="site-main pay-page box pos-r" style="min-height:400px">
        <?php
                if($msg){
                    echo '<div class="lm t-c">'.$msg.'</div>';
                }else{
                $c_order_id = zrz_build_order_no();
                $order_content = isset($_GET['orderContent']) ? esc_sql(sanitize_text_field($_GET['orderContent'])) : '';
                // 搜库资源网 soku.cc   生成一个临时的订单
                $ordre = new Zrz_order_Message($user_id,'0',$c_order_id,'',0,1,0,'w',$order_id,'',$order_content);
                $resout = $ordre->add_data();

                if(!$resout){
                    $msg = '订单生成失败！';
                }
                if($type == 'post' || $type == 'shop'){
                    // 搜库资源网 soku.cc   保存订单信息
                    delete_user_meta($user_id,'zrz_orders');
                    $user_order = array(
                        'order'=>$order_id,
                        'ids'=>$id_arr,// 搜库资源网 soku.cc   商品ID
                        'total_price'=>$toatl_price,// 搜库资源网 soku.cc   支付金额
                        'balance'=>0,// 搜库资源网 soku.cc   是否使用余额
                        'payed'=>0,
                        'type'=>$type
                    );
                    // 搜库资源网 soku.cc   先付费阅读信息打赏信息
                    update_user_meta($user_id,'zrz_ds_resout','begin');

                    // 搜库资源网 soku.cc   设置一个临时数据，回调的时候检查
                    update_user_meta($user_id,'zrz_orders',$user_order);
                }

                echo '<div class="lm t-c" style="font-size:28px">跳转中...</div>';

                if(wp_is_mobile()){
                    $type = 'wap';
                }else{
                    $type = 'web';
                }

                $open_alipay = zrz_alipay_type();

                // 搜库资源网 soku.cc   支付宝官方支付
                if($open_alipay == 'alipay' && $pay_type != 'weixin'){
                    require ZRZ_THEME_DIR . '/inc/SDK/PayAll/init.php';
                    // 搜库资源网 soku.cc    加载配置参数
                    $config = require ZRZ_THEME_DIR . '/inc/SDK/PayConfig.php';

                    // 搜库资源网 soku.cc    参考请求参数  https:// 搜库资源网 soku.cc   docs.open.alipay.com/203/107090/
                    $options = array(
                        'out_trade_no' => $c_order_id, // 搜库资源网 soku.cc    商户订单号
                        'total_amount' => $toatl_price, // 搜库资源网 soku.cc    支付金额
                        'subject'      => $title, // 搜库资源网 soku.cc    支付订单描述
                    );

                    $pay = new \Pay\Pay($config);

                    try {
                        echo $pay->driver('alipay')->gateway($type)->apply($options);
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                }else{
                    $xunhu = zrz_get_pay_settings('xunhu');

                    require ZRZ_THEME_DIR.'/inc/SDK/Xunhu/api.php';

                    $appid = isset($xunhu['appid']) ? $xunhu['appid'] : '';
                    $appsecret = isset($xunhu['appsecret']) ? $xunhu['appsecret'] : '';
                    $my_plugin_id = isset($xunhu['plugins']) ? $xunhu['plugins'] : '';

                    $payment = 'alipay';
                    $url              = 'https:// 搜库资源网 soku.cc   pay.wordpressopen.com/payment/do.html';

                    if($pay_type == 'weixin'){
                        $payment = 'wechat';

                        $hupijiao = zrz_get_pay_settings('hupijiao');
                        if(isset($hupijiao['hupijiao_wx_open']) && $hupijiao['hupijiao_wx_open'] == 1){
                            $appid = isset($hupijiao['hupijiao_wx_appid']) ? $hupijiao['hupijiao_wx_appid'] : '';
                            $appsecret = isset($hupijiao['hupijiao_wx_appsecret']) ? $hupijiao['hupijiao_wx_appsecret'] : '';
                            $url = isset($hupijiao['hupijiao_wx_gateway']) && $hupijiao['hupijiao_wx_gateway'] ? $hupijiao['hupijiao_wx_gateway'] : 'https:// 搜库资源网 soku.cc   api.xunhupay.com/payment/do.html';
                            $payment = 'alipay';
                        }

                    }

                    if($pay_type == 'alipay'){
                        $payment = 'alipay';

                        $hupijiao = zrz_get_pay_settings('hupijiao');
                        if(isset($hupijiao['hupijiao_alipay_open']) && $hupijiao['hupijiao_alipay_open'] == 1){
                            $appid = isset($hupijiao['hupijiao_alipay_appid']) ? $hupijiao['hupijiao_alipay_appid'] : '';
                            $appsecret = isset($hupijiao['hupijiao_alipay_appsecret']) ? $hupijiao['hupijiao_alipay_appsecret'] : '';
                            $url = isset($hupijiao['hupijiao_alipay_gateway']) && $hupijiao['hupijiao_alipay_gateway'] ? $hupijiao['hupijiao_alipay_gateway'] : 'https:// 搜库资源网 soku.cc   api.xunhupay.com/payment/do.html';
                            $payment = 'alipay';
                        }

                    }
         
                    if(!$appid || !$appsecret || !$payment) {
                        echo '请完成迅虎支付的各设置项';
                    }else{
                        $_data=array(
                            'version'   => '1.1',// 搜库资源网 soku.cc   固定值，api 版本，目前暂时是1.1
                            'lang'       => 'zh-cn', // 搜库资源网 soku.cc   必须的，zh-cn或en-us 或其他，根据语言显示页面
                            'plugins'   => $my_plugin_id,// 搜库资源网 soku.cc   必须的，根据自己需要自定义插件ID，唯一的，匹配[a-zA-Z\d\-_]+
                            'appid'     => $appid, // 搜库资源网 soku.cc   必须的，APPID
                            'trade_order_id'=> $c_order_id, // 搜库资源网 soku.cc   必须的，网站订单ID，唯一的，匹配[a-zA-Z\d\-_]+
                            'payment'   => $payment,// 搜库资源网 soku.cc   必须的，支付接口标识：wechat(微信接口)|alipay(支付宝接口)
                            'total_fee' => round($toatl_price, 2),// 搜库资源网 soku.cc   人民币，单位精确到分(测试账户只支持0.1元内付款)
                            'title'     => $title, // 搜库资源网 soku.cc   必须的，订单标题，长度32或以内
                            'time'      => time(),// 搜库资源网 soku.cc   必须的，当前时间戳，根据此字段判断订单请求是否已超时，防止第三方攻击服务器
                            'notify_url'=>  home_url('/notify-pay'), // 搜库资源网 soku.cc   必须的，支付成功异步回调接口
                            'return_url'=> home_url('/xunhu-success'),// 搜库资源网 soku.cc   必须的，支付成功后的跳转地址
                            'callback_url'=> home_url('/xunhu-error'),// 搜库资源网 soku.cc   必须的，支付发起地址（未支付或支付失败，系统会会跳到这个地址让用户修改支付信息）
                            'nonce_str' => str_shuffle(time())// 搜库资源网 soku.cc   必须的，随机字符串，作用：1.避免服务器缓存，2.防止安全密钥被猜测出来
                        );

                        $hashkey =$appsecret;
                        $_data['hash']     = XH_Payment_Api::generate_xh_hash($_data,$hashkey);

                        try {
                            $response     = XH_Payment_Api::http_post($url, json_encode($_data));

                            $result       = $response ? json_decode($response,true) : null;
                            if(!$result){
                                throw new Exception('Internal server error',500);
                            }

                            $hash         = XH_Payment_Api::generate_xh_hash($result,$hashkey);
                            if(!isset( $result['hash'])|| $hash!=$result['hash']){
                                throw new Exception(__('Invalid sign!',XH_Wechat_Payment),40029);
                            }

                            if($result['errcode']!=0){
                                throw new Exception($result['errmsg'],$result['errcode']);
                            }

                            $pay_url =$result['url'];
                            header("Location: $pay_url");
                            exit;
                        } catch (Exception $e) {
                            echo "errcode:{$e->getCode()},errmsg:{$e->getMessage()}";
                            // 搜库资源网 soku.cc   TODO:处理支付调用异常的情况
                        }
                    }
                }
            }
        ?>
    </main>
</div>
<?php
get_footer();
