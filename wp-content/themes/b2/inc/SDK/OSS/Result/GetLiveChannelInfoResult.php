<?php

namespace OSS\Result;

use OSS\Model\GetLiveChannelInfo;

class GetLiveChannelInfoResult extends Result
{
    /* 搜库资源网 soku.cc*
     * @return
     */
    protected function parseDataFromResponse()
    {
        $content = $this->rawResponse->body;
        $channelList = new GetLiveChannelInfo();
        $channelList->parseFromXml($content);
        return $channelList;
    }
}
