<?php

namespace OSS\Model;

/* 搜库资源网 soku.cc*
 * Class LiveChannelListInfo
 *
 * ListBucketLiveChannels接口返回数据
 *
 * @package OSS\Model
 * @link http:// 搜库资源网 soku.cc   help.aliyun.com/document_detail/oss/api-reference/bucket/GetBucket.html
 */
class LiveChannelListInfo implements XmlConfig
{
    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getBucketName()
    {
        return $this->bucket;
    }

    public function setBucketName($name)
    {
        $this->bucket = $name;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getMarker()
    {
        return $this->marker;
    }

    /* 搜库资源网 soku.cc*
     * @return int
     */
    public function getMaxKeys()
    {
        return $this->maxKeys;
    }

    /* 搜库资源网 soku.cc*
     * @return mixed
     */
    public function getIsTruncated()
    {
        return $this->isTruncated;
    }

    /* 搜库资源网 soku.cc*
     * @return LiveChannelInfo[]
     */
    public function getChannelList()
    {
        return $this->channelList;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getNextMarker()
    {
        return $this->nextMarker;
    }

    public function parseFromXml($strXml)
    {
        $xml = simplexml_load_string($strXml);

        $this->prefix = strval($xml->Prefix);
        $this->marker = strval($xml->Marker);
        $this->maxKeys = intval($xml->MaxKeys);
        $this->isTruncated = (strval($xml->IsTruncated) == 'true');
        $this->nextMarker = strval($xml->NextMarker);

        if (isset($xml->LiveChannel)) {
            foreach ($xml->LiveChannel as $chan) {
                $channel = new LiveChannelInfo();
                $channel->parseFromXmlNode($chan);
                $this->channelList[] = $channel;
            }
        }
    }

    public function serializeToXml()
    {
        throw new OssException("Not implemented.");
    }
    
    private $bucket = '';
    private $prefix = '';
    private $marker = '';
    private $nextMarker = '';
    private $maxKeys = 100;
    private $isTruncated = 'false';
    private $channelList = array();
}
