<?php

namespace OSS\Result;

/* 搜库资源网 soku.cc*
 * Class ExistResult 检查bucket和object是否存在的返回结果，
 * 根据返回response的http status判断
 * @package OSS\Result
 */
class ExistResult extends Result
{
    /* 搜库资源网 soku.cc*
     * @return bool
     */
    protected function parseDataFromResponse()
    {
        return intval($this->rawResponse->status) === 200 ? true : false;
    }

    /* 搜库资源网 soku.cc*
     * 根据返回http状态码判断，[200-299]即认为是OK, 判断是否存在的接口，404也认为是一种
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