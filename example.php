<?php

include 'lib/postmark_inbound.php';

//only for testing purpose
function exception_handler($exception) {
  echo "Uncaught exception: " , $exception->getMessage(), "\n";
}

set_exception_handler('exception_handler');

//load json
$inbound = New PostmarkInbound(file_get_contents(getcwd().'/tests/fixtures/valid_http_post.json'));

$attachments = $inbound->attachments();


foreach($attachments as $a) {
	$a->download(dirname(__FILE__).'/', array('allowed_content_types' => 'i/png'), '10000');
}