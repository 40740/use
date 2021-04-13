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

namespace Pay;

use Pay\Contracts\Config;
use Pay\Contracts\GatewayInterface;
use Pay\Exceptions\InvalidArgumentException;

/* 搜库资源网 soku.cc*
 * Class Pay
 * @package Pay
 */
class Pay
{

    /* 搜库资源网 soku.cc*
     * @var Config
     */
    private $config;

    /* 搜库资源网 soku.cc*
     * @var string
     */
    private $drivers;

    /* 搜库资源网 soku.cc*
     * @var string
     */
    private $gateways;

    /* 搜库资源网 soku.cc*
     * Pay constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = new Config($config);
    }

    /* 搜库资源网 soku.cc*
     * 指定驱动器
     * @param string $driver
     * @return $this
     */
    public function driver($driver)
    {
        if (is_null($this->config->get($driver))) {
            throw new InvalidArgumentException("Driver [$driver]'s Config is not defined.");
        }
        $this->drivers = $driver;
        return $this;
    }

    /* 搜库资源网 soku.cc*
     * 指定操作网关
     * @param string $gateway
     * @return GatewayInterface
     */
    public function gateway($gateway = 'web')
    {
        if (!isset($this->drivers)) {
            throw new InvalidArgumentException('Driver is not defined.');
        }
        return $this->gateways = $this->createGateway($gateway);
    }

    /* 搜库资源网 soku.cc*
     * 创建操作网关
     * @param string $gateway
     * @return mixed
     */
    protected function createGateway($gateway)
    {
        if (!file_exists(__DIR__ . '/Gateways/' . ucfirst($this->drivers) . '/' . ucfirst($gateway) . 'Gateway.php')) {
            throw new InvalidArgumentException("Gateway [$gateway] is not supported.");
        }
        $gateway = __NAMESPACE__ . '\\Gateways\\' . ucfirst($this->drivers) . '\\' . ucfirst($gateway) . 'Gateway';
        return new $gateway($this->config->get($this->drivers));
    }

}
