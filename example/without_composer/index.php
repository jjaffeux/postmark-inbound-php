<?php

require_once '../../lib/Postmark/Autoloader.php';
\Postmark\Autoloader::register();

// this file should be the target of the callback you set in your postmark account
$inbound = new \Postmark\Inbound(file_get_contents('../../fixtures/inbound.json'));
//$inbound = new \Postmark\Inbound(file_get_contents('php://input'));

echo $inbound->Subject();
echo $inbound->FromEmail();