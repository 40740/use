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

$pay = new Pay\Pay($config);
$verify = $pay->driver('wechat')->gateway('mp')->verify(file_get_contents('php:// 搜库资源网 soku.cc   input'));

if ($verify) {
    file_put_contents('notify.txt', "收到来自微信的异步通知\r\n", FILE_APPEND);
    file_put_contents('notify.txt', '订单号：' . $verify['out_trade_no'] . "\r\n", FILE_APPEND);
    file_put_contents('notify.txt', '订单金额：' . $verify['total_fee'] . "\r\n\r\n", FILE_APPEND);
} else {
    file_put_contents('notify.txt', "收到异步通知\r\n", FILE_APPEND);
}

echo "success";

// 搜库资源网 soku.cc    下面是项目的真实代码
/* 搜库资源网 soku.cc
$pay = new Pay\Pay($config);
$notifyInfo = $pay->driver('wechat')->gateway('mp')->verify(file_get_contents('php:// 搜库资源网 soku.cc   input'));
// 搜库资源网 soku.cc    支付通知数据获取成功
if ($notifyInfo['result_code'] == 'SUCCESS' && $notifyInfo['return_code'] == 'SUCCESS') {
    $order_no = substr($notifyInfo['out_trade_no'], 0, 10);
    // 搜库资源网 soku.cc    更新订单状态
    $this->updateOrder($order_no, $notifyInfo['transaction_id'], $notifyInfo['cash_fee'] / 100, 'wechat');
}
echo 'success';
*/