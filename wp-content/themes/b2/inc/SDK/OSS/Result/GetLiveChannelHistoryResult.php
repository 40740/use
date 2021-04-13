<?php

namespace OSS\Result;

use OSS\Model\GetLiveChannelHistory;

class GetLiveChannelHistoryResult extends Result
{
    /* 搜库资源网 soku.cc*
     * @return
     */
    protected function parseDataFromResponse()
    {
        $content = $this->rawResponse->body;
        $channelList = new GetLiveChannelHistory();
        $channelList->parseFromXml($content);
        return $channelList;
    }
}
