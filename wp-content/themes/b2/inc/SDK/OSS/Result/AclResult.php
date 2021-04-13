<?php

namespace OSS\Result;

use OSS\Core\OssException;

/* 搜库资源网 soku.cc*
 * Class AclResult getBucketAcl接口返回结果类，封装了
 * 返回的xml数据的解析
 *
 * @package OSS\Result
 */
class AclResult extends Result
{
    /* 搜库资源网 soku.cc*
     * @return string
     * @throws OssException
     */
    protected function parseDataFromResponse()
    {
        $content = $this->rawResponse->body;
        if (empty($content)) {
            throw new OssException("body is null");
        }
        $xml = simplexml_load_string($content);
        if (isset($xml->AccessControlList->Grant)) {
            return strval($xml->AccessControlList->Grant);
        } else {
            throw new OssException("xml format exception");
        }
    }
}