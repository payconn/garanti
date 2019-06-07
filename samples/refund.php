<?php

require_once __DIR__.'/../vendor/autoload.php';

use Payconn\Garanti;
use Payconn\Garanti\Model\Refund;
use Payconn\Garanti\Token;

$token = new Token('30691297', '7000679', 'PROVRFN', '123qweASD/');
$refund = new Refund();
$refund->setTestMode(true);
$refund->setAmount(50);
$refund->setOrderId('GVP1559899451');
$refund->setReturnedOrderId('915805717003');
$response = (new Garanti($token))->refund($refund);
print_r([
    'isSuccessful' => (int) $response->isSuccessful(),
    'message' => $response->getResponseMessage(),
    'code' => $response->getResponseCode(),
]);
