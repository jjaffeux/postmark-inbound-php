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