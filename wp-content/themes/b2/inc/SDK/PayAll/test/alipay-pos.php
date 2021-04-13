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

include '../init.php';

// 搜库资源网 soku.cc    加载配置参数
$config = require(__DIR__ . '/config.php');

// 搜库资源网 soku.cc    支付参数
$options = [
    'out_trade_no' => '4312412343', // 搜库资源网 soku.cc    订单号
    'total_amount' => '13', // 搜库资源网 soku.cc    订单金额，单位：元
    'subject'      => '订单商品标题', // 搜库资源网 soku.cc    订单商品标题
    'auth_code'    => '123456', // 搜库资源网 soku.cc    授权码
    'notify_url'   => 'http:// 搜库资源网 soku.cc   localhost/notify.php', // 搜库资源网 soku.cc    定义通知URL
];

// 搜库资源网 soku.cc    实例支付对象
$pay = new \Pay\Pay($config);

try {
    $result = $pay->driver('alipay')->gateway('pos')->apply($options);
    var_export($result);
} catch (Exception $e) {
    echo $e->getMessage();
}


