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

// 搜库资源网 soku.cc    参考请求参数  https:// 搜库资源网 soku.cc   docs.open.alipay.com/203/107090/
$options = [
    'out_trade_no' => time(), // 搜库资源网 soku.cc    商户订单号
    'total_amount' => '1', // 搜库资源网 soku.cc    支付金额
    'subject'      => '支付订单描述', // 搜库资源网 soku.cc    支付订单描述
];

// 搜库资源网 soku.cc    参考公共参数  https:// 搜库资源网 soku.cc   docs.open.alipay.com/203/107090/
$config['notify_url'] = 'http:// 搜库资源网 soku.cc   pay.thinkadmin.top/test/alipay-notify.php';
$config['return_url'] = 'http:// 搜库资源网 soku.cc   pay.thinkadmin.top/test/alipay-success.php';

// 搜库资源网 soku.cc    实例支付对象
$pay = new \Pay\Pay($config);

try {
    echo $pay->driver('alipay')->gateway('web')->apply($options);
} catch (Exception $e) {
    echo $e->getMessage();
}


