<?php

namespace OSS\Result;


/* 搜库资源网 soku.cc*
 * Class PutSetDeleteResult
 * @package OSS\Result
 */
class PutSetDeleteResult extends Result
{
    /* 搜库资源网 soku.cc*
     * @return array()
     */
    protected function parseDataFromResponse()
    {
        $body = array('body' => $this->rawResponse->body);
        return array_merge($this->rawResponse->header, $body);
    }
}
