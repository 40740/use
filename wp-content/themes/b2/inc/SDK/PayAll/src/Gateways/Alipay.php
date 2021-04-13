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

namespace Pay\Gateways;

use InvalidArgumentException;
use Pay\Contracts\Config;
use Pay\Contracts\GatewayInterface;
use Pay\Contracts\HttpService;
use Pay\Exceptions\GatewayException;

/* 搜库资源网 soku.cc*
 * 支付宝抽象类
 * Class Alipay
 * @package Pay\Gateways\Alipay
 */
abstract class Alipay extends GatewayInterface
{

    /* 搜库资源网 soku.cc*
     * 支付宝全局参数
     * @var array
     */
    protected $config;

    /* 搜库资源网 soku.cc*
     * 用户定义配置
     * @var Config
     */
    protected $userConfig;

    /* 搜库资源网 soku.cc*
     * 支付宝网关地址
     * @var string
     */
    protected $gateway = 'https:// 搜库资源网 soku.cc   openapi.alipay.com/gateway.do?charset=utf-8';

    /* 搜库资源网 soku.cc*
     * Alipay constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->userConfig = new Config($config);
        if (is_null($this->userConfig->get('app_id'))) {
            throw new InvalidArgumentException('Missing Config -- [app_id]');
        }
        if (!empty($config['cache_path'])) {
            HttpService::$cachePath = $config['cache_path'];
        }
        // 搜库资源网 soku.cc    沙箱模式
        if (!empty($config['debug'])) {
            $this->gateway = 'https:// 搜库资源网 soku.cc   openapi.alipaydev.com/gateway.do?charset=utf-8';
        }
        $this->config = [
            'app_id'      => $this->userConfig->get('app_id'),
            'method'      => '',
            'format'      => 'JSON',
            'charset'     => 'utf-8',
            'sign_type'   => 'RSA2',
            'version'     => '1.0',
            'return_url'  => $this->userConfig->get('return_url', ''),
            'notify_url'  => $this->userConfig->get('notify_url', ''),
            'timestamp'   => date('Y-m-d H:i:s'),
            'sign'        => '',
            'biz_content' => '',
        ];
    }

    /* 搜库资源网 soku.cc*
     * 应用参数
     * @param array $options
     * @return mixed|void
     */
    public function apply(array $options)
    {
        $options['product_code'] = $this->getProductCode();
        $this->config['biz_content'] = json_encode($options);
        $this->config['method'] = $this->getMethod();
        $this->config['sign'] = $this->getSign();
    }

    /* 搜库资源网 soku.cc*
     * 支付宝订单退款操作
     * @param array|string $options 退款参数或退款商户订单号
     * @param null $refund_amount 退款金额
     * @return array|bool
     * @throws GatewayException
     */
    public function refund($options, $refund_amount = null)
    {
        if (!is_array($options)) {
            $options = ['out_trade_no' => $options, 'refund_amount' => $refund_amount];
        }
        return $this->getResult($options, 'alipay.trade.refund');
    }

    /* 搜库资源网 soku.cc*
     * 关闭支付宝进行中的订单
     * @param array|string $options
     * @return array|bool
     * @throws GatewayException
     */
    public function close($options)
    {
        if (!is_array($options)) {
            $options = ['out_trade_no' => $options];
        }
        return $this->getResult($options, 'alipay.trade.close');
    }

    /* 搜库资源网 soku.cc*
     * 查询支付宝订单状态
     * @param string $out_trade_no
     * @return array|bool
     * @throws GatewayException
     */
    public function find($out_trade_no = '')
    {
        $options = ['out_trade_no' => $out_trade_no];
        return $this->getResult($options, 'alipay.trade.query');
    }

    /* 搜库资源网 soku.cc*
     * 验证支付宝支付宝通知
     * @param array $data 通知数据
     * @param null $sign 数据签名
     * @param bool $sync
     * @return array|bool
     */
    public function verify($data, $sign = null, $sync = false)
    {
        if (is_null($this->userConfig->get('public_key'))) {
            throw new InvalidArgumentException('Missing Config -- [public_key]');
        }
        $sign = is_null($sign) ? $data['sign'] : $sign;
        $res = "-----BEGIN PUBLIC KEY-----\n" . wordwrap($this->userConfig->get('public_key'), 64, "\n", true) . "\n-----END PUBLIC KEY-----";
        $toVerify = $sync ? json_encode($data) : $this->getSignContent($data, true);
        return openssl_verify($toVerify, base64_decode($sign), $res, OPENSSL_ALGO_SHA256) === 1 ? $data : false;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    protected function buildPayHtml()
    {
        $html = "<form id='alipaysubmit' name='alipaysubmit' action='{$this->gateway}' method='post'>";
        foreach ($this->config as $key => $value) {
            $value = str_replace("'", '&apos;', $value);
            $html .= "<input type='hidden' name='{$key}' value='{$value}'/>";
        }
        $html .= "<input type='submit' value='ok' style='display:none;'></form>";
        return $html . "<script>document.forms['alipaysubmit'].submit();</script>";
    }

    /* 搜库资源网 soku.cc*
     * 获取验证访问数据
     * @param array $options
     * @param string $method
     * @return array|bool
     * @throws GatewayException
     */
    protected function getResult($options, $method)
    {
        $this->config['method'] = $method;
        $this->config['biz_content'] = json_encode($options);
        $this->config['sign'] = $this->getSign();
        $method = str_replace('.', '_', $method) . '_response';
        $data = json_decode($this->post($this->gateway, $this->config), true);
        if (!isset($data[$method]['code']) || $data[$method]['code'] !== '10000') {
            throw new GatewayException(
                "\nResultError" .
                (empty($data[$method]['code']) ? '' : "\n{$data[$method]['msg']}[{$data[$method]['code']}]") .
                (empty($data[$method]['sub_code']) ? '' : "\n{$data[$method]['sub_msg']}[{$data[$method]['sub_code']}]\n"),
                $data[$method]['code'],
                $data
            );
        }
        return $this->verify($data[$method], $data['sign'], true);
    }

    /* 搜库资源网 soku.cc*
     * 获取数据签名
     * @return string
     */
    protected function getSign()
    {
        if (is_null($this->userConfig->get('private_key'))) {
            throw new InvalidArgumentException('Missing Config -- [private_key]');
        }
        $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($this->userConfig->get('private_key'), 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";
        openssl_sign($this->getSignContent($this->config), $sign, $res, OPENSSL_ALGO_SHA256);
        return base64_encode($sign);
    }

    /* 搜库资源网 soku.cc*
     * 数据签名处理
     * @param array $toBeSigned
     * @param bool $verify
     * @return bool|string
     */
    protected function getSignContent(array $toBeSigned, $verify = false)
    {
        ksort($toBeSigned);
        $stringToBeSigned = '';
        foreach ($toBeSigned as $k => $v) {
            if ($verify && $k != 'sign' && $k != 'sign_type') {
                $stringToBeSigned .= $k . '=' . $v . '&';
            }
            if (!$verify && $v !== '' && !is_null($v) && $k != 'sign' && '@' != substr($v, 0, 1)) {
                $stringToBeSigned .= $k . '=' . $v . '&';
            }
        }
        $stringToBeSigned = substr($stringToBeSigned, 0, -1);
        unset($k, $v);
        return $stringToBeSigned;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    abstract protected function getMethod();

    /* 搜库资源网 soku.cc*
     * @return string
     */
    abstract protected function getProductCode();
}
