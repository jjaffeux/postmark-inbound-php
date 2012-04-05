POSTMARK INBOUND HOOK
=====================

This is a simple API wrapper for Postmark Inbound Hook (http://developer.postmarkapp.com/developer-inbound.html)


Usage
-----

``` php
include 'lib/postmark_inbound.php';

//load json
$inbound = New PostmarkInbound(file_get_contents('php://input'));
// or test with $inbound = New PostmarkInbound(file_get_contents(dirname(__FILE__).'/tests/fixtures/valid_http_post.json'));

/* Content */
$inbound->from(); // Bob Bobson <bob@bob.com>
$inbound->from_name(); // Bob Bobson
$inbound->from_email(); // <bob@bob.com>

$recipients = $inbound->to();

foreach($recipients as $recipient) {
	echo $recipient->name; //if not set prints undefined
	echo $recipient->email;
}

$undisclosed_recipients = $inbound->cc();

foreach($undisclosed_recipients as $undisclosed_recipient) {
	echo $undisclosed_recipient->name; //if not set prints undefined
	echo $undisclosed_recipient->email;
}

$inbound->bcc();
$inbound->tag();
$inbound->message_id();
$inbound->mailbox_hash();
$inbound->reply_to();
$inbound->html_body();
$inbound->text_body();
$inbound->date();

/* Headers */
$inbound->headers();  //default to get Date
$inbound->headers('MIME-Version');
$inbound->headers('Received-SPF');
$inbound->headers('Date');

/* Spam */
$inbound->spam(); //default to get status
$inbound->spam('X-Spam-Checker-Version');
$inbound->spam('X-Spam-Score');
$inbound->spam('X-Spam-Tests');
$inbound->spam('X-Spam-Status');

/* Attachments */
$inbound->has_attachments(); //boolean
$attachments = $inbound->attachments();

$first_attachment = $attachments->get(0);
$first_attachment->name();

$second_attachment = $attachments->get(1);
$second_attachment->content_length();

$third_attachment = $attachments->get(2); // will return FALSE if it doesn't exist

foreach($attachments as $a) {
	$a->name();
	$a->content_type();
	$a->content_length();
	
	$options = array(
		'directory' => dirname(__FILE__).'/tests/fixtures/',
		'allowed_content_types' => array('image/png', 'text/html', 'text/plain'), //optionnal
		'max_content_length' => 10000 //optionnal
	);

	$a->download($options);
}

/* Get raw data */
$inbound::json();
$inbound::source();
``` 

FAQ
---

* Using the library with codeigniter : https://github.com/jjaffeux/postmark-inbound-php/issues/1


Bug tracker
-----------

Have a bug? Please create an issue here on GitHub!


Contributions
-------------

* Fork
* Write tests (using enhance http://www.enhance-php.com/Content/Documentation/, just open test.php in your browser to launch tests)
* Write Code
* Pull request

Thanks for your help.


Authors
-------

**Joffrey Jaffeux**

+ http://twitter.com/joffreyjaffeux
+ http://github.com/jjaffeux

Inspiration
-----------

Thx to Randy Schmidt for the original ruby wrapper

+ https://github.com/r38y
+ http://forge38.com/


Other libraries
---------------

+ Ruby : https://github.com/r38y/postmark-mitt
+ Python : https://github.com/jpadilla/postmark-inbound-python
+ Node : https://gist.github.com/1647808


License
---------------------

DON'T BE A DICK PUBLIC LICENSE