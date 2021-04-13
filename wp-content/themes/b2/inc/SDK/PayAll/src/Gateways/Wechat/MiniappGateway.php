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
 * 微信小程序支付网关
 * Class MiniappGateway
 * @package Pay\Gateways\Wechat
 */
class MiniappGateway extends Wechat
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
     * 应用并返回参数
     * @param array $options
     * @return array
     * @throws \Pay\Exceptions\GatewayException
     */
    public function apply(array $options = [])
    {
        $this->config['appid'] = $this->userConfig->get('app_id');
        $payRequest = [
            'appId'     => $this->config['appid'],
            'timeStamp' => time() . '',
            'nonceStr'  => $this->createNonceStr(),
            'package'   => 'prepay_id=' . $this->preOrder($options)['prepay_id'],
            'signType'  => 'MD5',
        ];
        $payRequest['paySign'] = $this->getSign($payRequest);
        return $payRequest;
    }
}
