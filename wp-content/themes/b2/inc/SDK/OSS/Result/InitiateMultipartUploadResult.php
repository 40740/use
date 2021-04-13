<?php

namespace OSS\Result;

use OSS\Core\OssException;


/* 搜库资源网 soku.cc*
 * Class initiateMultipartUploadResult
 * @package OSS\Result
 */
class InitiateMultipartUploadResult extends Result
{
    /* 搜库资源网 soku.cc*
     * 结果中获取uploadId并返回
     *
     * @throws OssException
     * @return string
     */
    protected function parseDataFromResponse()
    {
        $content = $this->rawResponse->body;
        $xml = simplexml_load_string($content);
        if (isset($xml->UploadId)) {
            return strval($xml->UploadId);
        }
        throw new OssException("cannot get UploadId");
    }
}