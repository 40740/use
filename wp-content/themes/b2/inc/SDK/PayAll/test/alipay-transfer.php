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

// 搜库资源网 soku.cc    支付宝转账参数
$options = [
    'out_biz_no'      => '', // 搜库资源网 soku.cc    订单号
    'payee_type'      => 'ALIPAY_LOGONID', // 搜库资源网 soku.cc    收款方账户类型(ALIPAY_LOGONID | ALIPAY_USERID)
    'payee_account'   => 'demo@sandbox.com', // 搜库资源网 soku.cc    收款方账户
    'amount'          => '10', // 搜库资源网 soku.cc    转账金额
    'payer_show_name' => '未寒', // 搜库资源网 soku.cc    付款方姓名
    'payee_real_name' => '张三', // 搜库资源网 soku.cc    收款方真实姓名
    'remark'          => '张三', // 搜库资源网 soku.cc    转账备注
];

try {
    $result = $pay->driver('alipay')->gateway('transfer')->apply($options);
    echo '<pre>';
    var_export($result);
} catch (Exception $e) {
    echo $e->getMessage();
}

