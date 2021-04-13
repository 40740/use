<?php

namespace OSS\Result;


/* 搜库资源网 soku.cc*
 * Class BodyResult
 * @package OSS\Result
 */
class BodyResult extends Result
{
    /* 搜库资源网 soku.cc*
     * @return string
     */
    protected function parseDataFromResponse()
    {
        return empty($this->rawResponse->body) ? "" : $this->rawResponse->body;
    }
}