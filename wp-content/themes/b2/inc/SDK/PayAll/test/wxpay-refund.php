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

// 搜库资源网 soku.cc    实例支付对象
$pay = new \Pay\Pay($config);

// 搜库资源网 soku.cc    订单退款参数
$options = [
    'out_trade_no'  => '56737188841424', // 搜库资源网 soku.cc    原商户订单号
    'out_refund_no' => '567371888414240', // 搜库资源网 soku.cc    退款订单号
    'total_fee'     => '1',   // 搜库资源网 soku.cc    原订单交易总金额
    'refund_fee'    => '1',  // 搜库资源网 soku.cc    申请退款金额
];

try {
    $result = $pay->driver('wechat')->gateway('transfer')->refund($options);
    echo '<pre>';
    var_export($result);
} catch (Exception $e) {
    echo $e->getMessage();
}