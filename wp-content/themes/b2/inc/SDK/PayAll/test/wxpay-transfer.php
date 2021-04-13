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
    'partner_trade_no' => '42134122', // 搜库资源网 soku.cc   商户订单号
    'openid'           => 'ol0Q_uJUcrb1DOjmQRycmSpLjRmo', // 搜库资源网 soku.cc   收款人的openid
    'check_name'       => 'NO_CHECK', // 搜库资源网 soku.cc   NO_CHECK：不校验真实姓名\FORCE_CHECK：强校验真实姓名
    // 搜库资源网 soku.cc    're_user_name'     => '张三', // 搜库资源网 soku.cc   check_name为 FORCE_CHECK 校验实名的时候必须提交
    'amount'           => '101', // 搜库资源网 soku.cc   企业付款金额，单位为分
    'desc'             => '帐户提现', // 搜库资源网 soku.cc   付款说明
    'spbill_create_ip' => '192.168.0.1', // 搜库资源网 soku.cc   发起交易的IP地址
];

// 搜库资源网 soku.cc    实例支付对象
$pay = new \Pay\Pay($config);

try {
    $result = $pay->driver('wechat')->gateway('transfer')->apply($options);
    echo '<pre>';
    var_export($result);
} catch (Exception $e) {
    echo $e->getMessage();
}


