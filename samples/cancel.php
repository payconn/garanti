<?php

require_once __DIR__.'/../vendor/autoload.php';

use Payconn\Garanti;
use Payconn\Garanti\Model\Cancel;
use Payconn\Garanti\Token;

$token = new Token('30691297', '7000679', '123qweASD/');
$cancel = new Cancel();
$cancel->setTestMode(true);
$cancel->setAmount(100);
$cancel->setReturnedOrderId('915805719230');
$cancel->setOrderId('GVP1559917965');
$response = (new Garanti($token))->cancel($cancel);
print_r([
    'isSuccessful' => (int) $response->isSuccessful(),
    'message' => $response->getResponseMessage(),
    'code' => $response->getResponseCode(),
]);
