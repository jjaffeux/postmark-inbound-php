POSTMARK INBOUND HOOK
=====================

This is a simple API wrapper for Postmark Inbound Hook (http://developer.postmarkapp.com/developer-inbound.html)


Usage
-----

``` include 'lib/postmark_inbound.php';
$inbound = New PostmarkInbound(file_get_contents('php://input'));
//OR for local testing
$inbound = New PostmarkInbound(file_get_contents(/path/to/file.json'));
``` 

Bug tracker
-----------

Have a bug? Please create an issue here on GitHub!


Contributions
-------------

1- Fork
2- Write tests (using enhance http://www.enhance-php.com/Content/Documentation/)
3- Write Code
4- Pull request

Thanks for your help.


TODO
----

1- Write more tests
2- Rewrite attachments
3- Better examples


Authors
-------

**Joffrey Jaffeux**

+ http://twitter.com/joffreyjaffeux
+ http://github.com/joffreyjaffeux

License
---------------------

DON'T BE A DICK PUBLIC LICENSE