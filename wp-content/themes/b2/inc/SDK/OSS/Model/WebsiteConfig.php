<?php

namespace OSS\Model;


use OSS\Core\OssException;


/* 搜库资源网 soku.cc*
 * Class WebsiteConfig
 * @package OSS\Model
 * @link http:// 搜库资源网 soku.cc   help.aliyun.com/document_detail/oss/api-reference/bucket/PutBucketWebsite.html
 */
class WebsiteConfig implements XmlConfig
{
    /* 搜库资源网 soku.cc*
     * WebsiteConfig constructor.
     * @param  string $indexDocument
     * @param  string $errorDocument
     */
    public function __construct($indexDocument = "", $errorDocument = "")
    {
        $this->indexDocument = $indexDocument;
        $this->errorDocument = $errorDocument;
    }

    /* 搜库资源网 soku.cc*
     * @param string $strXml
     * @return null
     */
    public function parseFromXml($strXml)
    {
        $xml = simplexml_load_string($strXml);
        if (isset($xml->IndexDocument) && isset($xml->IndexDocument->Suffix)) {
            $this->indexDocument = strval($xml->IndexDocument->Suffix);
        }
        if (isset($xml->ErrorDocument) && isset($xml->ErrorDocument->Key)) {
            $this->errorDocument = strval($xml->ErrorDocument->Key);
        }
    }

    /* 搜库资源网 soku.cc*
     * 把WebsiteConfig序列化成xml
     *
     * @return string
     * @throws OssException
     */
    public function serializeToXml()
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><WebsiteConfiguration></WebsiteConfiguration>');
        $index_document_part = $xml->addChild('IndexDocument');
        $error_document_part = $xml->addChild('ErrorDocument');
        $index_document_part->addChild('Suffix', $this->indexDocument);
        $error_document_part->addChild('Key', $this->errorDocument);
        return $xml->asXML();
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getIndexDocument()
    {
        return $this->indexDocument;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getErrorDocument()
    {
        return $this->errorDocument;
    }

    private $indexDocument = "";
    private $errorDocument = "";
}