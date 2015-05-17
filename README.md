POSTMARK INBOUND HOOK
=====================

This is a simple API wrapper for Postmark Inbound Hook (http://developer.postmarkapp.com/developer-inbound.html)

[![Build Status](https://secure.travis-ci.org/jjaffeux/postmark-inbound-php.png?branch=master)](http://travis-ci.org/jjaffeux/postmark-inbound-php)


Setup
-----
With composer :
``` json
{
    "require": {
        "jjaffeux/postmark-inbound-php": ">=2.0"
    }
}
```
``` php
$inbound = new \Postmark\Inbound(file_get_contents('php://input'));
``` 

Without composer :
``` php
require_once '../lib/Postmark/Autoloader.php';
\Postmark\Autoloader::register();

// this file should be the target of the callback you set in your postmark account
$inbound = new \Postmark\Inbound(file_get_contents('php://input'));
``` 

General Usage
-------------

``` php
$inbound->Subject();
$inbound->FromEmail();
$inbound->FromFull();
$inbound->FromName();
$inbound->Date();
$inbound->OriginalRecipient();
$inbound->ReplyTo();
$inbound->MailboxHash();
$inbound->Tag();
$inbound->MessageID();
$inbound->TextBody();
$inbound->HtmlBody();
``` 

Headers
-------

``` php
$inbound->Headers(); //default to spam status
$inbound->Headers('X-Spam-Status');
$inbound->Headers('X-Spam-Checker-Version');
$inbound->Headers('X-Spam-Score');
$inbound->Headers('X-Spam-Tests');
$inbound->Headers('Received-SPF');
$inbound->Headers('MIME-Version');
$inbound->Headers('Received-SPF'); // pass neutral fail
$inbound->Headers('Message-ID');
``` 


Recipients and Undisclosed Recipients
-------------------------------------

``` php
foreach($inbound->Recipients() as $recipient) {
	$recipient->Name;
	$recipient->Email;
}

foreach($inbound->UndisclosedRecipients() as $undisclosedRecipient) {
	$undisclosedRecipient->Name;
	$undisclosedRecipient->Email;
}
``` 

Attachments
-------------------------------------

``` php
foreach($inbound->Attachments() as $attachment) {
	$attachment->Name;
	$attachment->ContentType;
	$attachment->ContentLength;
	$attachment->Download('/'); //takes directory as first argument
}

$inbound->HasAttachments();
``` 

Raw
---

``` php
$inbound->Source; //array
$inbound->Json; //raw json
``` 


Bug tracker
-----------

Have a bug? Please create an issue here on GitHub!


Contributions
-------------

* Fork
* Write tests (phpunit in the directory to run the tests)
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

MIT License