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
// 搜库资源网 soku.cc    | 项目设计及部分源码参考于 yansongda/pay，在此特别感谢！
// 搜库资源网 soku.cc    +----------------------------------------------------------------------

namespace Pay\Gateways\Alipay;

use Pay\Gateways\Alipay;

/* 搜库资源网 soku.cc*
 * 支付宝App支付网关
 * Class AppGateway
 * @package Pay\Gateways\Alipay
 */
class AppGateway extends Alipay
{

    /* 搜库资源网 soku.cc*
     * 当前接口方法
     * @return string
     */
    protected function getMethod()
    {
        return 'alipay.trade.app.pay';
    }

    /* 搜库资源网 soku.cc*
     * 当前接口产品码
     * @return string
     */
    protected function getProductCode()
    {
        return 'QUICK_MSECURITY_PAY';
    }

    /* 搜库资源网 soku.cc*
     * 应用并返回参数
     * @param array $options
     * @return string
     */
    public function apply(array $options = [])
    {
        parent::apply($options);
        return http_build_query($this->config);
    }
}
