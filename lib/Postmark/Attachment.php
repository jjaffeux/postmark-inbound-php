<?php

namespace Postmark;

Class Attachment extends \Postmark\Inbound {

    public function __construct($attachment)
    {
        $this->Attachment = $attachment;
        $this->Name = $this->Attachment->Name;
        $this->ContentType = $this->Attachment->ContentType;
        $this->ContentLength = $this->Attachment->ContentLength;
        $this->Content = $this->Attachment->Content;
        $this->ContentID = array_key_exists('ContentID', $this->Attachment) ? $this->Attachment->ContentID : NULL; // Allows to use the attribute 'ContentID' if it exists. Returns NULL if it does not exist or the value of 'ContentID' if it does.
    }

    private function _read()
    {
        return base64_decode(chunk_split($this->Attachment->Content));
    }
    
    public function Download($directory)
    {
        file_put_contents($directory . $this->Name, $this->_read());
    }
}
