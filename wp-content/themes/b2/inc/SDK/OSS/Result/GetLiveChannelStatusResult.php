<?php

namespace OSS\Result;

use OSS\Model\GetLiveChannelStatus;

class GetLiveChannelStatusResult extends Result
{
    /* 搜库资源网 soku.cc*
     * @return
     */
    protected function parseDataFromResponse()
    {
        $content = $this->rawResponse->body;
        $channelList = new GetLiveChannelStatus();
        $channelList->parseFromXml($content);
        return $channelList;
    }
}
