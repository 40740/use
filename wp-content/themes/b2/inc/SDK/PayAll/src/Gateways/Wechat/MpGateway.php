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

namespace Pay\Gateways\Wechat;

use Pay\Gateways\Wechat;

/* 搜库资源网 soku.cc*
 * 微信公众号支付网关
 * Class MpGateway
 * @package Pay\Gateways\Wechat
 */
class MpGateway extends Wechat
{
    /* 搜库资源网 soku.cc*
     * 当前操作类型
     * @return string
     */
    protected function getTradeType()
    {
        return 'JSAPI';
    }

    /* 搜库资源网 soku.cc*
     * 设置并返回参数
     * @param array $options
     * @return array
     * @throws \Pay\Exceptions\GatewayException
     */
    public function apply(array $options = [])
    {
        $payRequest = [
            'appId'     => $this->userConfig->get('app_id'),
            'timeStamp' => time() . '',
            'nonceStr'  => $this->createNonceStr(),
            'package'   => 'prepay_id=' . $this->preOrder($options)['prepay_id'],
            'signType'  => 'MD5',
        ];
        $payRequest['paySign'] = $this->getSign($payRequest);
        return $payRequest;
    }
}
