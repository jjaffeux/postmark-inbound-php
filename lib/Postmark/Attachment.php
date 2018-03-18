<?php

namespace Postmark;

Class Attachment {

    public function __construct($attachment)
    {
//        $this->Attachment = $attachment;
        $this->Name = $attachment->Name;
        $this->ContentType = $attachment->ContentType;
        $this->ContentLength = $attachment->ContentLength;
        $this->Content = $attachment->Content;
        $this->ContentID = property_exists($attachment, 'ContentID') ? $attachment->ContentID : NULL; // Allows to use the attribute 'ContentID' if it exists. Returns NULL if it does not exist or the value of 'ContentID' if it does.
    }

    private function _read()
    {
        return base64_decode(chunk_split($this->Content));
    }
    
    public function Download($directory)
    {
        file_put_contents($directory . $this->Name, $this->_read());
    }
}
