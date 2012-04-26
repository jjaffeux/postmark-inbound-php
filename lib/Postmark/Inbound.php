<?php
namespace Postmark;

include('Exception.php');
include('Attachments.php');
include('Attachment.php');

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
                if($header->Name == 'Received-SPF') {
                    return self::_parseReceivedSpf($header->Value);
                }

                return $header->Value;
            }
            else {
                unset($header);
            }
        }

        return $header ? $header : FALSE;
    }

    private static function _parseReceivedSpf($header)
    {
        preg_match_all('/^(\w+\b.*?){1}/', $header, $matches);
        return strtolower($matches[1][0]);
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

    public function Attachments() {
        return new Attachments($this->source->Attachments);
    }

    public function HasAttachments() {
        return count($this->source->Attachments) > 0 ? TRUE : FALSE;
    }

}