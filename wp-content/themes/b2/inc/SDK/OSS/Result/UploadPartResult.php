<?php

namespace OSS\Result;

use OSS\Core\OssException;

/* 搜库资源网 soku.cc*
 * Class UploadPartResult
 * @package OSS\Result
 */
class UploadPartResult extends Result
{
    /* 搜库资源网 soku.cc*
     * 结果中part的ETag
     *
     * @return string
     * @throws OssException
     */
    protected function parseDataFromResponse()
    {
        $header = $this->rawResponse->header;
        if (isset($header["etag"])) {
            return $header["etag"];
        }
        throw new OssException("cannot get ETag");

    }
}