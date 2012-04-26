<?php

class Postmark_Tests_Attachments extends PHPUnit_Framework_TestCase {

    protected function setUp()
    {
      $this->inbound = new \Postmark\Inbound(file_get_contents('./fixtures/inbound.json'));
    }

    public function testAttachments()
    { 
        $dir = dirname(__FILE__).'/';
        $attachments = $this->inbound->Attachments();

        $first_attachment = $attachments->get(0);
        $this->assertEquals($first_attachment->Name, 'myimage.png');
        $this->assertEquals($first_attachment->ContentType, 'image/png');
        $this->assertEquals($first_attachment->ContentLength, 4096);
        $first_attachment->Download($dir);
        $this->assertTrue(file_exists($dir.'/myimage.png'));

        $second_attachment = $attachments->get(1);
        $this->assertEquals($second_attachment->Name, 'mypaper.doc');
        $this->assertEquals($second_attachment->ContentType, 'application/msword');
        $this->assertEquals($second_attachment->ContentLength, 16384);
        $second_attachment->Download($dir);
        $this->assertTrue(file_exists($dir.'/mypaper.doc'));
    }

    public function testHasAttachments()
    { 
        $this->assertTrue($this->inbound->HasAttachments());
    }

    public function tearDown() 
    {
        if(file_exists(dirname(__FILE__).'/myimage.png')) {
            unlink(dirname(__FILE__).'/myimage.png');
        }

        if(file_exists(dirname(__FILE__).'/mypaper.doc')) {
            unlink(dirname(__FILE__).'/mypaper.doc');
        }
    }

}