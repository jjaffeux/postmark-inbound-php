<?php

class Postmark_Tests_Inbound extends PHPUnit_Framework_TestCase {

    protected function setUp()
    {
      $this->inbound = new \Postmark\Inbound(file_get_contents('./fixtures/inbound.json'));
    }

    public function testSubject()
    {
        $this->assertEquals($this->inbound->Subject(), 'This is an inbound message');
    }

    public function testFromEmail()
    {
        $this->assertEquals($this->inbound->FromEmail(), 'myUser@theirDomain.com');
    }

    public function testFromFull()
    {
        $this->assertEquals($this->inbound->FromFull(), 'John Doe <myUser@theirDomain.com>');
    }

    public function testFromName()
    {
        $this->assertEquals($this->inbound->FromName(), 'John Doe');
    }

    public function testDate()
    {
        $this->assertEquals($this->inbound->Date(), 'Thu, 5 Apr 2012 16:59:01 +0200');
    }

    public function testOriginalRecipient()
    {
        $this->assertEquals($this->inbound->OriginalRecipient(), '451d9b70cf9364d23ff6f9d51d870251569e+ahoy@inbound.postmarkapp.com');
    }

    public function testReplyTo()
    {
        $this->assertEquals($this->inbound->ReplyTo(), 'myUsersReplyAddress@theirDomain.com');
    }

    public function testMailboxHash()
    {
        $this->assertEquals($this->inbound->MailboxHash(), 'ahoy');
    }

    public function testTag()
    {
        $this->assertEquals($this->inbound->Tag(), 'awesome');
    }

    public function testMessageID()
    {
        $this->assertEquals($this->inbound->MessageID(), '22c74902-a0c1-4511-804f2-341342852c90');
    }

    public function testTextBody()
    {
        $this->assertEquals(strlen($this->inbound->TextBody()), 7);
    }

    public function testHtmlBody()
    {
        $this->assertEquals(strlen($this->inbound->HtmlBody()), 15);
    }

    public function testHeaders()
    {
        $this->assertEquals($this->inbound->Headers(), 'No');
        $this->assertEquals($this->inbound->Headers('X-Spam-Status'), 'No');
        $this->assertEquals($this->inbound->Headers('X-Spam-Checker-Version'), 'SpamAssassin 3.3.1 (2010-03-16) onrs-ord-pm-inbound1.wildbit.com');
        $this->assertEquals($this->inbound->Headers('X-Spam-Score'), '-0.1');
        $this->assertEquals($this->inbound->Headers('X-Spam-Tests'), 'DKIM_SIGNED,DKIM_VALID,DKIM_VALID_AU,SPF_PASS');
        $this->assertEquals($this->inbound->Headers('Received-SPF'), 'pass');
        $this->assertEquals($this->inbound->Headers('MIME-Version'), '1.0');
        $this->assertEquals($this->inbound->Headers('Message-ID'), '<CAGXpo2WKfxHWZ5UFYCR3H_J9SNMG+5AXUovfEFL6DjWBJSyZaA@mail.gmail.com>');
    }

    public function testRecipients()
    {
        $recipients = $this->inbound->Recipients();
        $this->assertEquals(count($recipients), 2);
        $this->assertEquals($recipients[0]->Email, '451d9b70cf9364d23ff6f9d51d870251569e+ahoy@inbound.postmarkapp.com');
        $this->assertEquals($recipients[0]->Name, FALSE);
        $this->assertEquals($recipients[1]->Email, '451d9b70cf9364d23ff025154f870251569e+ahoy@inbound.postmarkapp.com');
        $this->assertEquals($recipients[1]->Name, 'Ian Tofull');
    }

    public function testUndisclosedRecipients()
    { 
        $undisclosed_recipients = $this->inbound->UndisclosedRecipients();
        $this->assertEquals(count($undisclosed_recipients), 2);
        $this->assertEquals($undisclosed_recipients[0]->Email, 'sample.cc@emailDomain.com');
        $this->assertEquals($undisclosed_recipients[0]->Name, 'Full name');
        $this->assertEquals($undisclosed_recipients[1]->Email, 'another.cc@emailDomain.com');
        $this->assertEquals($undisclosed_recipients[1]->Name, 'Another Cc');
    }

}