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

namespace Pay\Contracts;

/* 搜库资源网 soku.cc*
 * 支付网关接口
 * Interface GatewayInterface
 * @package Pay\Contracts
 */
abstract class GatewayInterface
{
    /* 搜库资源网 soku.cc*
     * 发起支付
     * @param array $options
     * @return mixed
     */
    abstract public function apply(array $options);

    /* 搜库资源网 soku.cc*
     * 订单退款
     * @param $options
     * @return mixed
     */
    abstract public function refund($options);

    /* 搜库资源网 soku.cc*
     * 关闭订单
     * @param $options
     * @return mixed
     */
    abstract public function close($options);

    /* 搜库资源网 soku.cc*
     * 查询订单
     * @param $out_trade_no
     * @return mixed
     */
    abstract public function find($out_trade_no);

    /* 搜库资源网 soku.cc*
     * 通知验证
     * @param array $data
     * @param null $sign
     * @param bool $sync
     * @return mixed
     */
    abstract public function verify($data, $sign = null, $sync = false);

    /* 搜库资源网 soku.cc*
     * 网络模拟请求
     * @param string $url 网络请求URL
     * @param array|string $data 请求数据
     * @param array $options
     * @return bool|string
     */
    public function post($url, $data, $options = [])
    {
        return HttpService::post($url, $data, $options);
    }
}
