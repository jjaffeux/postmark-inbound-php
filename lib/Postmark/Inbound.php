<?php
namespace Postmark;

/**
 * This is a simple API wrapper for Postmark Inbound Hook (http://developer.postmarkapp.com/developer-inbound.html)
 *
 * Basic Usage:
 * 
 *     $inbound = new \Postmark\Inbound(file_get_contents('php://input'));
 *  OR for local testing
 *     $inbound = new \Postmark\Inbound(file_get_contents('/path/to/json'));
 * 
 * @package    PostmarkInbound
 * @author     Joffrey Jaffeux
 * @copyright  2012 Joffrey Jaffeux
 * @license    MIT License
 */
class Inbound {

    public function __construct($json)
    {
       $this->json = $json;
       $this->source = $this->_jsonToArray();
    }

    public static function source()
    {
        return $this->source;
    }

    public static function json()
    {
        return $this->json;
    }

    private function _jsonToArray()
    {
        $source = json_decode($this->json, FALSE);

        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return $source;
            break;
            default:
                throw new PostmarkInboundException('Posmark Inbound Error: json format error');
            break;
        }
    }

    public function __call($name, $arguments)
    {
        return ($this->source->$name) ? $this->source->$name : FALSE;
    }

    public function FromEmail()
    {
        return $this->source->FromFull->Email;
    }

    public function FromFull()
    {
        return $this->source->FromFull->Name . ' <' . $this->source->FromFull->Email . '>';
    }

    public function FromName()
    {
        return $this->source->FromFull->Name;
    }

    public function Headers($name = 'X-Spam-Status') {
        foreach($this->source->Headers as $header) {
            if(isset($header->Name) AND $header->Name == $name) {
                return $header->Value;
            }
        }
        return FALSE;
    }

    public function Recipients()
    {
        return self::_parseRecipientsAndUndisclosedRecipients($this->source->ToFull);
    }

    public function UndisclosedRecipients()
    {
        return self::_parseRecipientsAndUndisclosedRecipients($this->source->CcFull);
    }

    private static function _parseRecipientsAndUndisclosedRecipients($recipients)
    {
        $objects = array_map(function ($object) {
            $object = get_object_vars($object);

            if( ! empty($object['Name'])) {
                $object['Name'] = $object['Name'];
            }
            else {
                $object['Name'] = FALSE;
            }

            $object['Email'] = $object['Email'];

            return (object)$object;

        }, $recipients);

        return $objects;
    }

    public function attachments() {
        return New Attachments($this->source->Attachments);
    }

    public function HasAttachments() {
        return count($this->source->Attachments) > 0 ? TRUE : FALSE;
    }

}

Class Attachments extends \Postmark\Inbound  implements \Iterator{

    public function __construct($attachments) {
        $this->attachments = $attachments;
        $this->position = 0;
    }

    function get($key) {
        $this->position = $key;

        if( ! empty($this->attachments[$key])) {
            return New Attachment($this->attachments[$key]);
        } else {
            return FALSE;
        }
    }

    function rewind() {
        $this->position = 0;
    }

    function current() {
        return New Attachment($this->attachments[$this->position]);
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        return isset($this->attachments[$this->position]);
    }

}

Class Attachment extends \Postmark\Attachments {

    public function __construct($attachment) {
        $this->Attachment = $attachment;
        $this->Name = $this->Attachment->Name;
        $this->ContentType = $this->Attachment->ContentType;
        $this->ContentLength = $this->Attachment->ContentLength;
        $this->Content = $this->Attachment->Content;
    }

    private function _read() {
        return base64_decode(chunk_split($this->Attachment->Content));
    }
    
    public function Download($directory) {
        if(file_put_contents($directory . $this->Name, $this->_read()) === false) {
            throw new PostmarkInboundException('Posmark Inbound Error: cannot save the file, check path and rights');
        }
    }
}


class PostmarkInboundException extends \Exception {}