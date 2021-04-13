<?php

namespace OSS\Result;

use OSS\Model\CnameConfig;

class GetCnameResult extends Result
{
    /* 搜库资源网 soku.cc*
     * @return CnameConfig
     */
    protected function parseDataFromResponse()
    {
        $content = $this->rawResponse->body;
        $config = new CnameConfig();
        $config->parseFromXml($content);
        return $config;
    }
}