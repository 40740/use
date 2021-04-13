<?php

namespace OSS\Model;

/* 搜库资源网 soku.cc*
 * Class RefererConfig
 *
 * @package OSS\Model
 * @link http:// 搜库资源网 soku.cc   help.aliyun.com/document_detail/oss/api-reference/bucket/PutBucketReferer.html
 */
class RefererConfig implements XmlConfig
{
    /* 搜库资源网 soku.cc*
     * @param string $strXml
     * @return null
     */
    public function parseFromXml($strXml)
    {
        $xml = simplexml_load_string($strXml);
        if (!isset($xml->AllowEmptyReferer)) return;
        if (!isset($xml->RefererList)) return;
        $this->allowEmptyReferer =
            (strval($xml->AllowEmptyReferer) === 'TRUE' || strval($xml->AllowEmptyReferer) === 'true') ? true : false;

        foreach ($xml->RefererList->Referer as $key => $refer) {
            $this->refererList[] = strval($refer);
        }
    }


    /* 搜库资源网 soku.cc*
     * 把RefererConfig序列化成xml
     *
     * @return string
     */
    public function serializeToXml()
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><RefererConfiguration></RefererConfiguration>');
        if ($this->allowEmptyReferer) {
            $xml->addChild('AllowEmptyReferer', 'true');
        } else {
            $xml->addChild('AllowEmptyReferer', 'false');
        }
        $refererList = $xml->addChild('RefererList');
        foreach ($this->refererList as $referer) {
            $refererList->addChild('Referer', $referer);
        }
        return $xml->asXML();
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    function __toString()
    {
        return $this->serializeToXml();
    }

    /* 搜库资源网 soku.cc*
     * @param boolean $allowEmptyReferer
     */
    public function setAllowEmptyReferer($allowEmptyReferer)
    {
        $this->allowEmptyReferer = $allowEmptyReferer;
    }

    /* 搜库资源网 soku.cc*
     * @param string $referer
     */
    public function addReferer($referer)
    {
        $this->refererList[] = $referer;
    }

    /* 搜库资源网 soku.cc*
     * @return boolean
     */
    public function isAllowEmptyReferer()
    {
        return $this->allowEmptyReferer;
    }

    /* 搜库资源网 soku.cc*
     * @return array
     */
    public function getRefererList()
    {
        return $this->refererList;
    }

    private $allowEmptyReferer = true;
    private $refererList = array();
}