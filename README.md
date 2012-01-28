POSTMARK INBOUND HOOK
=====================

This is a simple API wrapper for Postmark Inbound Hook (http://developer.postmarkapp.com/developer-inbound.html)


Usage
-----

``` php
include 'lib/postmark_inbound.php';

//only for testing purpose
function exception_handler($exception) {
  echo "Uncaught exception: " . $exception->getMessage() . "\n";
}
set_exception_handler('exception_handler');

//load json
$inbound = New PostmarkInbound(file_get_contents(dirname(__FILE__).'/tests/fixtures/valid_http_post.json'));

/* Content */
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

/* Headers */
$inbound->headers();  //default to get Date
$inbound->headers('MIME-Version');
$inbound->headers('Received-SPF');

/* Attachments */
$inbound->has_attachments(); //boolean
$attachments = $inbound->attachments();

foreach($attachments as $a) {
	echo "<p>Attachment Name : ".$a->name()."</p>";
	echo "<p>Attachment Content Type : ".$a->content_type()."</p>";
	echo "<p>Attachment Content Length : ".$a->content_length()."</p>";
	$a->download(dirname(__FILE__).'/tests/fixtures/', array('allowed_content_types' => 'image/png'), '10000'); //second and third are optionnals
}

/* Get raw data */
$inbound::json();
$inbound::source();
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


Authors
-------

**Joffrey Jaffeux**

+ http://twitter.com/joffreyjaffeux
+ http://github.com/jjaffeux

License
---------------------

DON'T BE A DICK PUBLIC LICENSE