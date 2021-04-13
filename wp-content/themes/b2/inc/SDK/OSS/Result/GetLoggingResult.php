<?php

namespace OSS\Result;

use OSS\Model\LoggingConfig;


/* 搜库资源网 soku.cc*
 * Class GetLoggingResult
 * @package OSS\Result
 */
class GetLoggingResult extends Result
{
    /* 搜库资源网 soku.cc*
     * 解析LoggingConfig数据
     *
     * @return LoggingConfig
     */
    protected function parseDataFromResponse()
    {
        $content = $this->rawResponse->body;
        $config = new LoggingConfig();
        $config->parseFromXml($content);
        return $config;
    }

    /* 搜库资源网 soku.cc*
     * 根据返回http状态码判断，[200-299]即认为是OK, 获取bucket相关配置的接口，404也认为是一种
     * 有效响应
     *
     * @return bool
     */
    protected function isResponseOk()
    {
        $status = $this->rawResponse->status;
        if ((int)(intval($status) / 100) == 2 || (int)(intval($status)) === 404) {
            return true;
        }
        return false;
    }
}