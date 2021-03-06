<?php
function zrz_options_pay_page(){

  if( isset($_POST['action']) && sanitize_text_field($_POST['action'])=='update' && wp_verify_nonce( trim($_POST['_wpnonce']), 'check-nonce' ) ) :

    $options = array(
        'alipay'=>array(
            'open_alipay'=>$_POST['open_alipay'],
            'appid'=>trim_value($_POST['zrz_alipay_appid']),// 搜库资源网 soku.cc   支付宝支付应用的appid
            'saPrivateKey'=>trim_value($_POST['zrz_alipay_saPrivateKey']),// 搜库资源网 soku.cc   商户私钥，填写对应签名算法类型的私钥，默认 RSA2 算法
            'alipayPublicKey'=>trim_value($_POST['zrz_alipay_alipayPublicKey']),
            'dangmian'=>$_POST['zrz_alipay_dangmian']
        ),
        'weixin'=>array(
            '_open_weixin'=>$_POST['weixin_open_weixin'],
            'gz_appid'=>trim_value($_POST['weixin_gz_appid']),// 搜库资源网 soku.cc   微信公众号的appid
            'mch_id'=>trim_value($_POST['weixin_mch_id']),// 搜库资源网 soku.cc   微信商户号
            'mch_key'=>trim_value($_POST['weixin_mch_key']),// 搜库资源网 soku.cc   微信支付密钥
            'key_path'=>trim_value($_POST['weixin_key_path']),
        ),
        'weixinpay'=>array(
            'open_weixin'=>$_POST['open_weixin'],
            'mchid'=>trim_value($_POST['zrz_weixinpay_mchid']),
            'key'=>trim_value($_POST['zrz_weixinpay_key']),
        ),
        'xunhu'=>array(
            'open'=>$_POST['open_xunhu'],
            'plugins'=>trim_value($_POST['xunhu_plugins']),
            'appid'=>trim_value($_POST['xunhu_appid']),
            'appsecret'=>trim_value($_POST['xunhu_appsecret'])
        ),
        'youzan'=>array(
            'open'=>$_POST['open_youzan'],
            'client_id'=>trim_value($_POST['youzan_client_id']),
            'client_secret'=>trim_value($_POST['youzan_client_secret']),
            'kdt_id'=>trim_value($_POST['youzan_kdt_id']),
        ),
        'card'=>array(
            'open'=>$_POST['open_card'],
            'html'=>$_POST['card_html']
        ),
        'hupijiao'=>array(
            'hupijiao_wx_open'=>$_POST['hupijiao_wx_open'],
            'hupijiao_wx_appid'=>$_POST['hupijiao_wx_appid'],
            'hupijiao_wx_appsecret'=>$_POST['hupijiao_wx_appsecret'],
            'hupijiao_wx_gateway'=>$_POST['hupijiao_wx_gateway'],
            'hupijiao_alipay_open'=>$_POST['hupijiao_alipay_open'],
            'hupijiao_alipay_appid'=>$_POST['hupijiao_alipay_appid'],
            'hupijiao_alipay_appsecret'=>$_POST['hupijiao_alipay_appsecret'],
            'hupijiao_alipay_gateway'=>$_POST['hupijiao_alipay_gateway'],
        )
    );

    update_option( 'zrz_pay_setting',$options );

    zrz_settings_error('updated');

  endif;

	$option = new zrzOptionsOutput();

	?>
<div class="wrap">

	<h1><?php _e('柒比贰主题设置','ziranzhi2');?></h1>
    <h2 class="title"><?php _e('支付设置','ziranzhi2');?></h2>
	<form method="post">
		<input type="hidden" name="action" value="update">
		<input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo wp_create_nonce( 'check-nonce' );?>">
		<?php zrz_admin_tabs('pay');?>
		<h2 class="title"><?php _e('支付宝设置（官方）','ziranzhi2');?></h2>
        <p>设置之前，请仔细阅读说明，按照步骤来：<a target="_blank" href="https:// 搜库资源网 soku.cc   7b2.com/29685.html">支付宝支付的申请与设置步骤</a></p>
		<?php
    		$option->table(array(
                array(
                    'type' => 'select',
                    'th' => __('是否启用支付宝支付？','zrz'),
                    'key' => 'open_alipay',
                    'value' => array(
                        'default' => array(zrz_get_pay_settings('alipay','open_alipay')),
                        'option' => array(
                            1 => __( '启用', 'zrz' ),
                            0 => __( '关闭', 'zrz' )
                        )
                    )
                ),
                array(
                    'type' => 'select',
                    'th' => __('PC端是否使用当面付','zrz'),
                    'key' => 'zrz_alipay_dangmian',
                    'after' =>'<p class="description">支付宝当面付是以扫码的形式支付，常规形式是新窗口跳转支付</p>',
                    'value' => array(
                        'default' => array(zrz_get_pay_settings('alipay','dangmian')),
                        'option' => array(
                            1 => __( '使用当面付', 'zrz' ),
                            0 => __( '使用常规形式', 'zrz' )
                        )
                    )
                ),
                array(
                    'type' => 'input',
                    'th' => 'APPID',
                    'key' => 'zrz_alipay_appid',
                    'after' =>'<p class="description">打开链接： <code>https:// 搜库资源网 soku.cc   open.alipay.com</code> 账户中心->密钥管理->开放平台密钥，填写添加了电脑网站支付的应用的APPID</p>',
                    'value' => zrz_get_pay_settings('alipay','appid')
                ),
                array(
                    'type' => 'textarea',
                    'th' => '商户私钥',
                    'key' => 'zrz_alipay_saPrivateKey',
                    'after' =>'<p class="description"> 本主题使用的是 <b style="color:red">RSA2</b> 算法生成的私钥。请使用 RSA2 算法来生成。<br>教程：<code>https:// 搜库资源网 soku.cc   docs.open.alipay.com/291/105971</code> 和 <code>https:// 搜库资源网 soku.cc   docs.open.alipay.com/200/105310</code></p>',
                    'value' => zrz_get_pay_settings('alipay','saPrivateKey')
                ),
                array(
                    'type' => 'textarea',
                    'th' => '支付宝公钥',
                    'key' => 'zrz_alipay_alipayPublicKey',
                    'after' =>'<p class="description"> 请在 账户中心->密钥管理->开放平台密钥，找到添加了支付功能的应用，根据你的加密类型，查看<b class="red">支付宝公钥</b>。</p>',
                    'value' => zrz_get_pay_settings('alipay','alipayPublicKey')
                )
            ));
		?>
        <h2 class="title"><?php _e('微信支付（官方）','ziranzhi2');?></h2>
        <p>设置之前，请仔细阅读说明，按照步骤来：<a target="_blank" href="https:// 搜库资源网 soku.cc   7b2.com/29685.html">微信支付的申请与设置步骤</a></p>
		<?php
            $weixin = zrz_get_pay_settings('weixin');
    		$option->table(array(
                array(
                    'type' => 'select',
                    'th' => __('是否启用微信支付？','zrz'),
                    'key' => 'weixin_open_weixin',
                    'value' => array(
                        'default' => array(isset($weixin['_open_weixin']) ? $weixin['_open_weixin'] : 0),
                        'option' => array(
                            1 => __( '启用', 'zrz' ),
                            0 => __( '关闭', 'zrz' )
                        )
                    )
                ),
                array(
                    'type' => 'input',
                    'th' => __('微信公众号的appid','zrz'),
                    'key' => 'weixin_gz_appid',
                    'after' =>'<p class="description"> 如果有公众号，并且开通（关联）了微信支付，请填写。如果没有请留空。</p>',
                    'value' => isset($weixin['gz_appid']) ? $weixin['gz_appid'] : '',
                ),
                array(
                    'type' => 'input',
                    'th' => __('微信支付商户号','zrz'),
                    'key' => 'weixin_mch_id',
                    'value' => isset($weixin['mch_id']) ? $weixin['mch_id'] : '',
                ),
                array(
                    'type' => 'input-password',
                    'th' => '微信支付密钥',
                    'key' => 'weixin_mch_key',
                    'after' =>'<p class="description"> 您使用在线工具生成的32位的密钥</p>',
                    'value' => isset($weixin['mch_key']) ? $weixin['mch_key'] : ''
                ),
                array(
                    'type' => 'input',
                    'th' => '证书存放路径',
                    'key' => 'weixin_key_path',
                    'after' =>'<p class="description">如果需要自定义路径，请将路径填写于此。否则请将证书上传至 <code>'.dirname(ABSPATH).'</code> 目录，并且此项留空。</p>',
                    'value' => isset($weixin['key_path']) ? $weixin['key_path'] : ''
                )
            ));
		?>
        <h2 class="title"><?php _e('payjs(第三方微信支付)','ziranzhi2');?></h2>
        <p style="color:red">微信支付的第三方服务，支持个人支付，申请地址：<a href="https:// 搜库资源网 soku.cc   payjs.cn/ref/DYXQLZ" target="_blank">payjs.cn</a>。</p>
        <?php
    		$option->table(array(
                array(
                    'type' => 'select',
                    'th' => __('是否启用（payjs）微信支付？','zrz'),
                    'key' => 'open_weixin',
                    'value' => array(
                        'default' => array(zrz_get_pay_settings('weixinpay','open_weixin')),
                        'option' => array(
                            1 => __( '启用', 'zrz' ),
                            0 => __( '关闭', 'zrz' )
                        )
                    )
                ),
                array(
                    'type' => 'input',
                    'th' => '商户号',
                    'key' => 'zrz_weixinpay_mchid',
                    'after' =>'<p class="description"> 请登录<code>payjs.cn</code>会员中心查看。</p>',
                    'value' => zrz_get_pay_settings('weixinpay','mchid')
                ),
                array(
                    'type' => 'input-password',
                    'th' => '密钥',
                    'key' => 'zrz_weixinpay_key',
                    'after' =>'<p class="description"> 请登录<code>payjs.cn</code>会员中心查看。</p>',
                    'value' => zrz_get_pay_settings('weixinpay','key')
                )
            ));
		?>

        <h2 class="title"><?php _e('迅虎支付(第三方微信支付宝支付)','ziranzhi2');?></h2>
        <p style="color:red">迅虎网络的第三方集成服务，支持支付宝、微信个人免签，申请地址：<a href="http:// 搜库资源网 soku.cc   mp.wordpressopen.com" target="_blank">迅虎支付</a>。</p>
        <?php
            $xunhu = zrz_get_pay_settings('xunhu');
    		$option->table(array(
                array(
                    'type' => 'select',
                    'th' => __('是否启用迅虎的支付宝和微信支付？','zrz'),
                    'key' => 'open_xunhu',
                    'value' => array(
                        'default' => array(isset($xunhu['open']) ? $xunhu['open'] : 0),
                        'option' => array(
                            1 => __( '启用', 'zrz' ),
                            0 => __( '关闭', 'zrz' )
                        )
                    )
                ),
                array(
                    'type' => 'input',
                    'th' => '渠道ID',
                    'key' => 'xunhu_plugins',
                    'after' =>'<p class="description">根据自己需要命名渠道ID，用来区分不同应用的例如：myswebsite1,mywebsite2。</p>',
                    'value' => isset($xunhu['plugins']) ? $xunhu['plugins'] : ''
                ),
                array(
                    'type' => 'input',
                    'th' => 'APPID',
                    'key' => 'xunhu_appid',
                    'after' =>'<p class="description">应用ID</p>',
                    'value' => isset($xunhu['appid']) ? $xunhu['appid'] : ''
                ),
                array(
                    'type' => 'input-password',
                    'th' => 'APPSECRET',
                    'key' => 'xunhu_appsecret',
                    'after' =>'<p class="description">开发者密码</p>',
                    'value' => isset($xunhu['appsecret']) ? $xunhu['appsecret'] : ''
                ),
            ));
?>
            <h2 class="title"><?php _e('虎皮椒-微信支付','ziranzhi2');?></h2>
        <p style="color:red">虎皮椒支付-微信支付，申请地址：<a href="https:// 搜库资源网 soku.cc   admin.xunhupay.com/sign-up/6474.html" target="_blank">虎皮椒V3</a>。</p>
        <?php
            $hupijiao = zrz_get_pay_settings('hupijiao');
    		$option->table(array(
                array(
                    'type' => 'select',
                    'th' => __('是否启用虎皮椒微信支付','zrz'),
                    'key' => 'hupijiao_wx_open',
                    'value' => array(
                        'default' => array(isset($hupijiao['hupijiao_wx_open']) ? $hupijiao['hupijiao_wx_open'] : 0),
                        'option' => array(
                            1 => __( '启用', 'zrz' ),
                            0 => __( '关闭', 'zrz' )
                        )
                    )
                ),
                array(
                    'type' => 'input',
                    'th' => 'appid',
                    'key' => 'hupijiao_wx_appid',
                    'value' => isset($hupijiao['hupijiao_wx_appid']) ? $hupijiao['hupijiao_wx_appid'] : ''
                ),
                array(
                    'type' => 'input',
                    'th' => 'appsecret',
                    'key' => 'hupijiao_wx_appsecret',
                    'value' => isset($hupijiao['hupijiao_wx_appsecret']) ? $hupijiao['hupijiao_wx_appsecret'] : ''
                ),
                array(
                    'type' => 'input',
                    'th' => '支付网关',
                    'key' => 'hupijiao_wx_gateway',
                    'after' =>'<p class="description">留空则使用默认网关，如无特别升级，请留空</p>',
                    'value' => isset($hupijiao['hupijiao_wx_gateway']) ? $hupijiao['hupijiao_wx_gateway'] : ''
                ),
            ));
            ?>
            <h2 class="title"><?php _e('虎皮椒-支付宝支付','ziranzhi2');?></h2>
        <p style="color:red">虎皮椒支付-支付宝支付，申请地址：<a href="https:// 搜库资源网 soku.cc   admin.xunhupay.com/sign-up/6474.html" target="_blank">虎皮椒V3</a>。</p>
        <?php
            $hupijiao = zrz_get_pay_settings('hupijiao');
    		$option->table(array(
                array(
                    'type' => 'select',
                    'th' => __('是否启用虎皮椒支付宝支付','zrz'),
                    'key' => 'hupijiao_alipay_open',
                    'value' => array(
                        'default' => array(isset($hupijiao['hupijiao_alipay_open']) ? $hupijiao['hupijiao_alipay_open'] : 0),
                        'option' => array(
                            1 => __( '启用', 'zrz' ),
                            0 => __( '关闭', 'zrz' )
                        )
                    )
                ),
                array(
                    'type' => 'input',
                    'th' => 'appid',
                    'key' => 'hupijiao_alipay_appid',
                    'value' => isset($hupijiao['hupijiao_alipay_appid']) ? $hupijiao['hupijiao_alipay_appid'] : ''
                ),
                array(
                    'type' => 'input',
                    'th' => 'appsecret',
                    'key' => 'hupijiao_alipay_appsecret',
                    'value' => isset($hupijiao['hupijiao_alipay_appsecret']) ? $hupijiao['hupijiao_alipay_appsecret'] : ''
                ),
                array(
                    'type' => 'input',
                    'th' => '支付网关',
                    'key' => 'hupijiao_alipay_gateway',
                    'after' =>'<p class="description">留空则使用默认网关，如无特别升级，请留空</p>',
                    'value' => isset($hupijiao['hupijiao_alipay_gateway']) ? $hupijiao['hupijiao_alipay_gateway'] : ''
                ),
            ));
		?>
        <h2 class="title"><?php _e('有赞支付(第三方微信、支付宝支付)','ziranzhi2');?></h2>
        <p style="color:red">有赞支付的第三方集成服务，支持支付宝、微信个人免签，详细教程，请查看：<a href="https:// 搜库资源网 soku.cc   7b2.com/31756.html" target="_blank">有赞支付设置教程</a>。</p>
        <?php
            $youzan = zrz_get_pay_settings('youzan');
    		$option->table(array(
                array(
                    'type' => 'select',
                    'th' => __('是否启用有赞的支付宝和微信支付？','zrz'),
                    'key' => 'open_youzan',
                    'value' => array(
                        'default' => array(isset($youzan['open']) ? $youzan['open'] : 0),
                        'option' => array(
                            1 => __( '启用', 'zrz' ),
                            0 => __( '关闭', 'zrz' )
                        )
                    )
                ),
                array(
                    'type' => 'input',
                    'th' => '应用ID',
                    'key' => 'youzan_client_id',
                    'value' => isset($youzan['client_id']) ? $youzan['client_id'] : ''
                ),
                array(
                    'type' => 'input-password',
                    'th' => '密钥',
                    'key' => 'youzan_client_secret',
                    'value' => isset($youzan['client_secret']) ? $youzan['client_secret'] : ''
                ),
                array(
                    'type' => 'input',
                    'th' => '授权店铺id',
                    'key' => 'youzan_kdt_id',
                    'value' => isset($youzan['kdt_id']) ? $youzan['kdt_id'] : ''
                )
            ));
		?>
        <h2 class="title"><?php _e('是否开启卡密支付','ziranzhi2');?></h2>
        <?php
            $card = zrz_get_pay_settings('card');
            $option->table(array(
                array(
                    'type' => 'select',
                    'th' => __('是否开启卡密支付','zrz'),
                    'key' => 'open_card',
                    'value' => array(
                        'default' => array(isset($card['open']) ? $card['open'] : 0),
                        'option' => array(
                            1 => __( '启用', 'zrz' ),
                            0 => __( '关闭', 'zrz' )
                        )
                    )
                ),
                array(
                    'type' => 'textarea',
                    'th' => '购买卡密的说明文字',
                    'key' => 'card_html',
                    'after' =>'<p class="description">这里设置的字符串将会显示在前端用户菜单财富->充值->卡密充值的选项中，一般是用来告知用户购买卡密地址的。支持 html 标签。</p>',
                    'value' => isset($card['html']) ? zrz_get_html_code($card['html']) : ''
                )
            ));
            if(isset($card['open']) && $card['open'] == 1){
                echo '<a href="'.home_url('/wp-admin/admin.php?page=zrz_options_card').'">前往设置卡密</a>';
            }
        ?>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( '保存更改', 'ziranzhi2' );?>"></p>
	</form>
</div>
	<?php
}
