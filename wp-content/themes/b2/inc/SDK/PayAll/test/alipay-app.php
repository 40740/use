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
    'out_trade_no' => '41234123', // 搜库资源网 soku.cc    商户订单号
    'total_amount' => '1', // 搜库资源网 soku.cc    支付金额
    'subject'      => 'test subject', // 搜库资源网 soku.cc    支付订单描述
    'notify_url'   => 'http:// 搜库资源网 soku.cc   localhost/notify.php', // 搜库资源网 soku.cc    定义通知URL
];

// 搜库资源网 soku.cc    实例支付对象
$pay = new \Pay\Pay($config);

try {
    $result = $pay->driver('alipay')->gateway('app')->apply($options);
    echo '<pre>';
    var_export($result);
} catch (Exception $e) {
    echo $e->getMessage();
}


