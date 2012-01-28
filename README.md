POSTMARK INBOUND HOOK
=====================

This is a simple API wrapper for Postmark Inbound Hook (http://developer.postmarkapp.com/developer-inbound.html)


Usage
-----

``` php
include 'lib/postmark_inbound.php';

$inbound = New PostmarkInbound(file_get_contents('php://input'));
//OR for local testing
$inbound = New PostmarkInbound(file_get_contents(/path/to/file.json'));

/* Content */
$inbound->subject();
$inbound->from();
$inbound->from_name();
$inbound->from_email();
$inbound->to();
$inbound->bcc();
$inbound->tag();
$inbound->message_id();
$inbound->mailbox_hash();
$inbound->reply_to();
$inbound->html_body();
$inbound->text_body();
$inbound->headers();

/* Headers */
$inbound->headers('MIME-Version');
$inbound->headers('Received-SPF');

/* Attachments */
$inbound->has_attachments();
$attachments = $inbound->attachments();

foreach($attachments as $a) {
	$a->name();
	$a->content_type();
	$a->content_length();
	$a->download(dirname(__FILE__).'/fixtures/', array('allowed_content_types' => 'image/png'), '10000');
}
``` 

Bug tracker
-----------

Have a bug? Please create an issue here on GitHub!


Contributions
-------------

* Fork
* Write tests (using enhance http://www.enhance-php.com/Content/Documentation/)
* Write Code
* Pull request

Thanks for your help.


TODO
----

* Write more tests
* Rewrite attachments
* Better examples


Authors
-------

**Joffrey Jaffeux**

+ http://twitter.com/joffreyjaffeux
+ http://github.com/jjaffeux

License
---------------------

DON'T BE A DICK PUBLIC LICENSE