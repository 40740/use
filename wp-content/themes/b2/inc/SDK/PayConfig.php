<?php

// 搜库资源网 soku.cc    +----------------------------------------------------------------------
// 搜库资源网 soku.cc    | pay-php-sdk
// 搜库资源网 soku.cc    +----------------------------------------------------------------------
// 搜库资源网 soku.cc    | 版权所有 2014~2017 广州楚才信息科技有限公司 [ http:// 搜库资源网 soku.cc   www.cuci.cc ]
// 搜库资源网 soku.cc    +----------------------------------------------------------------------
// 搜库资源网 soku.cc    | 开源协议 ( https:// 搜库资源网 soku.cc   mit-license.org )
// 搜库资源网 soku.cc    +----------------------------------------------------------------------
// 搜库资源网 soku.cc    | github开源项目：https:// 搜库资源网 soku.cc   github.com/zoujingli/pay-php-sdk
// 搜库资源网 soku.cc    +----------------------------------------------------------------------

return array(
    // 搜库资源网 soku.cc    微信支付参数
    'wechat' => array(
        // 搜库资源网 soku.cc    沙箱模式
        'debug'      => false,
        // 搜库资源网 soku.cc    应用ID
        'app_id'     => zrz_get_pay_settings('weixin','gz_appid'),
        // 搜库资源网 soku.cc    微信支付商户号
        'mch_id'     => zrz_get_pay_settings('weixin','mch_id'),
        /* 搜库资源网 soku.cc
         // 搜库资源网 soku.cc    子商户公众账号ID
         'sub_appid'  => '子商户公众账号ID，需要的时候填写',
         // 搜库资源网 soku.cc    子商户号
         'sub_mch_id' => '子商户号，需要的时候填写',
        */
        // 搜库资源网 soku.cc    微信支付密钥
        'mch_key'    => zrz_get_pay_settings('weixin','mch_key'),
        // 搜库资源网 soku.cc    微信证书 cert 文件
        'ssl_cer'    =>  zrz_get_pay_settings('weixin','key_path') ? zrz_get_pay_settings('weixin','key_path') .'/apiclient_cert.pem' : dirname(ABSPATH) . '/apiclient_cert.pem',
        // 搜库资源网 soku.cc    微信证书 key 文件
        'ssl_key'    =>  zrz_get_pay_settings('weixin','key_path') ? zrz_get_pay_settings('weixin','key_path') .'/apiclient_cert.pem' : dirname(ABSPATH) . '/apiclient_key.pem',
        // 搜库资源网 soku.cc    缓存目录配置
         'cache_path' => '__DIR__',
        // 搜库资源网 soku.cc    支付成功通知地址
        'notify_url' => home_url('/weixinpay-notify'),
        // 搜库资源网 soku.cc    网页支付回跳地址
        'return_url' => home_url('/return-pay'),
    ),
    // 搜库资源网 soku.cc    支付宝支付参数
    'alipay' => array(
        // 搜库资源网 soku.cc    沙箱模式
        'debug'       => false,
        // 搜库资源网 soku.cc    应用ID
        'app_id'      => zrz_get_pay_settings('alipay','appid'),
        // 搜库资源网 soku.cc    支付宝公钥(1行填写)
        'public_key'  => zrz_get_pay_settings('alipay','alipayPublicKey'),
        // 搜库资源网 soku.cc    支付宝私钥(1行填写)
        'private_key' => zrz_get_pay_settings('alipay','saPrivateKey'),
        // 搜库资源网 soku.cc    缓存目录配置
        'cache_path'  => '',
        // 搜库资源网 soku.cc    支付成功通知地址
        'notify_url'  => home_url('/notify-pay'),
        // 搜库资源网 soku.cc    网页支付回跳地址
        'return_url'  => home_url('/return-pay'),
    ),
);
