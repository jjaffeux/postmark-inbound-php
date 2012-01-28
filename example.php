<?php
include 'lib/postmark_inbound.php';

//only for testing purpose
function exception_handler($exception) {
  "Uncaught exception: " , $exception->getMessage(), "\n";
}
set_exception_handler('exception_handler');

//load json
$inbound = New PostmarkInbound(file_get_contents(getcwd().'/tests/fixtures/valid_http_post.json'));

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