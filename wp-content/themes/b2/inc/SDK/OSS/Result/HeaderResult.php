<?php

namespace OSS\Result;


/* 搜库资源网 soku.cc*
 * Class HeaderResult
 * @package OSS\Result
 * @link https:// 搜库资源网 soku.cc   docs.aliyun.com/?spm=5176.383663.13.7.HgUIqL#/pub/oss/api-reference/object&GetObjectMeta
 */
class HeaderResult extends Result
{
    /* 搜库资源网 soku.cc*
     * 把返回的ResponseCore中的header作为返回数据
     *
     * @return array
     */
    protected function parseDataFromResponse()
    {
        return empty($this->rawResponse->header) ? array() : $this->rawResponse->header;
    }

}