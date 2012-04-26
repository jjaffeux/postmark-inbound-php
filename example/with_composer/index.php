<?php

require 'vendor/autoload.php';

// this file should be the target of the callback you set in your postmark account
$inbound = new \Postmark\Inbound(file_get_contents('../../fixtures/inbound.json'));
//$inbound = new \Postmark\Inbound(file_get_contents('php://input'));

echo $inbound->Subject();
echo $inbound->FromEmail();