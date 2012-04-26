<?php

class Postmark_Tests_Exception extends PHPUnit_Framework_TestCase {


    public function testEmptyJsonException()
    {
        try {
           $this->inbound = new \Postmark\Inbound();
        }
        catch (Postmark\InboundException $expected) {
            return;
        }
 
        $this->fail('Postmark\InboundException has not been raised');
    }

    public function testInvalidJsonException()
    {
        try {
            $this->inbound = new \Postmark\Inbound("{");
        }
        catch (Postmark\InboundException $expected) {
            return;
        }
 
        $this->fail('Postmark\InboundException has not been raised');
    }

}