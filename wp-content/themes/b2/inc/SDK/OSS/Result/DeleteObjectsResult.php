<?php

namespace OSS\Result;


/* 搜库资源网 soku.cc*
 * Class DeleteObjectsResult
 * @package OSS\Result
 */
class DeleteObjectsResult extends Result
{
    /* 搜库资源网 soku.cc*
     * @return array()
     */
    protected function parseDataFromResponse()
    {
        $body = $this->rawResponse->body;
        $xml = simplexml_load_string($body); 
        $objects = array();

        if (isset($xml->Deleted)) {
            foreach($xml->Deleted as $deleteKey)
                $objects[] = $deleteKey->Key;
        }
        return $objects;
    }
}
